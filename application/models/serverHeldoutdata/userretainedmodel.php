<?php
/**
* 区服账号留存统计
*/
class Userretainedmodel extends CI_Model 
{

    function __construct() 
    {
        parent::__construct();
    }


    //每日账号留存效率
    function getUserRemainCountByDay($from,$to) 
    {

        //统计日新增总账号
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
            $userremainday[$k]['day30']= $this->getday( $userremainday[$k]['startdate'], 30);
            $userremainday[$k]['day90']= $this->getday( $userremainday[$k]['startdate'], 90);
        }
        return $userremainday;
    }



    //统计每天账号总数
    function gettotalcount($from, $to) 
    {
        $to   = date('Y-m-d', strtotime("+1 day", strtotime($to))); //+ 1 day
        $dwdb = $this->load->database('dw', true);
        $sql = 
                "SELECT 
                date(datevalue) date,  
                ifnull( ff.did,0) did   
                from " . $dwdb -> dbprefix('dim_date')  . "   dd
                left JOIN(
                SELECT 
                COUNT(DISTINCT re.user_id) as did ,
                re.create_register_time ,
                re.date_sk 
                FROM  " . $dwdb -> dbprefix('register_info')  . "  re    
                WHERE re.create_register_time   >=  '$from'   and   re.create_register_time <'$to'
                GROUP BY re.date_sk) 
                ff on ff.date_sk =  dd.date_sk  
                where  dd.datevalue  >=  '$from'  AND  dd.datevalue  < '$to'    
                GROUP BY dd.datevalue" 
                ;
        $query = $dwdb->query($sql);
        $totalinfo = $query->result_array();
       // echo $dwdb ->  last_query();
        return $totalinfo;
    }


    //统计每天各区服账号总数
    function getservertotalcount($from, $to,$sid) 
    {
        $to   = date('Y-m-d', strtotime("+1 day", strtotime($to))); //+ 1 day
        $dwdb = $this->load->database('dw', true);
        $sql = 
                "
                SELECT date(datevalue) date, 
                ifnull( ff.did,0) did 
                from " . $dwdb -> dbprefix('dim_date')  . "  dd 
                left JOIN(
                select COUNT(ro.role_id) as did , ro.date_sk 
                from   " . $dwdb -> dbprefix('role_info')  . "  ro 
                WHERE  ro.create_role_time  >=   '$from'   and  ro.create_role_time <  ' $to'   
                AND sid = '$sid'
                GROUP BY ro.date_sk
                )ff on   ff.date_sk = dd.date_sk 
                where  dd.datevalue  >=  '$from'   AND  dd.datevalue < ' $to'  
                GROUP BY dd.datevalue" ;
        $query = $dwdb->query($sql);
        $totalinfo = $query->result_array();
       // echo $dwdb ->  last_query();
        return $totalinfo;
    }



    //获取账号留存数
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
        $sql  = 
                "SELECT  
                COUNT( did) as did   
                From  ". $dwdb -> dbprefix('register_info')  ."  re
                left join 
                (
                SELECT 
                DISTINCT lo.user_id as did  , 
                lo.device_id ,lo.user_id  
                From ". $dwdb -> dbprefix('login_info')  ."  lo 
                WHERE lo.create_login_ts BETWEEN '$from2' and  '$to2'
                ) ff  
                ON ff.user_id = re.user_id
                WHERE re.create_register_time  BETWEEN '$from1' and  '$to1' ";
        $query = $dwdb->query($sql);
        //echo $dwdb ->  last_query();
        $count_did = $query->row()->did;
        return $count_did;

    }



     //按区查询获取账号留存率 
    function getserverday($from,$day,$sid) 
    {
        
        //设备日新增时间
        $from1 = $from;
        $to1 = date('Y-m-d', strtotime("+1 day", strtotime($from1))); 
        //累计增加时间
        $from2 = date('Y-m-d', strtotime("+".$day." day", strtotime($from))); 
        $to2 = date('Y-m-d', strtotime("+1 day", strtotime($from2))); 
        //调用数据库
        $dwdb = $this->load->database('dw', true);
        $sql  =
                "SELECT  
                COUNT( did) as did   
                From  ". $dwdb -> dbprefix('register_info')  ."  re
                left join 
                (
                SELECT 
                DISTINCT lo.user_id as did  , 
                lo.device_id ,lo.user_id  
                From ". $dwdb -> dbprefix('login_info')  ."  lo 
                WHERE lo.create_login_ts BETWEEN '$from2' and  '$to2'
                ) ff  
                ON ff.user_id = re.user_id
                WHERE re.create_register_time  BETWEEN '$from1' and  '$to1' AND sid = '$sid' ";
        $query = $dwdb->query($sql);
        //echo $dwdb ->  last_query();
        $count_did = $query->row()->did;
        return $count_did;

    }





    //详细表查询
    function getdetailedByDay($from,$to)
    {

        //统计日新增总账号
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
            $userremainday[$k]['day8'] = $this->getday( $userremainday[$k]['startdate'], 8);
            $userremainday[$k]['day9'] = $this->getday( $userremainday[$k]['startdate'], 9);
            $userremainday[$k]['day10']= $this->getday( $userremainday[$k]['startdate'],10);
            $userremainday[$k]['day11']= $this->getday( $userremainday[$k]['startdate'],11);
            $userremainday[$k]['day12']= $this->getday( $userremainday[$k]['startdate'],12);
            $userremainday[$k]['day13']= $this->getday( $userremainday[$k]['startdate'],13);
            $userremainday[$k]['day14']= $this->getday( $userremainday[$k]['startdate'],14);
            $userremainday[$k]['day15']= $this->getday( $userremainday[$k]['startdate'], 15);
            $userremainday[$k]['day16']= $this->getday( $userremainday[$k]['startdate'], 16);
            $userremainday[$k]['day17']= $this->getday( $userremainday[$k]['startdate'], 17);
            $userremainday[$k]['day18']= $this->getday( $userremainday[$k]['startdate'], 18);
            $userremainday[$k]['day19']= $this->getday( $userremainday[$k]['startdate'], 19);
            $userremainday[$k]['day20']= $this->getday( $userremainday[$k]['startdate'], 20);
            $userremainday[$k]['day21']= $this->getday( $userremainday[$k]['startdate'], 21);
            $userremainday[$k]['day22']= $this->getday( $userremainday[$k]['startdate'], 22);  
            $userremainday[$k]['day23']= $this->getday( $userremainday[$k]['startdate'], 23);
            $userremainday[$k]['day24']= $this->getday( $userremainday[$k]['startdate'], 24);
            $userremainday[$k]['day25']= $this->getday( $userremainday[$k]['startdate'], 25);
            $userremainday[$k]['day26']= $this->getday( $userremainday[$k]['startdate'], 26);
            $userremainday[$k]['day27']= $this->getday( $userremainday[$k]['startdate'], 27);
            $userremainday[$k]['day28']= $this->getday( $userremainday[$k]['startdate'], 28);
            $userremainday[$k]['day29']= $this->getday( $userremainday[$k]['startdate'], 29);
            $userremainday[$k]['day30']= $this->getday( $userremainday[$k]['startdate'], 30);
            $userremainday[$k]['day60']= $this->getday( $userremainday[$k]['startdate'], 60);
            $userremainday[$k]['day90']= $this->getday( $userremainday[$k]['startdate'], 90);
        }
        return $userremainday;

    }


}
