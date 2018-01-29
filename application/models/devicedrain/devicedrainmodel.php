<?php
/**
* devicedrainmodel
*
* 设备流失统计
*
*/

  
class Devicedrainmodel extends CI_Model
{

	public function __construct()
    {
        parent::__construct();
    }


    public function getdevicedraindata($fromTime,$toTime)
    {
    	$data = array();
		$jhsb = $this->getdeviceinfo($fromTime,$toTime); 			//返回激活设备总数
		$user = $this->getregsiterinfo($fromTime,$toTime); 			// 返回注册设备总数
        $role = $this->getroleinfo($fromTime,$toTime);              //返回创角角色设备总数


		$lossnum = $jhsb - $user;								//新建用户流失设备
		$loss  = number_format( ($lossnum/$jhsb ) * 100,2) .'%';	//新建用户流失设备效率
		

        $lossnum1 = $jhsb - $role;          //新建角色流失设备
        $loss1  = number_format( ($lossnum1/$jhsb) * 100,2) .'%';//新建角色流失设备效率

        //激活设备
		$jhsb  =  array ('jhsb'=> '激活设备' ,'dcsb'=>$jhsb, 'lossnum' => 0,'loss'=>'0.00%');
        //新建用户(节点1)
    	$jd1   =   array(
    		'jhsb' => '新建账号(节点1)', 
    		'dcsb' => $user, 
    		'lossnum' => $lossnum,
    		'loss' => $loss
    			);
        //新建角色(节点2)
        $jd2   = array(
            'jhsb'  => '新建角色(节点2)',
            'dcsb'  =>  $role,
            'lossnum' =>  $lossnum1,
            'loss' => $loss1
            );
 

        

		array_push($data, $jhsb,$jd1,$jd2);
    	
    	return $data;
    }


    //返回激活设备数
    public function getdeviceinfo($fromTime,$toTime)
    {

    	$dwdb = $this->load->database('dw', true);

    	$sql =  "
				SELECT
				IFNULL(COUNT(de.device_id),0)	 AS dcsb
				FROM
				". $dwdb->dbprefix('device_info')  ." de
				WHERE
				de.activity_time BETWEEN '$fromTime'
				AND '$toTime'
    			";

    	$query = $dwdb->query($sql);
    	//	echo $dwdb -> last_query();
    	$data  = $query->row()->dcsb;
    	return $data;
    }



    //返回创建用户设备数
    public  function getregsiterinfo($fromTime,$toTime)
    {

    	$dwdb = $this->load->database('dw', true);
    	$sql  = "
                SELECT
                COUNT(DISTINCT re.device_id) AS dcsb
                FROM
                
                 ". $dwdb->dbprefix('device_info')  ." de
                LEFT JOIN 
               ". $dwdb->dbprefix('register_info')  ." re
                ON re.device_id = de.device_id
                AND re.date_sk = de.date_sk
                WHERE
                de.activity_time BETWEEN     '$fromTime'  AND '$toTime'
   		       ";
    	$query = $dwdb->query($sql);
    	//echo $dwdb -> last_query();
    	$data  = $query->row()->dcsb;
    	return $data;
    }
    




    //返回创角角色设备数
    public function getroleinfo($fromTime,$toTime)
    {
        $dwdb = $this->load->database('dw', true);
        $sql  = "         
                SELECT
                COUNT(DISTINCT ro.device_id) AS dcsb
                FROM
                ". $dwdb->dbprefix('device_info')  ." de
                LEFT JOIN  
                ". $dwdb->dbprefix('role_info')  ." ro
                ON ro.device_id = de.device_id
                AND ro.date_sk = de.date_sk
                WHERE
                de.activity_time BETWEEN '$fromTime'  AND '$toTime'
                ";
        $query = $dwdb->query($sql);
        $data  = $query->row()->dcsb;
        return $data;     
    }












}


?>