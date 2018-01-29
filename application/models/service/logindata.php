<?php

/**
* LoginData class  登录信息记录
*/
class LoginData extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    /**
    * addData
    * @param 添加登录信息
    * @return void
    */
    function addData($postData)
    {

        $dwdb =  $this->load->database('dw', true);
        $app_id  =  $this->getProductappid( $postData->app_info->key);   
        $date_sk =  $this->getdatesk( date('Y-m-d', $postData-> timestamp -> login_ts / 1000) );             
        $userid  =  $postData->user_info->uid;                     
        $rid     =  $postData->user_info->rid . $postData->user_info->uid . $postData->user_info->sid;  
        $devid   =  $this->getdevid( $postData->device_info->device_id );                 //验证deviceid是否存在
        $uid     =  $this->validationuserid ( $userid );                                 // 验证user_id 是否存在  
        $rid     =  $this->validationrid (  $rid  );                                    //验证rid 是否存在
        if ($devid == null )
        {   
            //如果等于null  则重新插入 device_info 设备表                                                      
            $this->load->model('service/deviceinfo', 'deviceinfo'); 
            //设置设备激活时间
            $postData->timestamp->activate_ts  =  $postData->timestamp->login_ts;               
            $this->deviceinfo->addDeviceData($postData);               
        }

        if ($uid == null) 
        {
            //如果等于null  则重新插入 user_info 用户表  
            //加载service下regeisterdata 注册信息model
            $this->load->model('service/regeisterdata', 'userRegeister');
            //设置用户注册时间       
            $postData->timestamp->register_ts = $postData->timestamp->login_ts;
            $this->userRegeister->addRegisterData($postData);                 
        }

        if ($rid == null) 
        {                                    
            //如果等于null  则重新插入 role_info 角色表   
            $this->load->model('service/createroledata', 'createroledata');
            //设置角色注册时间
            $postData->timestamp->create_role_ts    =  $postData->timestamp->login_ts;
            $this->createroledata->addData($postData);
        }

        $data = array(
        'role_id'         => isset($postData->user_info->rid) ? $postData->user_info->rid:'',
        'device_id'       => isset($postData->device_info->device_id) ? $postData->device_info->device_id:'',
        'date_sk'         =>  isset($date_sk) ? $date_sk : '',
        'sid'             => isset($postData->user_info->sid) ? $postData->user_info->sid : '',
        'user_id'         => isset($postData->user_info->uid) ? $postData->user_info->uid: '',
        'app_id'          => isset( $app_id ) ?  $app_id : '',
        'rus_id'          => $postData->user_info->rid. $postData->user_info->uid.$postData->user_info->sid,
        'create_login_ts' => isset($postData->timestamp->login_ts) ? date('Y-m-d H:i:s' , $postData->timestamp->login_ts / 1000 ): '',
        );

        $dwdb ->insert('login_info', $data);

    }


    //验证deviceid 是否存在 
    function  getdevid($id)
    {

        $dwdb =  $this->load->database('dw', true);
        $query = $dwdb->query("select  device_id  from ".  $dwdb->dbprefix('device_info')   . "   where device_id = '$id'");
        if ($query != null && $query->num_rows() > 0) 
        {
            return $query->first_row()->device_id;
        }
        return null;
    }

    //验证 用户表 user_id 是否存在
    function validationuserid($uid)
    {
        $dwdb =  $this->load->database('dw', true);
        $query = $dwdb->query("select user_id from ".   $dwdb->dbprefix('register_info')   . "   where user_id  = '$uid'");
        if ($query != null && $query->num_rows() > 0) 
        {
            return $query->first_row()->user_id;
        }
        return null;
    }


    //验证 角色表 rus_id 是否存在
    function  validationrid($rid)
    {
        $dwdb  = $this->load->database('dw', true);
        $query = $dwdb->query("select  rus_id from ".   $dwdb->dbprefix('role_info')  . " where rus_id  = '$rid'");
        if ($query != null && $query->num_rows() > 0) 
        {
            return $query->first_row()->rus_id;
        }
        return null;
    }




    //获取日期key
    function  getdatesk($date)
    {
        $dwdb =  $this->load->database('dw', true);
        $query = $dwdb->query("select  date_sk  from " .$dwdb->dbprefix('dim_date') . " where datevalue = '$date'");
        if ($query != null && $query->num_rows() > 0) 
        {
            return $query->first_row()->date_sk;
        }
        return null;
    }

    //验证应用key
    function getProductappid($key)
    {
        $db =  $this->load->database('default', true);
        $query = $db->query("select product_id from " . $db->dbprefix('channel_product') . " where productkey = '$key'");
        if ($query != null && $query->num_rows() > 0) 
        {
            return $query->first_row()->product_id;
        }
        return null;
    }





}
?>
