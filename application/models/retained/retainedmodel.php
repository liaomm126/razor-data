<?php
/**
* 设备留存统计
*/
class Retainedmodel extends CI_Model 
{

    function __construct() 
    {
        parent::__construct();
    }


    //每日设备留存效率
    function getUserRemainCountByDay($from, $to) 
    {   
        //统计日新增总设备
        $total = $this->gettotalcount($from, $to);
        $userremainday = array();
        foreach ($total as $k => $v) 
        {
            $userremainday[$k]['startdate'] = $v['date'];
            $userremainday[$k]['usercount'] = $v['did'];
            $userremainday[$k]['day1'] = $this->getday( $userremainday[$k]['startdate'], 1);
            $userremainday[$k]['day2'] = $this->getday( $userremainday[$k]['startdate'], 2);
            $userremainday[$k]['day3'] = $this->getday( $userremainday[$k]['startdate'], 3);
            $userremainday[$k]['day4'] = $this->getday( $userremainday[$k]['startdate'], 4);
            $userremainday[$k]['day5'] = $this->getday( $userremainday[$k]['startdate'], 5);
            $userremainday[$k]['day6'] = $this->getday( $userremainday[$k]['startdate'], 6);
            $userremainday[$k]['day7'] = $this->getday( $userremainday[$k]['startdate'], 7);
            $userremainday[$k]['day15']= $this->getday( $userremainday[$k]['startdate'], 15);
        }
        return $userremainday;
    }



    //统计每天设备总数
    function gettotalcount($from, $to) 
    {
        $to   = date('Y-m-d', strtotime("+1 day", strtotime($to))); //+ 1 day
        $dwdb = $this->load->database('dw', true);
        $sql  = "SELECT 
                date(datevalue) date,  
                ifnull( ff.did,0) did   
                from " . $dwdb->dbprefix('dim_date') . "   dd
                left JOIN(  
                SELECT COUNT(DISTINCT de.device_id) as did, 
                de.activity_time ,
                de.date_sk  
                FROM   " . $dwdb->dbprefix('device_info') . " de
                WHERE   de.activity_time >= '$from'    and    de.activity_time < '$to' 
                GROUP BY de.date_sk) 
                ff on ff.date_sk =  dd.date_sk 
                where  dd.datevalue     >= '$from'  AND  dd.datevalue  <'$to'  
                GROUP BY dd.datevalue";
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
        $sql =  "SELECT  
                 COUNT(DISTINCT lo.device_id) as did 
                 from  " . $dwdb->dbprefix('login_info') . "  lo  
                 JOIN  " . $dwdb->dbprefix('device_info') . " de 
                 on de.device_id = lo.device_id 
                 where lo.create_login_ts  BETWEEN '$from2' and  '$to2' AND
                 de.activity_time  BETWEEN '$from1' and  '$to1' ";
        $query = $dwdb->query($sql);
        //echo $dwdb ->  last_query();
        $count_did = $query->row()->did;
        return $count_did;

    }














}
