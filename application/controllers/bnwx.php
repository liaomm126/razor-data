<?php


class Bnwx extends CI_Controller
{

    private $_jsondata;
    private $_prefix;


    //数据流
    var $rawdata = "php://input";
    var $ret = array();
    /**
    * Bnwx
    *
    * @return void
    */
    function Bnwx()
    {
        parent::__construct();
        $isRedisEnabled = $this->config->item('redis'); //没有开启redis
        if($isRedisEnabled)
        {
            $this->_prefix = "redis_service"; 
        }
        else
        {
            $this->_prefix = "service"; //走这里
        }
        $this->load->model($this->_prefix . '/utility', 'utility');
    }



    //登录设备信息deviceinfo
    function postdeviceinfo()
    {
        if($this->_jsondata != null) 
        {
            try
            {
                $this-> load -> model($this->_prefix . '/deviceinfo', 'deviceinfo');
                $this-> deviceinfo -> addDeviceData($this->_jsondata);           
                $this->ret['deviceinfo'] = array(
                'flag' => 'deviceinfo',
                'msg' => 'ok'
                   );
            }catch (Exception $e)
            {
                $this->ret['deviceinfo']  = array(
                'flag' => 'deviceinfo',
                'msg' => 'DB Error'
                );
            }
            echo json_encode($this->ret);
        }
    }





    //用户注册信息 register_info
    function postregisterData() 
    {
        if ($this->_jsondata != null)
        {
            try
            {
                $this->load->model($this->_prefix . '/regeisterdata', 'userRegeister');
                $this->userRegeister->addRegisterData($this->_jsondata);
                $this->ret['register'] = array(
                'flag' => 'register',
                'msg' => 'ok'
                );
            }
            catch( Exception $ex )
            {
                $this->ret['register'] = array(
                'flag' => 'register',
                'msg' => 'DB Error'
                );
            }
            echo json_encode($this->ret);
        }
    }




    //用户创建角色信息    CreateRoleData
    function postCreateRoleData() 
    {
        if ($this->_jsondata != null)
        {
            try
            {
                $this->load->model($this->_prefix.'/createroledata', 'createroledata');
                $this->createroledata->addData($this->_jsondata);
                $this->ret['CreateRole'] = array(
                'flag' => 'CreateRole',
                'msg' => 'ok'
                );
            }
            catch ( Exception $ex )
            {
                $this->ret['CreateRole'] = array(
                'flag' => 'CreateRole',
                'msg' =>  'DB Error'
                );
            }
            echo json_encode($this->ret);
        }
    }



    //应用登录信息
    function postLoginData()
    {
        if ($this->_jsondata != null)
        {
            try
            {
                $this->load->model($this->_prefix.'/logindata','userLogin');
                $this->userLogin->addData($this->_jsondata);
                $this->ret['Login'] = array(
                'flag' => 'Login',
                'msg' => 'ok'
                );
            }
            catch( Exception $ex )
            {
                $this->ret['Login']  = array(
                'flag' => 'Login',
                'msg' => 'DB Error'
                );
            }
            echo json_encode($this->ret);
        }
    
    }




    //处理订单信息
    function postOrderData()
    {
        $ret = array('flag' => 'orderdata','msg' => 'NUll');
        if ($this->_jsondata != null)
        {
            try
            {
            $this->load->model($this->_prefix.'/orderdata','orderdata');
            $this->orderdata->addData($this->_jsondata); 
            $this->ret['orderdata'] = array(
            'flag' => 'orderdata',
            'msg' => 'ok'
            );
        }
            catch ( Exception $ex )
            {
            $this->ret['orderdata'] = array(
            'flag' => 'orderdata',
            'msg' => 'DB Error'
            );
            }
        }
        echo json_encode($this->ret);
    }




    //处理用户时长分布
    function  postSessionsData()
    {
        if($this->_jsondata != null)
        {
            try
            {
                $this->load->model($this->_prefix.'/sessions','sessions');
                $this->sessions->addData($this->_jsondata);
                $this->ret['Sessions'] = array(
                'flag' => 'sessions',
                'msg' => 'ok'
                );
            }
            catch( Exception $ex )
            {
                $ret['Sessions'] = array(
                'flag' => 'Sessions',
                'msg' => 'DB Error'
                );
            }
        }
        echo json_encode($this->ret);
    }



    //处理等级分布
    function postGradedata($jsonstr)
    {
        if($jsonstr)
        {
            try
            {
                $this->load->model($this->_prefix.'/gradedata','gradedata');
                $this->gradedata->addData( $jsonstr );
                $this->ret['gradedata'] = array(
                'flag' => 'gradedata',
                'msg' => 'ok'
                );
            }
            catch ( Exception $ex )
            {
                $ret['gradedata'] = array(
                'flag' => 'gradedata',
                'msg' => 'DB Error'
                );
            }
        }
        echo json_encode($this->ret);
    }








    // 开源自带数据源接口
    /**
    * Interface to accept client data 接受客户端数据的接口
    *
    * @return void
    */
    function postClientData()
    {
        $ret = $this->checkJsonData();
        if ($ret == null)
        {  
            try 
            {
                $this->load->model($this->_prefix . '/clientdata', 'clientdata');  //载入model
                $this->clientdata->addClientdata($this->_jsondata);
                $ret = array(
                'flag' => 1,
                'msg' => 'ok'
                );
            }
            catch ( Exception $ex )  //捕获
            {
                $ret = array(
                'flag' => -4,
                'msg' => 'DB Error'
                );
            }
        }
        log_message('debug', json_encode($ret));
        echo json_encode($ret);
    }

    //提交帐户数据  accountData表
    function postAccountData()  
    {
        $ret = $this->checkJsonData();
        if ($ret == null) 
        {
            try 
            {
                $this->load->model($this->_prefix . '/accountdata', 'accountdata');
                $this->accountdata->addAccountdata($this->_jsondata);  
                $ret = array(
                'flag' => 1,
                'msg' => 'ok'
                );
            }catch( Exception $ex ) 
            {
                $ret = array(
                'flag' => -4,
                'msg' => 'DB Error'
                );
            }
        }
        log_message('debug', json_encode($ret));
        echo json_encode($ret);
    }

    /**
    * Interface to accept Activity Log 
    *
    * @return void
    */ 
    //活动日志接口  clientusinglog表
    function postActivityLog()
    {
        $ret = $this->checkJsonData();
        if ($ret == null)
        {
            try
            {
                $this->load->model($this->_prefix . '/activitylog', 'activitylog');
                $this->activitylog->addActivitylog($this->_jsondata);
                $ret = array(
                'flag' => 1,
                'msg' => 'ok'
                );
            }
            catch ( Exception $ex )
            {
                $ret = array(
                'flag' => -4,
                'msg' => 'DB Error'
                );
            }
        }
        echo json_encode($ret);
    }

    /**
    * Interface to accept event log by client
    * Must pass parameters:appkey,event_identifier,time,activity,version
    *
    * @return void   添加事件标记
    */
    function postEvent()
    {
        $ret = $this->checkJsonData();
        if ($ret == null)
        {
            $this->load->model($this->_prefix . '/event', 'event');
            $isgetEventid = $this->event->addEvent($this->_jsondata);
            if (!$isgetEventid)
            {
                $ret = array(
                'flag' => -5,
                'msg' => 'event_identifier is not defined'
                );
                echo json_encode($ret);
                return;
            }
            else
            {
                $ret = array(
                'flag' => 1,
                'msg' => 'ok'
                );
            }
        }
        echo json_encode($ret);
    }

    /**
    * Interface to accept total log    接受总日志的接口
    *
    * @return void
    */
    function uploadLog()
    {
        $ret = $this->checkJsonData();
        if ($ret == null)
        {
            try 
            {
                $this->load->model($this->_prefix . '/uploadlog', 'uploadlog');
                $this->uploadlog->addUploadlog($this->_jsondata);
                $ret = array(
                'flag' => 1,
                'msg' => 'ok'
                );
            }catch ( Exception $ex ) 
            {
                $ret = array(
                'flag' => -4,
                'msg' => 'DB Error'
                );
            }
        }
        echo json_encode($ret);
    }

    /**
    * Interface to accept user id  for user tag 接受用户标签的用户标识
    *
    * @return void
    */
    function postTag()
    {
        $ret = $this->checkJsonData();
        if ($ret == null) 
        {
            try 
            {
                $this->load->model($this->_prefix . '/usertag', 'usertag');
                $this->usertag->addUserTag($this->_jsondata);
                $ret = array(
                'flag' => 1,
                'msg' => 'ok'
                );
            }catch ( Exception $ex ) 
            {
                $ret = array(
                'flag' => -4,
                'msg' => 'DB Error'
                );
            }
        }
        echo json_encode($ret);
    }


    /**
    * Get Application Update by version no  获取应用程序更新版本号
    *
    * @return void
    */
    function getApplicationUpdate()
    {
        $ret = $this->checkJsonData();
        if ($ret == null) 
        {
            $this->load->model($this->_prefix . '/update', 'update');
            $this->load->model('servicepublicclass/applicationupdatepublic', 'applicationupdatepublic');
            $updateobj = new applicationupdatepublic();
            $updateobj->loadapplicationupdate($this->_jsondata);
            $key = $updateobj->appkey;
            $version_code = $updateobj->version_code;
            $haveNewversion = $this->update->haveNewversion($key, $version_code);
            if (!$haveNewversion) 
            {
                $ret = array(
                'flag' => -7,
                'msg' => 'no new version'
                );
            }else 
            {
            try 
            {
            $product = $this->update->getProductUpdate($key);  // key查询对应的应用
                if ($product != null) 
                {
                $ret = array(
                    'flag' => 1,
                    'msg' => 'ok',
                    'fileurl' => $product->updateurl,
                    'forceupdate' => $product->man,
                    'description' => $product->description,
                    'time' => $product->date,
                    'version' => $product->version
                );
                }
            }catch ( Exception $ex ) 
                {
                    $ret = array(
                    'flag' => -4,
                    'msg' => 'DB Error'
                    );
                }
            }
        }
        echo json_encode($ret);
    }


    /**
    * Used to get Online Configuration 用于获取联机配置
    *
    * @return void
    */
    function getOnlineConfiguration()
    {
        $ret = $this->checkJsonData();
        if ($ret == null) 
        {
            try {
            $this->load->model($this->_prefix . '/onlineconfig', 'onlineconfig');
            $this->load->model('servicepublicclass/onlineconfigpublic', 'online');
            $online = new onlineconfigpublic();
            $online->loadonlineconfig($this->_jsondata);
            $productid = $this->onlineconfig->getProductid($online->appkey);
            $configmessage = $this->onlineconfig->getConfigMessage($productid);
            if ($configmessage != null) 
            {
                $ret = array(
                'flag' => 1,
                'msg' => 'ok',
                'autogetlocation' => $configmessage->autogetlocation,
                'updateonlywifi' => $configmessage->updateonlywifi,
                'sessionmillis' => $configmessage->sessionmillis,
                'reportpolicy' => $configmessage->reportpolicy
                );
            }
            } catch ( Exception $ex ) 
            {
                $ret = array(
                'flag' => -4,
                'msg' => 'DB Error'
                );
            }
        }
        echo json_encode($ret);
    }

    /**
    * Interface to accept User Id      接受用户标识的接口
    * 
    * @return void   deviceid_userid此表 已删除
    */
    function postUserid() 
    {
        $ret = $this->checkJsonData();
        if ($ret == null) 
        {
            try{
                $this->load->model($this->_prefix . '/deviceiduid', 'userid');
                $this->userid->addDeviceidUid($this->_jsondata);
                $ret = array(
                'flag' => 1,
                'msg' => 'ok'
                );
            }
            catch ( Exception $ex ) 
            {
                $ret = array(
                'flag' => -4,
                'msg' => 'DB Error'
                );
            }
        }
        echo json_encode($ret);
    }




    public  function checkJsonData(){
    //读取文件
    $encoded_content = file_get_contents($this->rawdata, 'r');
    log_message ( "error", $encoded_content );

    if (empty($encoded_content)) 
    {
        $ret = array(
        'flag'    => -1,
        'content' =>  'Empty content',
        'msg'     => 'Invalid content from php://input.'
        );
        echo json_encode($ret);     
    }
    else
    {
        //remove 'content=', and urldecode the post json string.  urldecode编码处理 string
        $jsonstr = urldecode(substr($encoded_content, 8));
        log_message( "error", $jsonstr );
        $this->_jsondata = json_decode($jsonstr); //json 转换 obj对象  ($jsonstr, true) 则返回数组   
        if($this->_jsondata == null) 
        {   // 等于空 则返回错误信息
            $ret = array(
            'flag' => 0,
            'content' =>  'Jsondata  failed',
            'msg' => 'Parse jsondata failed. Error No. is ' . json_last_error()
            );
            echo json_encode($ret);
        }
    }




    //设备信息    
    if ($this->_jsondata != null) 
    { 
        if( property_exists($this->_jsondata , 'device_info') &&  
            property_exists($this->_jsondata , 'timestamp')   &&  
            property_exists($this->_jsondata , 'app_info') )
        {
            if(property_exists($this->_jsondata->device_info , 'device_id')  &&   
               property_exists($this->_jsondata->timestamp , 'activate_ts')  &&   
               property_exists($this->_jsondata->app_info , 'key')  ) 
            {      
                $this->postdeviceinfo();   
            } 

        }
    }


    //用户注册 
    if ($this->_jsondata != null) 
    { 

        if( property_exists($this->_jsondata , 'device_info')  &&  
            property_exists($this->_jsondata , 'app_info')  &&  
            property_exists($this->_jsondata , 'timestamp') &&
            property_exists($this->_jsondata , 'user_info') )
        { 
            if( property_exists($this->_jsondata->app_info , 'key')  &&   
                property_exists($this->_jsondata->device_info , 'device_id')   &&   
                property_exists($this->_jsondata->timestamp , 'register_ts')   &&
                property_exists($this->_jsondata->user_info , 'uid') )
            {  
                $this-> postregisterData();  
            }
        }

    }



    //角色创建
    if ($this->_jsondata != null) 
    { 

        if( property_exists($this->_jsondata , 'device_info') && 
            property_exists($this->_jsondata , 'app_info')    &&  
            property_exists($this->_jsondata , 'timestamp')   &&  
            property_exists($this->_jsondata , 'user_info') )
        {
            if( property_exists($this->_jsondata->app_info , 'key')  &&   
                property_exists($this->_jsondata->device_info , 'device_id') &&   
                property_exists($this->_jsondata->timestamp ,  'create_role_ts') && 
                property_exists($this->_jsondata->user_info , 'sid')  && 
                property_exists($this->_jsondata->user_info , 'uid')  && 
                property_exists($this->_jsondata->user_info , 'rid') )
            {  
                $this-> postCreateRoleData();  
            }
        }
    }



    //用户登录
    if ($this->_jsondata != null) 
    { 

        if( property_exists($this->_jsondata , 'device_info')  &&  
            property_exists($this->_jsondata , 'app_info')     &&  
            property_exists($this->_jsondata , 'timestamp')    &&  
            property_exists($this->_jsondata , 'user_info') )
        {
            if( property_exists($this->_jsondata->app_info , 'key')  &&   
                property_exists($this->_jsondata->device_info , 'device_id')    &&   
                property_exists($this->_jsondata->timestamp , 'login_ts')   && 
                property_exists($this->_jsondata->user_info , 'sid')  && 
                property_exists($this->_jsondata->user_info , 'uid')  && 
                property_exists($this->_jsondata->user_info , 'rid') )
            {  
                $this-> postLoginData();  
            }
        }

    }


    //订单查询

    if ($this->_jsondata != null) 
    { 

        if( property_exists($this->_jsondata,'order_info')  &&  
            property_exists($this->_jsondata , 'app_info')     &&  
            property_exists($this->_jsondata , 'device_info')  &&  
            property_exists($this->_jsondata , 'timestamp')   &&  
            property_exists($this->_jsondata , 'user_info') )
        {
            if( property_exists($this->_jsondata->device_info , 'device_id') &&
                property_exists($this->_jsondata->user_info , 'uid')  && 
                property_exists($this->_jsondata->user_info , 'sid')  && 
                property_exists($this->_jsondata->user_info , 'rid')  &&
                property_exists($this->_jsondata->order_info , 'money')  && 
                property_exists($this->_jsondata->order_info , 'productid')   &&
                property_exists($this->_jsondata->order_info , 'imp')   &&
                property_exists($this->_jsondata->timestamp , 'recharge_ts') )
            {    
                $this-> postOrderData();  
            }
        }

    }



    //  用户时长分布
    if ($this->_jsondata != null) 
    {   
        //先判断 整体数据
        if( property_exists($this->_jsondata,'sessions') &&  
            property_exists($this->_jsondata , 'device_info')  &&  
            property_exists($this->_jsondata , 'user_info') ) //检测 二个数据是否存在
        {
            if( property_exists($this->_jsondata->device_info , 'device_id') &&     //判断 device_id 是否存在
                property_exists($this->_jsondata->user_info , 'uid')         && 
                property_exists($this->_jsondata->user_info , 'sid')         && 
                property_exists($this->_jsondata->user_info , 'rid') )
            {    
                $this->postSessionsData();      //条件满足 则写入进去
            } 
        }

    }



    //用户等级分布
    if($this->_jsondata  != null )
    {
        if( property_exists($this->_jsondata ,'level_info') )
        {


            if( property_exists($this->_jsondata->level_info ,'d') && 
                property_exists($this->_jsondata->level_info ,'c') && 
                property_exists($this->_jsondata->level_info ,'t') )
            {  
                
                $this->postGradedata($jsonstr);   //满足条件 就写入
            } 

        }
    }





    }

    /**
    * Uncompress the gzipped data
    *
    * @return void
    */
    function unCompressGzip()
    {
        $data = $_POST['content'];
        $this->utility->gzdecode($data);
    }



}
?> 