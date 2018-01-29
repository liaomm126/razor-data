<?php
/**
 *  用户留存率
 */
class Accoutmodel extends CI_Model
{

    function __construct()
    {
      parent::__construct();
    }


    //每日留存效率
    function getaccoutdataByDay($from, $to)
    {

        $total  = $this->gettotalcount($from, $to);     
        $userremainday = array();
        foreach ($total as $k => $v) 
        {
            $userremainday[$k]['startdate'] = $v['date'];  
            $userremainday[$k]['usercount'] = $v['did'];  
            $userremainday[$k]['day1']      = $this-> getday( $userremainday[$k]['startdate'] , 1);
            $userremainday[$k]['day2']      = $this-> getday( $userremainday[$k]['startdate'] , 2);
            $userremainday[$k]['day3']      = $this-> getday( $userremainday[$k]['startdate'] , 3);
            $userremainday[$k]['day4']      = $this-> getday( $userremainday[$k]['startdate'] , 4);
            $userremainday[$k]['day5']      = $this-> getday( $userremainday[$k]['startdate'] , 5);
            $userremainday[$k]['day6']      = $this-> getday( $userremainday[$k]['startdate'] , 6);
            $userremainday[$k]['day7']      = $this-> getday( $userremainday[$k]['startdate'] , 7);
            $userremainday[$k]['day15']     = $this-> getday( $userremainday[$k]['startdate'] , 15);
        }
        return  $userremainday ;
    }


    //统计用户留存总数
    function  gettotalcount($from, $to)
    {

        $to   =   date('Y-m-d',strtotime("+1 day",strtotime($to))) ;
        $dwdb =   $this->load->database('dw', true);             
        $sql  =  
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
                GROUP BY dd.datevalue" ;
        $query = $dwdb -> query($sql);
        $totalinfo = $query-> result_array();  //返回数组类型
        //echo $dwdb ->  last_query();
        return $totalinfo;

    }


    function getday($from,$day)  
    {

        $from1 = $from;
        $to1   = date('Y-m-d',strtotime("+1 day",strtotime($from1))) ;
        $from2 = date('Y-m-d',strtotime("+".$day." day",strtotime($from)));  
        $to2   = date('Y-m-d',strtotime("+1 day",strtotime($from2))); 
        $dwdb  = $this->load->database('dw', true);
        $sql   =  
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
        $query = $dwdb -> query($sql);
        //echo $dwdb ->  last_query();
        $count_did = $query-> row() -> did; 
        return $count_did;

    }


}
