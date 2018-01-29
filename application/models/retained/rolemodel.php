<?php
/**
 *  角色留存率
 */
class Rolemodel extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }



    //查询用户的 每日留存效率
    function getroledataByDay($from, $to)
    {
        $total  = $this->gettotalcount($from, $to);
        $userremainday = array();
        foreach ($total as $k => $v) 
        {
            $userremainday[$k]['startdate'] = $v['date'];  
            $userremainday[$k]['usercount'] = $v['did'];  
            $userremainday[$k]['day1']      = $this-> getday( $userremainday[$k]['startdate'],1);
            $userremainday[$k]['day2']      = $this-> getday( $userremainday[$k]['startdate'],2);
            $userremainday[$k]['day3']      = $this-> getday( $userremainday[$k]['startdate'],3);
            $userremainday[$k]['day4']      = $this-> getday( $userremainday[$k]['startdate'],4);
            $userremainday[$k]['day5']      = $this-> getday( $userremainday[$k]['startdate'],5);
            $userremainday[$k]['day6']      = $this-> getday( $userremainday[$k]['startdate'],6);
            $userremainday[$k]['day7']      = $this-> getday( $userremainday[$k]['startdate'],7);
            $userremainday[$k]['day15']     = $this-> getday( $userremainday[$k]['startdate'],15);
        }
        return  $userremainday;
    }



    function  gettotalcount($from, $to)
    {

        $dwdb = $this->load->database('dw', true);
        $to   = date('Y-m-d',strtotime("+1 day",strtotime($to)));   //+ 1 day
        $sql = "SELECT date(datevalue) date, 
        ifnull( ff.did,0) did 
        from " . $dwdb -> dbprefix('dim_date')  . "  dd 
        left JOIN(
        select COUNT(ro.role_id) as did , ro.date_sk from   " . $dwdb -> dbprefix('role_info')  . "  ro 
        WHERE  ro.create_role_time  >=   '$from'   and  ro.create_role_time <  ' $to'   
        GROUP BY ro.date_sk
        )ff on   ff.date_sk = dd.date_sk 
        where  dd.datevalue  >=  '$from'   AND  dd.datevalue < ' $to'  
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
        $from2 = date('Y-m-d',strtotime("+".$day." day",strtotime($from)));   //+ 1 day 
        $to2   = date('Y-m-d',strtotime("+1 day",strtotime($from2)));   //+ 1 day    
        $dwdb  = $this->load->database('dw', true);
        $sql   = 
                "SELECT 
                COUNT(DISTINCT lo.rus_id) as did 
                From ". $dwdb -> dbprefix('login_info')  ."  lo  
                JOIN  ". $dwdb -> dbprefix('role_info')  ."  ro 
                ON ro.rus_id = lo.rus_id 
                WHERE lo.create_login_ts  BETWEEN '$from2' and  '$to2' AND
                ro.create_role_time  BETWEEN '$from1' and  '$to1' " ;
        $query = $dwdb -> query($sql);
        // echo $dwdb ->  last_query();
        $count_did = $query-> row() -> did; 
        return $count_did;

    }



}
