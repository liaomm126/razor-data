<?php
/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */

/**
 * UMS Controller
 *
 * Post interface controller, receiver all post data and save them to mysql or
 * redis.
 *
 * @category PHP
 * @package  Controller
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Ums extends CI_Controller
{
    private $_jsondata;
    private $_prefix;
    var $rawdata = "php://input";

    /**
     * Ums
     *
     * @return void
     */
    function Ums()
    {
        parent::__construct();
        $isRedisEnabled = $this->config->item('redis'); //没有开启redis
        if ($isRedisEnabled)
        {
            $this->_prefix = "redis_service"; 
        }
        else
        {
            $this->_prefix = "service"; //走这里
        }
        $this->load->model($this->_prefix . '/utility', 'utility');
    }

    /**
     * Interface to accept client data 接受客户端数据的接口
     *
     * @return void
     */
    function postClientData()
    {
        $ret = $this->_checkJsonData();

      
        if ($ret == null)
        {  
            try  //检测
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
        $ret = $this->_checkJsonData();
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/accountdata', 'accountdata');
                $this->accountdata->addAccountdata($this->_jsondata);  
                $ret = array(
                    'flag' => 1,
                    'msg' => 'ok'
                );
            } catch ( Exception $ex ) {
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
        $ret = $this->_checkJsonData();
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
        $ret = $this->_checkJsonData();
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
        $ret = $this->_checkJsonData();
        if ($ret == null)
         {
            try {
                $this->load->model($this->_prefix . '/uploadlog', 'uploadlog');
                $this->uploadlog->addUploadlog($this->_jsondata);
                $ret = array(
                    'flag' => 1,
                    'msg' => 'ok'
                );
            } catch ( Exception $ex ) {
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
        $ret = $this->_checkJsonData();
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/usertag', 'usertag');
                $this->usertag->addUserTag($this->_jsondata);
                $ret = array(
                    'flag' => 1,
                    'msg' => 'ok'
                );
            } catch ( Exception $ex ) {
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
        $ret = $this->_checkJsonData();

        if ($ret == null) {
            $this->load->model($this->_prefix . '/update', 'update');
            $this->load->model('servicepublicclass/applicationupdatepublic', 'applicationupdatepublic');
            $updateobj = new applicationupdatepublic();
            $updateobj->loadapplicationupdate($this->_jsondata);
            $key = $updateobj->appkey;
            $version_code = $updateobj->version_code;
            $haveNewversion = $this->update->haveNewversion($key, $version_code);
            if (!$haveNewversion) {
                $ret = array(
                    'flag' => -7,
                    'msg' => 'no new version'
                );
            } else {
                try {
                    $product = $this->update->getProductUpdate($key);  // key查询对应的应用
                    if ($product != null) {
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
                } catch ( Exception $ex ) {
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
        $ret = $this->_checkJsonData();
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/onlineconfig', 'onlineconfig');
                $this->load->model('servicepublicclass/onlineconfigpublic', 'online');
                $online = new onlineconfigpublic();
                $online->loadonlineconfig($this->_jsondata);
                $productid = $this->onlineconfig->getProductid($online->appkey);
                $configmessage = $this->onlineconfig->getConfigMessage($productid);
                if ($configmessage != null) {
                    $ret = array(
                        'flag' => 1,
                        'msg' => 'ok',
                        'autogetlocation' => $configmessage->autogetlocation,
                        'updateonlywifi' => $configmessage->updateonlywifi,
                        'sessionmillis' => $configmessage->sessionmillis,
                        'reportpolicy' => $configmessage->reportpolicy
                    );
                }
            } catch ( Exception $ex ) {
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
        $ret = $this->_checkJsonData();
        if ($ret == null) {
            try {
                $this->load->model($this->_prefix . '/deviceiduid', 'userid');
                $this->userid->addDeviceidUid($this->_jsondata);
                $ret = array(
                    'flag' => 1,
                    'msg' => 'ok'
                );
            } catch ( Exception $ex ) {
                $ret = array(
                    'flag' => -4,
                    'msg' => 'DB Error'
                );
            }
        }
        echo json_encode($ret);
    }



   //设备注册信息
    function postRegeisterData() 
    {
        $ret = $this->_checkJsonData();
        if ($ret == null)
        {
            try
            {
                $this->load->model($this->_prefix . '/regeisterdata', 'userRegeister');
                $this->userRegeister->addData($this->_jsondata);
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




// 设备登录信息
    function postLoginData()
    {
        $ret = $this->_checkJsonData();
        if ($ret == null)
        {
            try
            {
                $this->load->model($this->_prefix . '/logindata', 'userLogin');
                $this->userLogin->addData($this->_jsondata);
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


// 设备创建角色信息

    function postCreateRoleData()
    {
        $ret = $this->_checkJsonData();
        if ($ret == null)
        {
            try
            {
                $this->load->model($this->_prefix . '/createroledata', 'userCreateRole');
                $this->userCreateRole->addData($this->_jsondata);
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
     * Check json format
     *
     * @return array
     */
    private function _checkJsonData()
    {
        //读取文件
        $encoded_content = file_get_contents($this->rawdata, 'r');
        if (empty($encoded_content)) {
            $ret = array(
                'flag' => -3,
                'msg' => 'Invalid content from php://input.'
            );
            return $ret;
        } else {
            //remove 'content=', and urldecode the post json string.  urldecode编码处理 string
            $jsonstr = urldecode(substr($encoded_content, 8));
            $this->_jsondata = json_decode($jsonstr); //json 转换 obj对象  ($jsonstr, true) 则返回数组   

            if ($this->_jsondata == null) {   // 等于空 则返回错误信息
                $ret = array(
                    'flag' => -4,
                    'msg' => 'Parse jsondata failed. Error No. is ' . json_last_error()
                );
                return $ret;
            }
        }
        //property_exists 检查对象或类是否具有该属性
        if (!property_exists($this->_jsondata, 'appkey')) {    //如果没有 返回错误
            $ret = array(
                'flag' => -5,
                'msg' => 'Appkey is not set in json.'
            );
            return $ret;
        }

        $appkey = $this->_jsondata->appkey;     //取出 appkey    -1 代表成功

        if (!$this->utility->isKeyAvailale($appkey)) {
            $ret = array(
                'flag' => -1,  
                'msg' => 'Invalid app key:' . $appkey
            );
            return $ret;
        }

        return null;
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
