<?php
/*
*
* 实时数据统计模型
*
*/
class Realtimedatamodel extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }



    function getrealtimedata($fromTime,$toTime)
    {

		$data =  $this-> getrealtimedatalist($fromTime,$toTime); 	

		$realtimedata = array();

		foreach ($data as $k => $v) 
		{
			$realtimedata[$k]['server']	=	$v['sname'];
			$realtimedata[$k]['rxzsb']	=	$v['rxzsb'];
			$realtimedata[$k]['rdlyh']	=	$v['rdlyh'];
			$realtimedata[$k]['rxzyh']	=	$v['rxzyh'];
			$realtimedata[$k]['ffyh']	=	$v['ffyh'];
			$realtimedata[$k]['xzffyh']	=	$v['xzffyh'];
			$realtimedata[$k]['ffcs']	=	$v['ffcs'];
			$realtimedata[$k]['ffje']	=	$v['ffje'];
		}

		return $realtimedata;
    }







    //返回实时数据
    function getrealtimedatalist($fromtime,$totime)
    {

    	$dwdb = $this->load->database('dw',true);

    	$sql  = "
					SELECT
					date(ddd.date) date,
					se.sid_sk,
					se.sname,
					IFNULL(USER .rxzsb, 0) AS rxzsb,
					IFNULL(USER .rdlyh, 0) AS rdlyh,
					IFNULL(USER .rxzyh, 0) AS rxzyh,
					IFNULL(recharge.ffyh, 0) AS ffyh,
					IFNULL(recharge.xzffyh, 0) AS xzffyh,
					IFNULL(recharge.ffcs, 0) AS ffcs,
					IFNULL(recharge.ffje, 0) AS ffje
					FROM
					"  . $dwdb->dbprefix('server_info').  "  se
					LEFT JOIN (
					SELECT
					COUNT(DISTINCT lo.user_id) AS rdlyh,
					ff.rxzyh AS rxzyh,
					ff.rxzsb as rxzsb,
					lo.sid,
					lo.date_sk
					FROM
					"  . $dwdb->dbprefix('login_info').  "  lo
					LEFT JOIN (
					SELECT
					COUNT(DISTINCT ded.device_id) AS rxzsb,
					COUNT(DISTINCT reu.user_id) AS rxzyh,
					COUNT(DISTINCT ro.role_id) AS rxzjs,
					ro.sid
					FROM
					"  . $dwdb->dbprefix('role_info').  "  ro
					LEFT JOIN (
					SELECT
					re.user_id,
					re.date_sk,
					re.device_id
					FROM
					"  . $dwdb->dbprefix('register_info').  "  re
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
					"  . $dwdb->dbprefix('device_info').  " de
					GROUP BY
					de.date_sk,
					de.device_id
					) ded ON ded.device_id = ro.device_id
					AND ded.device_id = reu.device_id
					AND ded.date_sk = ro.date_sk
					WHERE
					ro.create_role_time BETWEEN '$fromtime'  and '$totime'
					GROUP BY
					ro.sid
					) ff ON ff.sid = lo.sid
					WHERE
					lo.create_login_ts BETWEEN '$fromtime'  and '$totime'
					GROUP BY
					lo.sid
					) USER ON USER.sid = se.sid_sk
					LEFT JOIN (
					SELECT
					COUNT(DISTINCT od.user_id) ffyh,
					COUNT(DISTINCT id) ffcs,
					sum(od.money) ffje,
					od.date_sk,
					xzff.xzffyh,
					od.sid
					FROM
					"  . $dwdb->dbprefix('order_info').  " od 
					LEFT JOIN (
					SELECT
					COUNT(id) AS xzffyh,
					date_sk,
					sid
					FROM
					(
					SELECT
					min(id) AS id,
					count(user_id) AS count,
					device_id,
					date_sk,
					sid
					FROM
					"  . $dwdb->dbprefix('order_info').  " 
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
					date_sk,
					sid
					) xzff ON xzff.date_sk = od.date_sk AND xzff.sid  = od.sid
					WHERE
					od.rechargedate BETWEEN '$fromtime'  and '$totime'
					AND od.app_id = 1
					GROUP BY
					od.date_sk,
					sid
					) recharge ON  recharge.sid = se.sid_sk
					LEFT JOIN
					(
					SELECT
					date(dd.datevalue) date,
					dd.date_sk
					FROM
					"  . $dwdb->dbprefix('dim_date').  "  dd
					WHERE
					dd.datevalue >= '$fromtime'
					AND dd.datevalue < '$totime'
					)ddd on ddd.date_sk  = USER.date_sk
					
    				"; 
    				$query = $dwdb->query($sql);
			       // echo  $dwdb->last_query();
			        $info = $query->result_array();  //返回对象类型
			        return $info;


    }





	//详细表
    function  getrealtimedetaileddata($fromTime,$toTime)
    {


    	$data =  $this-> getrealtimedatalist($fromTime,$toTime); 	


		$realtimedata = array();
		foreach ($data as $k => $v) 
		{
			$realtimedata[$k]['server']	=	$v['sname'];
			$realtimedata[$k]['rxzsb']	=	$v['rxzsb'];
			$realtimedata[$k]['zrxzsb']	=	$this->gettotalinfo($v['sid_sk'])['zrxzsb'];
			$realtimedata[$k]['rdlyh']	=	$v['rdlyh'];
			$realtimedata[$k]['rxzyh']	=	$v['rxzyh'];
			$realtimedata[$k]['zrxzyh']	=	$this->gettotalinfo($v['sid_sk'])['zrxzyh'];
			$realtimedata[$k]['ffyh']	=	$v['ffyh'];
			$realtimedata[$k]['zffyh']	=	$this->gettotalinfo($v['sid_sk'])['zffyh'];
			$realtimedata[$k]['xzffyh']	=	$v['xzffyh'];
			$realtimedata[$k]['ffcs']	=	$v['ffcs'];
			$realtimedata[$k]['ffje']	=	$v['ffje'];
			$realtimedata[$k]['zffje']	=	$this->gettotalinfo($v['sid_sk'])['zffje'];
		}
		return $realtimedata;

    }






    //返回总计数据  
    function gettotalrealtimedata($fromtime,$totime)
    {


	$dwdb = $this->load->database('dw',true);
	$sql =  "
			SELECT
			date(dd.datevalue) date,
			IFNULL(USER .rxzsb, 0) AS rxzsb,
			IFNULL(USER .rdlyh, 0) AS rdlyh,
			IFNULL(USER .rxzyh, 0) AS rxzyh,
			IFNULL(recharge.ffyh, 0) AS ffyh,
			IFNULL(recharge.xzffyh, 0) AS xzffyh,
			IFNULL(recharge.ffcs, 0) AS ffcs,
			IFNULL(recharge.ffje, 0) AS ffje
			FROM
			"  . $dwdb->dbprefix('dim_date').  "  dd
			LEFT JOIN (
			SELECT
			COUNT(DISTINCT lo.user_id) AS rdlyh,
			ff.rxzyh AS rxzyh,
			ff.rxzsb AS rxzsb,
			lo.sid,
			lo.date_sk
			FROM
			"  . $dwdb->dbprefix('login_info').  "   lo
			LEFT JOIN (
			SELECT
			COUNT(DISTINCT ded.device_id) AS rxzsb,
			COUNT(DISTINCT reu.user_id) AS rxzyh,
			COUNT(DISTINCT ro.role_id) AS rxzjs,
			ro.date_sk
			FROM
			"  . $dwdb->dbprefix('role_info').  "   ro
			LEFT JOIN (
			SELECT
			re.user_id,
			re.date_sk,
			re.device_id
			FROM
			"  . $dwdb->dbprefix('register_info').  "   re
			GROUP BY
			date_sk,
			user_id
			) reu ON reu.user_id = ro.user_id
			AND reu.device_id = ro.device_id
			AND reu.date_sk = ro.date_sk
			LEFT JOIN (
			SELECT
			de.device_id,
			de.date_sk
			FROM
			"  . $dwdb->dbprefix('device_info').  "   de
			GROUP BY
			de.date_sk,
			de.device_id
			) ded ON ded.device_id = ro.device_id
			AND ded.device_id = reu.device_id
			AND ded.date_sk = ro.date_sk
			WHERE
			ro.create_role_time BETWEEN  '$fromtime'  and '$totime'
			) ff ON ff.date_sk = lo.date_sk
			WHERE
			lo.create_login_ts BETWEEN  '$fromtime'  and '$totime'
			) USER ON USER .date_sk = dd.date_sk
			LEFT JOIN (
			SELECT
			COUNT(DISTINCT od.user_id) ffyh,
			COUNT(DISTINCT id) ffcs,
			sum(od.money) ffje,
			od.date_sk,
			xzff.xzffyh,
			od.sid
			FROM
			"  . $dwdb->dbprefix('order_info').  "   od
			LEFT JOIN (
			SELECT
			COUNT(id) AS xzffyh,
			date_sk,
			sid
			FROM
			(
			SELECT
			min(id) AS id,
			count(user_id) AS count,
			device_id,
			date_sk,
			sid
			FROM
			"  . $dwdb->dbprefix('order_info').  "
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
			date_sk,
			sid
			) xzff ON xzff.date_sk = od.date_sk
			AND xzff.sid = od.sid
			WHERE
			od.rechargedate BETWEEN  '$fromtime'  and '$totime'
			AND od.app_id = 1
			GROUP BY
			od.date_sk
			) recharge ON recharge.date_sk = dd.date_sk
			WHERE
			dd.datevalue  >= '$fromtime'   and dd.datevalue  < '$totime' ";
			$query = $dwdb->query($sql);
			//echo  $dwdb->last_query();
			$info = $query->row_array();  //返回对象类型
			return $info;

    }






    //返回 总新增数据  分服
    function  gettotalinfo($sid)
    {
    	$dwdb = $this->load->database('dw',true);
    	$sql  = "
				SELECT
				COUNT(DISTINCT de.device_id) AS zrxzsb,
				COUNT(DISTINCT re.user_id) AS zrxzyh,
				COUNT(DISTINCT ro.role_id) AS zrxzjs,
				COUNT(DISTINCT od.user_id) zffyh,
				IFNULL(SUM(od.money),0)	 AS zffje
				FROM
				"  . $dwdb->dbprefix('role_info').  " ro
				LEFT JOIN 
				"  . $dwdb->dbprefix('register_info').  "  re
				ON ro.user_id = re.user_id
				LEFT JOIN 
				"  . $dwdb->dbprefix('device_info').  "  de
				ON ro.device_id = de.device_id  
				LEFT JOIN 
				"  . $dwdb->dbprefix('order_info').  "	od
				ON ro.sid = od.sid  AND ro.user_id = od.user_id 
				WHERE
				ro.sid = '$sid';
    			";
    		$query = $dwdb->query($sql);
			//echo  $dwdb->last_query();
			$info = $query->row_array();  //返回对象类型
			return $info;
    }



    //返回 总新增数据  不分服
    function  gettotalinfodata()
    {
    	$dwdb = $this->load->database('dw',true);
    	$sql  = "
				SELECT
				COUNT(DISTINCT de.device_id) AS zrxzsb,
				COUNT(DISTINCT re.user_id) AS zrxzyh,
				COUNT(DISTINCT ro.role_id) AS zrxzjs,
				COUNT(DISTINCT od.user_id) zffyh,
				IFNULL(SUM(od.money),0)	 AS zffje
				FROM
				"  . $dwdb->dbprefix('role_info').  " ro
				LEFT JOIN 
				"  . $dwdb->dbprefix('register_info').  "  re
				ON ro.user_id = re.user_id  
				LEFT JOIN 
				"  . $dwdb->dbprefix('device_info').  "  de
				ON ro.device_id = de.device_id
				LEFT JOIN 
				"  . $dwdb->dbprefix('order_info').  "	od
				ON ro.sid = od.sid
				AND ro.user_id = od.user_id 
    			";
    		$query = $dwdb->query($sql);
			//echo  $dwdb->last_query();
			$info = $query->row_array();  //返回对象类型
			return $info;
    }



}