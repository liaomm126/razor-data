<?php
/**
 *LTV数据
 */
class Lifetimevaluemodel extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }



    //查询用户的 每日留存效率
    function getlifetimevalue($from, $to,$opdetailed)
    {
        $total  = $this->gettotalcount($from, $to);
        $userremainday = array();
        //判断是概略表 还是详细表
        if ($opdetailed == 'briefly') 
        {
			foreach ($total as $k => $v) 
			{
				$userremainday[$k]['startdate'] = $v['date'];  
				$userremainday[$k]['usercount'] = $v['xzyh'];  
				$userremainday[$k]['day1']      = $this-> getdayffje( $userremainday[$k]['startdate'],0);
				$userremainday[$k]['day2']      = $this-> getdayffje( $userremainday[$k]['startdate'],1);
				$userremainday[$k]['day3']      = $this-> getdayffje( $userremainday[$k]['startdate'],2);
				$userremainday[$k]['day4']      = $this-> getdayffje( $userremainday[$k]['startdate'],3);
				$userremainday[$k]['day5']      = $this-> getdayffje( $userremainday[$k]['startdate'],4);
				$userremainday[$k]['day6']      = $this-> getdayffje( $userremainday[$k]['startdate'],5);
				$userremainday[$k]['day7']      = $this-> getdayffje( $userremainday[$k]['startdate'],6);
				$userremainday[$k]['day15']     = $this-> getdayffje( $userremainday[$k]['startdate'],14);
				$userremainday[$k]['day30']     = $this-> getdayffje( $userremainday[$k]['startdate'],29);
				$userremainday[$k]['day60']     = $this-> getdayffje( $userremainday[$k]['startdate'],59);
				$userremainday[$k]['day90']     = $this-> getdayffje( $userremainday[$k]['startdate'],89);
			}
			return  $userremainday;
        }
        else
        {
        	foreach ($total as $k => $v) 
			{
				$userremainday[$k]['startdate'] = $v['date'];  
				$userremainday[$k]['usercount'] = $v['xzyh'];  
				$userremainday[$k]['day1']      = $this-> getdayffje( $userremainday[$k]['startdate'],0);
				$userremainday[$k]['day2']      = $this-> getdayffje( $userremainday[$k]['startdate'],1);
				$userremainday[$k]['day3']      = $this-> getdayffje( $userremainday[$k]['startdate'],2);
				$userremainday[$k]['day4']      = $this-> getdayffje( $userremainday[$k]['startdate'],3);
				$userremainday[$k]['day5']      = $this-> getdayffje( $userremainday[$k]['startdate'],4);
				$userremainday[$k]['day6']      = $this-> getdayffje( $userremainday[$k]['startdate'],5);
				$userremainday[$k]['day7']      = $this-> getdayffje( $userremainday[$k]['startdate'],6);
				$userremainday[$k]['day8']      = $this-> getdayffje( $userremainday[$k]['startdate'],7);
				$userremainday[$k]['day9']      = $this-> getdayffje( $userremainday[$k]['startdate'],8);
				$userremainday[$k]['day10']      = $this-> getdayffje( $userremainday[$k]['startdate'],9);
				$userremainday[$k]['day11']      = $this-> getdayffje( $userremainday[$k]['startdate'],10);
				$userremainday[$k]['day12']      = $this-> getdayffje( $userremainday[$k]['startdate'],11);
				$userremainday[$k]['day13']      = $this-> getdayffje( $userremainday[$k]['startdate'],12);
				$userremainday[$k]['day14']      = $this-> getdayffje( $userremainday[$k]['startdate'],13);
				$userremainday[$k]['day15']      = $this-> getdayffje( $userremainday[$k]['startdate'],14);
				$userremainday[$k]['day16']      = $this-> getdayffje( $userremainday[$k]['startdate'],15);
				$userremainday[$k]['day17']      = $this-> getdayffje( $userremainday[$k]['startdate'],16);
				$userremainday[$k]['day18']      = $this-> getdayffje( $userremainday[$k]['startdate'],17);
				$userremainday[$k]['day19']      = $this-> getdayffje( $userremainday[$k]['startdate'],18);
				$userremainday[$k]['day20']      = $this-> getdayffje( $userremainday[$k]['startdate'],19);
				$userremainday[$k]['day21']      = $this-> getdayffje( $userremainday[$k]['startdate'],20);
				$userremainday[$k]['day22']      = $this-> getdayffje( $userremainday[$k]['startdate'],21);
				$userremainday[$k]['day23']      = $this-> getdayffje( $userremainday[$k]['startdate'],22);
				$userremainday[$k]['day24']      = $this-> getdayffje( $userremainday[$k]['startdate'],23);
				$userremainday[$k]['day25']      = $this-> getdayffje( $userremainday[$k]['startdate'],24);
				$userremainday[$k]['day26']      = $this-> getdayffje( $userremainday[$k]['startdate'],25);
				$userremainday[$k]['day27']      = $this-> getdayffje( $userremainday[$k]['startdate'],26);
				$userremainday[$k]['day28']      = $this-> getdayffje( $userremainday[$k]['startdate'],27);
				$userremainday[$k]['day29']      = $this-> getdayffje( $userremainday[$k]['startdate'],28);
				$userremainday[$k]['day30']     = $this-> getdayffje( $userremainday[$k]['startdate'],29);
				$userremainday[$k]['day60']     = $this-> getdayffje( $userremainday[$k]['startdate'],59);
				$userremainday[$k]['day90']     = $this-> getdayffje( $userremainday[$k]['startdate'],89);
			}
			return  $userremainday;
        }




    }



    function  gettotalcount($from, $to)
    {

        $dwdb = $this->load->database('dw', true);
        $to   = date('Y-m-d',strtotime("+1 day",strtotime($to)));   //+ 1 day
        $sql = "
				SELECT
				date(datevalue) date,
				ifnull(ff.xzyh, 0) xzyh
				FROM
				" . $dwdb -> dbprefix('dim_date')  . "  dd 
				LEFT JOIN (
				SELECT
				COUNT(DISTINCT ro.role_id) AS xzyh,
				ro.date_sk
				FROM
				" . $dwdb -> dbprefix('role_info')  . "  ro 
				GROUP BY
				ro.date_sk
				) ff ON ff.date_sk = dd.date_sk
				WHERE
				dd.datevalue >= '$from'
				AND dd.datevalue <  '$to' ";
        $query = $dwdb -> query($sql);
        $totalinfo = $query-> result_array();  
        //echo $dwdb ->  last_query();
        return $totalinfo;

    }


    function getdayffje($from,$day)   
    {

        $from1 = $from;
        $to1   = date('Y-m-d',strtotime("+1 day",strtotime($from1))) ;
        $from2 = date('Y-m-d',strtotime("+".$day." day",strtotime($from)));   //+ 1 day 
        $to2   = date('Y-m-d',strtotime("+1 day",strtotime($from2)));   //+ 1 day    
        $dwdb  = $this->load->database('dw', true);
        $sql   = "SELECT
					IFNULL(SUM( money ),0)	as ffje
					FROM
					". $dwdb -> dbprefix('order_info')  ." od
					LEFT JOIN (
					SELECT
					ro.role_id,
					date_sk
					FROM
					t_role_info ro
					WHERE
					ro.create_role_time BETWEEN '$from1'
					AND '$to1' 
					GROUP BY
					role_id,
					date_sk
					) ff ON ff.role_id = od.role_id
					WHERE
					rechargedate BETWEEN '$from2'
					AND '$to2'
					AND ff.role_id  = od.role_id
                 ";


        $query = $dwdb -> query($sql);
        //echo $dwdb ->  last_query();
        $ffje = $query-> row() -> ffje; 
        return $ffje;

    }



}
