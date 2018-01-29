<?php
/*
*
* 充值数据统计模型
*
*/
class Statisticsmodel extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    function getnewlist($fromTime,$toTime,$productid)
    {
        $data = $this->getorderdata($fromTime,$toTime,$productid);
        $server = array();
        foreach ($data as $key => $value)
        {
            $server[$key]['date'] = $value['date'];
            $server[$key]['ffyh'] = $value['ffyh'];
            $server[$key]['newffyh'] = $value['xzff'];
            $server[$key]['dlffl'] =  number_format($value['ffyh'] / $value['dlyh'] * 100,1) .'%';
            $server[$key]['zcffl'] = number_format($value['xzff'] / $value['xzjs'] * 100,1) .'%';
            $server[$key]['ffcs'] = $value['ffcs'];
            $server[$key]['ffje'] = $value['ffje'];
            $server[$key]['xzffje'] = $value['xzffje'];
            $server[$key]['ffarpu'] = number_format($value['ffje'] / $value['ffyh'],1) ? number_format($value['ffje'] / $value['ffyh'],1)  : 0;
            $server[$key]['dlarpu'] = number_format($value['ffje'] / $value['dlyh'],1) ?  number_format($value['ffje'] / $value['dlyh'],1) : 0 ;
        }
        return  $server;
     }



     //充值数据统计
    function  getorderdata($fromTime,$toTime,$productid)
    {
        $dwdb = $this->load->database('dw',true);
        $sql  = "
                  SELECT  DISTINCT date(datevalue) date,
                  IFNULL(odd.ffyh,0) ffyh ,
                  IFNULL(odd.ffcs,0) ffcs,
                  IFNULL(odd.ffje ,0.00) ffje  ,
                  IFNULL(red.did ,0) xzjs,
                  IFNULL( lod.ldid ,0) dlyh ,
                  IFNULL( rxz.czcs ,0) xzff,
                  IFNULL( rxz.xzffje ,0) xzffje
                  from  "  . $dwdb->dbprefix('dim_date').  "  dd
                  LEFT JOIN
                  (
                  select  COUNT( DISTINCT od.user_id ) ffyh ,
                  COUNT(od.user_id ) ffcs,
                  sum(od.money ) ffje,
                  od.rechargedate,od.date_sk
                  from  "  . $dwdb->dbprefix('order_info').  "  od
                  where  od.rechargedate BETWEEN  '$fromTime'  and '$toTime'
                  AND  od.app_id = ". $productid ."
                  GROUP BY  od.date_sk ) odd  on odd.date_sk = dd.date_sk
                  LEFT JOIN
                  (
                  SELECT
                  COUNT(DISTINCT ro.role_id) AS did,
                  ro.date_sk
                  FROM
                  "  . $dwdb->dbprefix('role_info').  " ro
                  WHERE
                  ro.create_role_time >= '$fromTime'
                  AND ro.create_role_time < '$toTime'
                  GROUP BY
                  ro.date_sk) red
                  on  red.date_sk = dd.date_sk
                  LEFT JOIN
                  (     
                  SELECT
                  COUNT(id) as czcs,date_sk,sum(money) as xzffje
                  FROM
                  (
                  SELECT  
                  min(id) AS id,
                  count(user_id) AS count,
                  device_id,date_sk,money
                  FROM
                    "  . $dwdb->dbprefix('order_info').  "   
                  WHERE  app_id =  ". $productid ."
                  GROUP BY
                    user_id
                  HAVING
                    count
                  ORDER BY
                    count DESC
                  )AS tab
                  GROUP BY  date_sk
                  ) rxz
                  ON  rxz.date_sk  = dd.date_sk
                  LEFT JOIN
                  (
                  SELECT  COUNT(DISTINCT lo.user_id) ldid,
                  lo.create_login_ts ,date_sk
                  from    "  . $dwdb->dbprefix('login_info').  "  lo
                  WHERE lo.create_login_ts
                  BETWEEN  '$fromTime'  and '$toTime'
                  AND  lo.app_id = ". $productid ."
                  group by lo.date_sk) lod
                  on lod.date_sk  = dd.date_sk
                  where 
                  dd.datevalue  >=  '$fromTime'  and   dd.datevalue  < '$toTime'
                  ORDER BY date asc";

        $query = $dwdb->query($sql);
        //echo  $dwdb->last_query();
        $orderinfo = $query->result_array();  //返回对象类型
        return $orderinfo;
    }




    //登录数
    function   getloginnumber($fromTime,$toTime)
    {
        $dwdb = $this->load->database('dw',true);
        $sql = "
              SELECT COUNT( DISTINCT user_id)
              FROM  "  . $dwdb->dbprefix('login_info').  "   lo
              WHERE  lo.create_login_ts  BETWEEN  '$fromTime'  and '$toTime'
              GROUP BY  lo.create_login_ts ";
    }



    //新增用户
    function  gettotalcount($from, $to)
    {
        $to   = date('Y-m-d',strtotime("+1 day",strtotime($to))) ;
        $dwdb = $this->load->database('dw', true);
        $sql  =  "
                SELECT
                date(datevalue) date,
                ifnull( ff.did,0) did
                from " . $dwdb -> dbprefix('dim_date')  . "   dd
                left JOIN
                (
                SELECT
                COUNT(DISTINCT re.user_id) as did ,
                re.create_register_time,
                re.date_sk
                FROM " . $dwdb -> dbprefix('device_info')  . "  de
                LEFT JOIN
                " . $dwdb -> dbprefix('register_info')  . "  re
                on  re.device_id = de.device_id
                WHERE
                re.create_register_time   >=  '$from'   and   re.create_register_time <'$to'
                GROUP BY re.create_register_time)
                ff on ff.date_sk =  dd.date_sk
                where  dd.datevalue  >=  '$from'  AND  dd.datevalue  < '$to'
                GROUP BY dd.datevalue" ;
        $query = $dwdb -> query($sql);
        $totalinfo = $query-> result_array();  //返回数组类型
        //echo $dwdb ->  last_query();
        return $totalinfo;
    }


}