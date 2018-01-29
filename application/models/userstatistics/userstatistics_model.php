<?php
/*
* 在线用户分布
*/

class Userstatistics_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();  //调用父类构造
    }

    //统计在线用户分布数据
    function getnewlist($fromTime,$toTime)
    {   
        $result = $this->getDetailNewData($fromTime,$toTime);                  //调用用户分布数据
        $server = array(); 
        foreach ($result as $key => $value)  
        {
            $server[$key]['xh'] = $key + 1;                              //序号
            $server[$key]['hour'] = $fromTime.'&nbsp;'.$value['hour'];   //在线时间
            $server[$key]['gs']   = $value['did'];                       //在线人数
        }
        return $server ;
    }

    // 返回用户分布数据
    function getDetailNewData($fromTime,$toTime)
    {
        $dwdb = $this->load->database('dw', true);          
        $time = date('Y-m-d',strtotime('+1 day',time()));                   
        $sql  = 
                "SELECT   
                uh.`hour`,IFNULL(fff.did,0) as did
                from  " . $dwdb->dbprefix('user_hour') . "   uh
                LEFT JOIN  
                (SELECT 
                uh.`hour` ,
                COUNT(DISTINCT user_id) as did , 
                uh.hour_sk as hk,ff.st ,
                ff.et 
                from " . $dwdb->dbprefix('user_hour') . "   uh 
                join ( SELECT  
                DISTINCT ud.user_id , 
                ud.start_time  st ,
                ud.end_time  et   
                from  " . $dwdb->dbprefix('user_distribution') . "   ud  
                where   start_time  BETWEEN '$fromTime'  and '$toTime'   
                or  end_time   BETWEEN  '$fromTime'  and '$time'
                ) ff
                where  concat('$fromTime ',`hour`) BETWEEN ff.st and ff.et   
                GROUP BY uh.`hour`
                )fff
                on fff.hk = uh.hour_sk
                GROUP BY uh.`hour` " ;
        $query = $dwdb->query($sql);
        // echo $dwdb->last_query();  //打印最后一条sql语句
        $result = $query->result_array();
        return $result;
    }













































}