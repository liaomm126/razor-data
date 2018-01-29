<?php
/**
* 付费用户留存统计
*/
class Playerretainedmodel extends CI_Model 
{

    function __construct() 
    {
        parent::__construct();
    }


    //付费用户留存统计
    function getplayerretainedByDay($from,$to) 
    {

        $total = $this->gettotalcount($from, $to); 
        $userremainday = array();
        foreach ($total as $k => $v) 
        {
            $userremainday[$k]['startdate'] = $v['date'];
            $userremainday[$k]['usercount'] = $v['xzff'];
            //查询120次留存
            for($i = 1;$i<=31; $i++)
            {
                $userremainday[$k]['day'.$i] = $this->getday( $userremainday[$k]['startdate'], $i);
            }

        }
        return $userremainday;
    }



    //统计新增付费用户总数
    function gettotalcount($from, $to) 
    {
        $to   = date('Y-m-d', strtotime("+1 day", strtotime($to))); //+ 1 day
        $dwdb = $this->load->database('dw', true);
        $sql = "
                SELECT DISTINCT
                date(datevalue) date,
                IFNULL(rxz.czcs, 0) xzff
                From ". $dwdb -> dbprefix('dim_date')  ."  dd  
                LEFT JOIN(
                SELECT
                COUNT(DISTINCT ro.role_id) AS did,
                ro.date_sk
                FROM
                ". $dwdb -> dbprefix('role_info')  ."  ro
                WHERE
                ro.create_role_time  BETWEEN  '$from'  AND  '$to'
                GROUP BY
                ro.date_sk
                ) red ON red.date_sk = dd.date_sk
                LEFT JOIN(
                SELECT
                COUNT(id) AS czcs,
                date_sk
                FROM
                (
                SELECT
                min(id) AS id,
                count(user_id) AS count,
                device_id,
                date_sk
                FROM
                ". $dwdb -> dbprefix('order_info')  ."
                WHERE
                app_id = 1
                GROUP BY
                user_id
                HAVING
                count
                ORDER BY
                count DESC
                ) AS tab
                GROUP BY
                date_sk
                ) rxz ON rxz.date_sk = dd.date_sk
                WHERE
                dd.datevalue >= '$from'
                AND dd.datevalue < '$to'
                ORDER BY
                date ASC
                ";
                $query = $dwdb->query($sql);
                $totalinfo = $query->result_array();
                //echo $dwdb ->  last_query();
                return $totalinfo;
    }


    

    function getday($from,$day) 
    {
        
        //设备日新增时间
        $from1 = $from;
        $to1 = date('Y-m-d', strtotime("+1 day", strtotime($from1))); 
        //累计增加时间
        $from2 = date('Y-m-d', strtotime("+".$day." day", strtotime($from))); 
        $to2 = date('Y-m-d', strtotime("+1 day", strtotime($from2))); 
        //调用数据库
        $dwdb = $this->load->database('dw', true);
        $sql  = "
                SELECT
                COUNT(DISTINCT lo.user_id) as did 
                FROM
                ". $dwdb -> dbprefix('login_info')  ."  lo 
                LEFT JOIN 
                (
                SELECT
                min(id) AS id,
                device_id,
                date_sk,
                user_id,
                rechargedate
                FROM
                ". $dwdb -> dbprefix('order_info')  ." 
                WHERE
                app_id = 1
                GROUP BY
                user_id
                )xzff  ON  xzff.user_id = lo.user_id
                WHERE   
                lo.create_login_ts 
                BETWEEN '$from2' AND  '$to2'
                AND 
                xzff.rechargedate 
                BETWEEN '$from1' AND  '$to1'
                " ;
        $query = $dwdb->query($sql);
       // echo $dwdb -> last_query();
        $did = $query->row()->did;
        return $did;



    }



                // SELECT 
                // COUNT(DISTINCT lo.rus_id) as did 
                // From ". $dwdb -> dbprefix('login_info')  ."  lo  
                // JOIN  ". $dwdb -> dbprefix('role_info')  ."  ro 
                // ON ro.rus_id = lo.rus_id 
                // WHERE lo.create_login_ts  BETWEEN '$from2' and  '$to2' AND
                // ro.create_role_time  BETWEEN '$from1' and  '$to1'




}
