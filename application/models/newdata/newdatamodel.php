<?php
/**
* Newdatamodel
*
* 统计数据模型
*
*/
class Newdatamodel extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    //返回新增数据
    function getnewlist($fromTime,$toTime)
    {
        $totalinfo =   $this->gettotalinfo($fromTime,$toTime);                      // 新增数据统计
        $data      = array();
        $newday    = array();
        foreach ( $totalinfo as $k => $v )  
        { 
            $data[$k]['server'] = $v['sname'];                                           // 服务器
            $data[$k]['loginnewequipment'] = $v['rdlsb'];                               // 日登陆设备数 
            $data[$k]['newequipment'] = $v['rxzsb'];                                   // 日新增设备数
            $data[$k]['hynewequipment'] = $v['rdlsb'] - $v['rxzsb'];                  // 日活跃设备数
            $data[$k]['loginnewaccountnumber'] = $v['rdlzh'];                           // 日登陆账号数
            $data[$k]['newaccountnumber'] = $v['rxzyh'];                                  // 日新增账号数
            $data[$k]['hynewaccountnumber'] = $v['rdlzh'] - $v['rxzyh'];                   // 日活跃账号数
            $data[$k]['logindailyactiveaccount'] = $v['rdljs'];                           // 日登陆角色数
            $data[$k]['dailyactiveaccount'] = $v['rxzjs'];                               // 日新增角色数
            $data[$k]['hylogindailyactiveaccount'] = $v['rdljs'] - $v['rxzjs'];         // 日活跃角色数              
        }
        return  $data;
    }




    //联合查询统计  新增  +  登录  + 活跃
    function  gettotalinfo($fromtime,$totime)
    {
        $dwdb = $this->load->database('dw', true);
        $sql  = 
                "SELECT 
                se.sid_sk,
                se.sname,
                IFNULL(fff.rdlsb,0)  rdlsb,
                IFNULL(fff.rxzsb,0)  rxzsb,
                IFNULL(fff.rdlzh,0)  rdlzh, 
                IFNULL(fff.rxzyh,0)  rxzyh,
                IFNULL(fff.rdljs,0)  rdljs,
                IFNULL(fff.rxzjs,0)  rxzjs
                from 
                ".   $dwdb->dbprefix('server_info')  ."   se
                LEFT JOIN
                (
                select 
                COUNT(DISTINCT lo.device_id) as rdlsb ,
                COUNT(DISTINCT lo.user_id) as rdlzh, 
                COUNT(DISTINCT lo.rus_id) as rdljs,
                ff.rxzsb as rxzsb,
                ff.rxzyh as rxzyh,
                ff.rxzjs as rxzjs,
                lo.sid 
                From ".   $dwdb->dbprefix('login_info')  ."   lo 
                LEFT JOIN
                (    
       
                    SELECT
                    COUNT(DISTINCT ded.device_id) AS rxzsb,
                    COUNT(DISTINCT reu.user_id) AS rxzyh,
                    COUNT(DISTINCT ro.role_id) AS rxzjs,
                    ro.sid
                    FROM
                     ".  $dwdb->dbprefix('role_info')  ."  ro
                    LEFT JOIN (
                    SELECT
                    re.user_id,
                    re.date_sk,
                    re.device_id
                    FROM
                    ".  $dwdb->dbprefix('register_info')  ." re 
                    GROUP BY
                    date_sk,
                    user_id
                    ) reu ON reu.user_id = ro.user_id
                    AND reu.device_id    = ro.device_id
                    AND reu.date_sk = ro.date_sk
                    LEFT JOIN (
                    SELECT
                    de.device_id,
                    de.date_sk
                    FROM
                    ".  $dwdb->dbprefix('device_info')  ."  de
                    GROUP BY
                    de.date_sk,
                    de.device_id
                    ) ded ON ded.device_id = ro.device_id
                    AND ded.device_id = reu.device_id
                    AND ded.date_sk = ro.date_sk
                    WHERE
                    ro.create_role_time BETWEEN '$fromtime' and '$totime'
                    GROUP BY
                    ro.sid
                    ) ff ON ff.sid = lo.sid
                    WHERE
                    lo.create_login_ts BETWEEN '$fromtime' and '$totime'
                    GROUP BY
                    lo.sid
                )fff on fff.sid  = se.sid_sk
                ORDER BY sid_sk asc";

                $query = $dwdb->query($sql);
                //echo  $dwdb -> last_query();
                $totalinfo = $query-> result_array();  //返回数组类型
                return  $totalinfo;

              // LEFT JOIN (
              //   SELECT
              //   re.user_id,
              //   re.date_sk,
              //   re.device_id
              //   FROM
              //   ".  $dwdb->dbprefix('register_info')  ." re 
              //   WHERE
              //   re.create_register_time BETWEEN '$fromtime' and '$totime'
              //   GROUP BY
              //   date_sk,
              //   user_id
              //   ) reu ON reu.user_id = ro.user_id
              //   AND reu.date_sk = ro.date_sk
              //   AND reu.device_id    = ro.device_id
              //   LEFT JOIN (
              //   SELECT
              //   de.device_id,
              //   de.date_sk
              //   FROM
              //   ".  $dwdb->dbprefix('device_info')  ."  de
              //   WHERE
              //   de.activity_time BETWEEN '$fromtime' and '$totime'
              //   GROUP BY
              //   de.date_sk,
              //   de.device_id
              //   ) ded ON ded.device_id = ro.device_id
              //   AND ded.date_sk = ro.date_sk
              //   AND ded.device_id = reu.device_id

              //二
              //  LEFT JOIN    ".  $dwdb->dbprefix('register_info')  ." re   ON re.user_id = ro.user_id
              //LEFT JOIN  ".  $dwdb->dbprefix('device_info')  ."  de     ON de.device_id =   ro.device_id
    }


    // --日登录设备 、用户 、角色
    function  total_login($fromTime,$toTime)
    {

        $dwdb = $this->load->database('dw', true);
        $sql = "
                SELECT
                COUNT( DISTINCT lo.device_id) as rdlsb,
                COUNT( DISTINCT lo.user_id) as rdlyh,
                COUNT( DISTINCT lo.rus_id) as rdljs
                FROM
                " .  $dwdb->dbprefix('login_info')  .  "  lo 
                WHERE
                lo.create_login_ts   BETWEEN   '$fromTime' and '$toTime' ";

        $query = $dwdb->query($sql);
        $result = $query->row_array();
        return  $result;
    }






    // --日新增设备、用户、角色 
    function  total_newdata($fromtime,$totime)
    {

        $dwdb = $this->load->database('dw', true);
        $sql = "
                    SELECT
                    COUNT(DISTINCT ded.device_id) AS rxzsb,
                    COUNT(DISTINCT reu.user_id) AS rxzyh,
                    COUNT(DISTINCT ro.role_id) AS rxzjs,
                    ro.sid
                    FROM
                     ".  $dwdb->dbprefix('role_info')  ."  ro
                  
                    LEFT JOIN (
                    SELECT
                    re.user_id,
                    re.date_sk,
                    re.device_id
                    FROM
                    ".  $dwdb->dbprefix('register_info')  ." re 
                    GROUP BY
                    date_sk,
                    user_id
                    ) reu ON reu.user_id = ro.user_id
                    AND reu.device_id    = ro.device_id
                    AND reu.date_sk  =  ro.date_sk
                    LEFT JOIN (
                    SELECT
                    de.device_id,
                    de.date_sk
                    FROM
                    ".  $dwdb->dbprefix('device_info')  ."  de
                    GROUP BY
                    de.date_sk,
                    de.device_id
                    ) ded ON ded.device_id = ro.device_id
                    AND ded.device_id = reu.device_id
                    AND  ded.date_sk  = ro.date_sk
                    WHERE
                    ro.create_role_time BETWEEN '$fromtime' and '$totime'
                ";
        $query = $dwdb->query($sql);
        //echo  $dwdb -> last_query();
        $result = $query->row_array();
        return  $result;

    }

// SELECT
// COUNT(DISTINCT de.device_id) AS rxzsb,
// COUNT(DISTINCT re.user_id) AS rxzyh,
// COUNT(DISTINCT ro.role_id) AS rxzjs,
// ro.sid
// FROM
// ".  $dwdb->dbprefix('role_info')  ."  ro
// LEFT JOIN    ".  $dwdb->dbprefix('register_info')  ." re   ON re.user_id = ro.user_id
// LEFT JOIN  ".  $dwdb->dbprefix('device_info')  ."  de     ON de.device_id =   ro.device_id
// WHERE
// ro.create_role_time BETWEEN '$fromtime' and '$totime'


// SELECT
//                  COUNT(DISTINCT ded.device_id) AS rxzsb,
//                  COUNT(DISTINCT reu.user_id) AS rxzyh,
//                  COUNT(DISTINCT ro.role_id) AS rxzjs,
//                  ro.sid
//                  FROM
//                   ".  $dwdb->dbprefix('role_info')  ."  ro
//                  LEFT JOIN (
//                  SELECT
//                  re.user_id,
//                  re.date_sk,
//                  re.device_id
//                  FROM
//                  ".  $dwdb->dbprefix('register_info')  ." re
//                  GROUP BY
//                  date_sk,
//                  user_id
//                  ) reu ON reu.user_id = ro.user_id
//                  AND reu.device_id    = ro.device_id
//                  AND reu.date_sk  =  ro.date_sk
//                  LEFT JOIN (
//                  SELECT
//                  de.device_id,
//                  de.date_sk
//                  FROM
//                  ".  $dwdb->dbprefix('device_info')  ."  de
//                  GROUP BY
//                  de.date_sk,
//                  de.device_id
//                  ) ded ON ded.device_id = ro.device_id
//                  AND ded.device_id = reu.device_id
//                  AND  ded.date_sk  = ro.date_sk
//                  WHERE
//                  ro.create_role_time BETWEEN '$fromtime' and '$totime'

        
}