<?php
/*
*
* 充值数据排名统计模型
*
*/
class Toprankingmodel extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }



    function getToprankingdata($fromTime,$toTime,$sid)
    {

    	$realtimedata = array();
    	if ($sid  ==  'all') 
    	{

    		$data =  $this-> getToprankinglist($fromTime,$toTime); 	

			foreach ($data as $k => $v) 
			{
				$realtimedata[$k]['num']	= 	$k+1;
				$realtimedata[$k]['server']	=	$v['sid'];
				$realtimedata[$k]['imp']	=	$v['imp'];
				$realtimedata[$k]['UID']	=	$v['user_id'];
				$realtimedata[$k]['RID']	=	$v['role_id'];
				$realtimedata[$k]['ljcz']	=	$v['ljcz'];
				$realtimedata[$k]['regtime']	=	$v['regtime'];
				$realtimedata[$k]['roletime']	=	$v['roletime'];
				$realtimedata[$k]['lastlogin']	=	$this->getlastlogin($v['role_id']);
			}

			return $realtimedata;

    	}
    	else
    	{
    		//按区服分
    		$data =  $this-> getToprankingserverlist($fromTime,$toTime,$sid);
    		foreach ($data as $k => $v) 
			{
				$realtimedata[$k]['num']	= 	$k+1;
				$realtimedata[$k]['server']	=	$v['sid'];
				$realtimedata[$k]['imp']	=	$v['imp'];
				$realtimedata[$k]['UID']	=	$v['user_id'];
				$realtimedata[$k]['RID']	=	$v['role_id'];
				$realtimedata[$k]['ljcz']	=	$v['ljcz'];
				$realtimedata[$k]['regtime']	=	$v['regtime'];
				$realtimedata[$k]['roletime']	=	$v['roletime'];
				$realtimedata[$k]['lastlogin']	=	$this->getlastlogin($v['role_id']);
			}
			return $realtimedata;
    	}

		

	



    }







    //全服数据查询
    function getToprankinglist($fromtime,$totime)
    {

    	$dwdb = $this->load->database('dw',true);

    	$sql  = "
				SELECT
				od.user_id,
				od.role_id,
				od.sid,
				od.imp,
				SUM(od.money) AS ljcz,
				re.create_register_time as regtime,
				ro.create_role_time as roletime
				FROM
				". $dwdb -> dbprefix('order_info')  ." od
				LEFT JOIN 
				". $dwdb -> dbprefix('register_info')  ." re
				ON od.user_id = re.user_id
				LEFT JOIN   
				". $dwdb -> dbprefix('role_info ')  ." ro
				ON od.role_id = ro.role_id
				AND re.user_id = ro.user_id
				WHERE
				od.rechargedate BETWEEN '$fromtime' AND '$totime'
				AND  od.app_id = 1
				GROUP BY
				sid,
				user_id,
				role_id
				ORDER BY ljcz desc
    			";
   		$query = $dwdb->query($sql);
   		//echo $dwdb ->  last_query();
   		$data = $query -> result_array();
   		return $data;
    }
					


    //按区服数据查询
    function getToprankingserverlist($fromtime,$totime,$sid)
    {

    	$dwdb = $this->load->database('dw',true);
    	$sql  = "
				SELECT
				od.user_id,
				od.role_id,
				od.sid,
				od.imp,
				SUM(od.money) AS ljcz,
				re.create_register_time as regtime,
				ro.create_role_time as roletime
				FROM
				". $dwdb -> dbprefix('order_info')  ." od
				LEFT JOIN 
				". $dwdb -> dbprefix('register_info')  ." re
				ON od.user_id = re.user_id
				LEFT JOIN   
				". $dwdb -> dbprefix('role_info ')  ." ro
				ON od.role_id = ro.role_id
				AND re.user_id = ro.user_id
				WHERE
				od.rechargedate BETWEEN '$fromtime' AND '$totime'  AND  od.sid = '$sid'
				AND  od.app_id = 1
				GROUP BY
				sid,
				user_id,
				role_id
				ORDER BY ljcz desc
    			";
   		$query = $dwdb->query($sql);
   		//echo $dwdb ->  last_query();
   		$data = $query -> result_array();
   		return $data;
    }





    //获取角色最后一次登录时间
    function getlastlogin($rid)
    {
    	$dwdb = $this->load->database('dw',true);
    	$sql = "SELECT
				max(create_login_ts)  as lastlogin
				FROM
				". $dwdb -> dbprefix('login_info')  ." 
				WHERE  role_id = '$rid'
				";
    	$result =  $dwdb -> query($sql);

    	return  $result->row()->lastlogin;


    }

}