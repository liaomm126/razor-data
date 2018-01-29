<?php
/**
* Grademodel
*
* 分日数据统计
*
*/
class Datedatamodel extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }


    function getdatedatalist($fromtime,$totime,$sid)
    {

    	if ($sid == 'all') 
    	{

		$getdaydata = $this-> getdatedata($fromtime,$totime);      // 新增数据统计
        $data = array();
        foreach ($getdaydata as $k => $v) 
        {
        	
        	$data[$k]['date']	=	$v['date'];		//日期
        	$data[$k]['sname']	=	'合计';		//区服
        	$data[$k]['rdlyh']	=	$v['rdlyh'];	//日登陆用户数
        	$data[$k]['rxzyh']	=	$v['rxzyh'];	//日新增用户数
        	$data[$k]['rhyyh']	=	$v['rdlyh'] - $v['rxzyh'] ? $v['rdlyh'] - $v['rxzyh'] : 0;	//日活跃用户
        	//日均使用次数
        	$data[$k]['rjxycs']	=	$v['dlzs']; 
        	$data[$k]['ACU']	=	$v['acu'];	//平均同时在线(ACU)
        	$data[$k]['PCU']	=	$v['pcu'];	//最高同时在线(PCU)
        	//日均在线时长(分)
        	$data[$k]['rjzxsc']	=	number_format( $v['duration'] / 3600 / 1000 , 1) ? number_format( $v['duration'] / 3600 / 1000 , 1) : 0;
        	$data[$k]['ffyh']	=	$v['ffyh']; 		//付费用户
        	$data[$k]['xzffyh']	=	$v['xzffyh'];		//新增付费用户
        	$data[$k]['dlffl']	=	$v['rdlyh'] ? number_format($v['ffyh'] / $v['rdlyh'] * 100,1).'%' : 0;	//登录付费率
        	$data[$k]['zcffl']	=	$v['rxzyh'] ? number_format($v['xzffyh'] / $v['rxzyh'] * 100,1).'%' : 0;	//注册付费率
        	$data[$k]['ffcs']	=	$v['ffcs'];	//付费次数
        	$data[$k]['ffje']	=	$v['ffje'];	//付费金额
        	//付费用户ARPU
        	$data[$k]['ffARPU']	=	$v['ffyh'] ? sprintf('%.2f',$v['ffje'] / $v['ffyh']) : 0 ;
        	//登陆用户ARPU
        	$data[$k]['dlARPU']	=	$v['rdlyh'] ? sprintf('%.2f',$v['ffje'] / $v['rdlyh']) : 0 ;
        	//角色存留率  getday方法 第一个参数 传日期、 第二个参数 传天数  
        	 $data[$k]['day1']	=	$this->getday($v['date'],1); 
        	 $data[$k]['day3']	=	$this->getday($v['date'],3);
        	 $data[$k]['day7']	=	$this->getday($v['date'],7);
        }

        return $data;

    	}
    	else
    	{

		$getdaydata = $this-> getdateserverdata($fromtime,$totime,$sid);      // 按服区分新增数据统计
        $data = array();
        foreach ($getdaydata as $k => $v) 
        {
        	
        	$data[$k]['date']	=	$v['date'];		//日期
        	$data[$k]['sname']	=	'合计';		//区服
        	$data[$k]['rdlyh']	=	$v['rdlyh'];	//日登陆用户数
        	$data[$k]['rxzyh']	=	$v['rxzyh'];	//日新增用户数
        	$data[$k]['rhyyh']	=	$v['rdlyh'] - $v['rxzyh'] ? $v['rdlyh'] - $v['rxzyh'] : 0;	//日活跃用户
        	//日均使用次数
        	$data[$k]['rjxycs']	=	$v['dlzs']; 
        	$data[$k]['ACU']	=	$v['acu'];	//平均同时在线(ACU)
        	$data[$k]['PCU']	=	$v['pcu'];	//最高同时在线(PCU)
        	//日均在线时长(分)
        	$data[$k]['rjzxsc']	=	number_format( $v['duration'] / 3600 / 1000 , 1) ? number_format( $v['duration'] / 3600 / 1000 , 1) : 0;
        	$data[$k]['ffyh']	=	$v['ffyh']; 		//付费用户
        	$data[$k]['xzffyh']	=	$v['xzffyh'];		//新增付费用户
        	$data[$k]['dlffl']	=	$v['rdlyh'] ? number_format($v['ffyh'] / $v['rdlyh'] * 100,1).'%' : 0;	//登录付费率
        	$data[$k]['zcffl']	=	$v['rxzyh'] ? number_format($v['xzffyh'] / $v['rxzyh'] * 100,1).'%' : 0;	//注册付费率
        	$data[$k]['ffcs']	=	$v['ffcs'];	//付费次数
        	$data[$k]['ffje']	=	$v['ffje'];	//付费金额
        	//付费用户ARPU
        	$data[$k]['ffARPU']	=	$v['ffyh'] ? sprintf('%.2f',$v['ffje'] / $v['ffyh']) : 0 ;
        	//登陆用户ARPU
        	$data[$k]['dlARPU']	=	$v['rdlyh'] ? sprintf('%.2f',$v['ffje'] / $v['rdlyh']) : 0 ;
        	//角色存留率  getday方法 第一个参数 传日期、 第二个参数 传天数 、第三个传区组
        	 $data[$k]['day1']	=	$this->getdayserver($v['date'],1,$sid); 
        	 $data[$k]['day3']	=	$this->getdayserver($v['date'],3,$sid);
        	 $data[$k]['day7']	=	$this->getdayserver($v['date'],7,$sid);
        }

        return $data;			
    	}
    	

 


     }




    //获取角色留存率 
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
                ro.create_role_time  BETWEEN '$from1' and  '$to1' ";
        $query = $dwdb -> query($sql);
        //echo $dwdb ->  last_query();
        $count_did = $query-> row() -> did; 
        return $count_did;

    }





    //获取信息
    function  getdatedata($fromtime,$totime)
    {
        $dwdb = $this->load->database('dw', true);
        $sql  ="
				SELECT DISTINCT
				date(datevalue) date,
				IFNULL(USER .rdlyh, 0) AS rdlyh,
				IFNULL(USER .rxzyh, 0) AS rxzyh,
				IFNULL(ondata.dlzs, 0) AS dlzs,
				IFNULL(ondata.dljs, 0) AS dljs,
				IFNULL(ondata.duration, 0) AS duration,
				IFNULL(ondata.acu, 0) AS acu,
				IFNULL(ondata.pcu, 0) AS pcu,
				IFNULL(recharge.ffyh, 0) AS ffyh,
				IFNULL(recharge.xzffyh, 0) AS xzffyh,
				IFNULL(recharge.ffcs, 0) AS ffcs,
				IFNULL(recharge.ffje, 0) AS ffje
				FROM
				".  $dwdb->dbprefix('dim_date')  ." dd
				LEFT JOIN (
				SELECT
				COUNT(DISTINCT lo.user_id) AS rdlyh,
				IFNULL(ff.rxzyh, 0) AS rxzyh,
				lo.date_sk
				FROM
				".  $dwdb->dbprefix('login_info')  ." lo
				LEFT JOIN (
				SELECT
				COUNT(DISTINCT ded.device_id) AS rxzsb,
				COUNT(DISTINCT reu.user_id) AS rxzyh,
				COUNT(DISTINCT ro.role_id) AS rxzjs,
				ro.date_sk
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
				".  $dwdb->dbprefix('device_info')  ."	de
				GROUP BY
				de.date_sk,
				de.device_id
				) ded ON ded.device_id = ro.device_id
				AND ded.device_id = reu.device_id
				AND ded.date_sk = ro.date_sk
				WHERE
				ro.create_role_time BETWEEN '$fromtime' and '$totime'
				GROUP BY
				ro.date_sk
				) ff ON ff.date_sk = lo.date_sk
				AND ff.date_sk = lo.date_sk
				WHERE
				lo.create_login_ts BETWEEN '$fromtime' and '$totime'
				GROUP BY
				lo.date_sk
				) USER ON USER .date_sk = dd.date_sk
				LEFT JOIN (
				SELECT
				COUNT(DISTINCT lo.rus_id) dljs,
				COUNT(lo.id) dlzs,
				IFNULL(ff.duration, 0) duration,
				lo.date_sk,
				lo.sid,
				IFNULL(fff.pcu, 0) pcu,
				IFNULL(fff.acu, 0) acu
				FROM
				".  $dwdb->dbprefix('login_info')  ." lo
				LEFT JOIN (
				SELECT
				SUM(ud.duration) AS duration,
				ud.date_sk,
				ud.sid
				FROM
				".  $dwdb->dbprefix('user_distribution')  ." ud
				WHERE
				start_time BETWEEN '$fromtime' and '$totime'
				GROUP BY
				ud.date_sk
				) ff ON ff.date_sk = lo.date_sk
				LEFT JOIN (
				SELECT
				max(hk) AS pcu,
				ceil(AVG(hk)) AS acu,
				date_sk
				FROM
				(
				SELECT
				COUNT(hour_sk) AS hk,
				date_sk
				FROM
				".  $dwdb->dbprefix('user_distribution')  ."
				WHERE
				start_time BETWEEN '$fromtime' and '$totime'
				GROUP BY
				date_sk,hour_sk,sid 
				) aff
				GROUP BY
				date_sk
				) fff ON fff.date_sk = lo.date_sk
				WHERE
				lo.create_login_ts BETWEEN '$fromtime' and '$totime'
				GROUP BY
				lo.date_sk
				) ondata ON ondata.date_sk = dd.date_sk
				LEFT JOIN (
				SELECT
				COUNT(DISTINCT od.user_id) ffyh,
				COUNT(DISTINCT od.id) ffcs,
				sum(od.money) ffje,
				od.date_sk,
				xzff.xzffyh
				FROM
				".  $dwdb->dbprefix('order_info')  ." od
				LEFT JOIN (
				SELECT
				COUNT(id) AS xzffyh,
				date_sk
				FROM
				(
				SELECT
				min(id) AS id,
				count(user_id) AS count,
				device_id,
				date_sk,
				sid
				FROM
				 ".  $dwdb->dbprefix('order_info')  ."
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
				) xzff ON xzff.date_sk = od.date_sk
				WHERE
				od.rechargedate BETWEEN '$fromtime' and '$totime'
				AND od.app_id = 1
				GROUP BY
				od.date_sk
				) recharge ON recharge.date_sk = dd.date_sk
				WHERE
				dd.datevalue >= '$fromtime' and  dd.datevalue < '$totime'
				ORDER BY
				datevalue ASC
				";
	
        $query = $dwdb->query($sql);
        //echo $dwdb -> last_query();
        $dailydata = $query-> result_array();  //返回数组类型
        return  $dailydata;
    }   





      


    //获取所有区服信息
    
    function  getserverinfo()
    {

    	$dwdb = $this->load->database('dw', true);
    	$sql = "SELECT * FROM   ".  $dwdb->dbprefix('server_info');
    	$query = $dwdb->query($sql);
    	$server = $query-> result_array();  //返回数组类型
    	return $server;
    }










    //按服区分
    function  getdateserverdata($fromtime,$totime,$sid)
    {
		$dwdb = $this->load->database('dw', true);
        $sql  ="
				SELECT DISTINCT
				date(datevalue) date,
				IFNULL(USER .rdlyh, 0) AS rdlyh,
				IFNULL(USER .rxzyh, 0) AS rxzyh,
				IFNULL(ondata.dlzs, 0) AS dlzs,
				IFNULL(ondata.dljs, 0) AS dljs,
				IFNULL(ondata.duration, 0) AS duration,
				IFNULL(ondata.acu, 0) AS acu,
				IFNULL(ondata.pcu, 0) AS pcu,
				IFNULL(recharge.ffyh, 0) AS ffyh,
				IFNULL(recharge.xzffyh, 0) AS xzffyh,
				IFNULL(recharge.ffcs, 0) AS ffcs,
				IFNULL(recharge.ffje, 0) AS ffje
				FROM
				".  $dwdb->dbprefix('dim_date')  ." dd
				LEFT JOIN (
				SELECT
				COUNT(DISTINCT lo.user_id) AS rdlyh,
				IFNULL(ff.rxzyh, 0) AS rxzyh,
				lo.date_sk
				FROM
				".  $dwdb->dbprefix('login_info')  ." lo
				LEFT JOIN (
				SELECT
				COUNT(DISTINCT ded.device_id) AS rxzsb,
				COUNT(DISTINCT reu.user_id) AS rxzyh,
				COUNT(DISTINCT ro.role_id) AS rxzjs,
				ro.date_sk
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
				".  $dwdb->dbprefix('device_info')  ."	de
				GROUP BY
				de.date_sk,
				de.device_id
				) ded ON ded.device_id = ro.device_id
				AND ded.device_id = reu.device_id
				AND ded.date_sk = ro.date_sk
				WHERE
				ro.create_role_time BETWEEN '$fromtime' and '$totime'
				AND ro.sid = '$sid'
				GROUP BY
				ro.date_sk
				) ff ON ff.date_sk = lo.date_sk
				AND ff.date_sk = lo.date_sk
				WHERE
				lo.create_login_ts BETWEEN '$fromtime' and '$totime'
				AND lo.sid = '$sid'
				GROUP BY
				lo.date_sk
				) USER ON USER .date_sk = dd.date_sk
				LEFT JOIN (
				SELECT
				COUNT(DISTINCT lo.rus_id) dljs,
				COUNT(lo.id) dlzs,
				IFNULL(ff.duration, 0) duration,
				lo.date_sk,
				lo.sid,
				IFNULL(fff.pcu, 0) pcu,
				IFNULL(fff.acu, 0) acu
				FROM
				".  $dwdb->dbprefix('login_info')  ." lo
				LEFT JOIN (
				SELECT
				SUM(ud.duration) AS duration,
				ud.date_sk,
				ud.sid
				FROM
				".  $dwdb->dbprefix('user_distribution')  ." ud
				WHERE
				start_time BETWEEN '$fromtime' and '$totime'
				AND ud.sid = '$sid'
				GROUP BY
				ud.date_sk
				) ff ON ff.date_sk = lo.date_sk
				LEFT JOIN (
				SELECT
				max(hk) AS pcu,
				ceil(AVG(hk)) AS acu,
				date_sk
				FROM
				(
				SELECT
				COUNT(hour_sk) AS hk,
				date_sk
				FROM
				".  $dwdb->dbprefix('user_distribution')  ."
				WHERE
				start_time BETWEEN '$fromtime' and '$totime'
				AND sid = '$sid'
				GROUP BY
				date_sk,hour_sk,sid 
				) aff
				GROUP BY
				date_sk
				) fff ON fff.date_sk = lo.date_sk
				WHERE
				lo.create_login_ts BETWEEN '$fromtime' and '$totime'
				AND lo.sid = '$sid'
				GROUP BY
				lo.date_sk
				) ondata ON ondata.date_sk = dd.date_sk
				LEFT JOIN (
				SELECT
				COUNT(DISTINCT od.user_id) ffyh,
				COUNT(DISTINCT od.id) ffcs,
				sum(od.money) ffje,
				od.date_sk,
				xzff.xzffyh
				FROM
				".  $dwdb->dbprefix('order_info')  ." od
				LEFT JOIN (
				SELECT
				COUNT(id) AS xzffyh,
				date_sk
				FROM
				(
				SELECT
				min(id) AS id,
				count(user_id) AS count,
				device_id,
				date_sk,
				sid
				FROM
				 ".  $dwdb->dbprefix('order_info')  ."
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
				) xzff ON xzff.date_sk = od.date_sk
				WHERE
				od.rechargedate BETWEEN '$fromtime' and '$totime'
				AND od.app_id = 1
				AND od.sid = '$sid'
				GROUP BY
				od.date_sk
				) recharge ON recharge.date_sk = dd.date_sk
				WHERE
				dd.datevalue >= '$fromtime' and  dd.datevalue < '$totime'
				ORDER BY
				datevalue ASC
				";
	
        $query = $dwdb->query($sql);
        //echo $dwdb -> last_query();
        $dailydata = $query-> result_array();  //返回数组类型
        return  $dailydata;
    }





    //按区查询获取角色留存率 
    function getdayserver($from,$day,$sid)    
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
                ro.create_role_time  BETWEEN '$from1' and  '$to1' AND lo.sid  = '$sid' ";
        $query = $dwdb -> query($sql);
        //echo $dwdb ->  last_query();
        $count_did = $query-> row() -> did; 
        return $count_did;

    }




}