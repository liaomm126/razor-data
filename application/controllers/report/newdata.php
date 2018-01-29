<?php
/*
* newdata Controller
* 统计新增数据
*/
error_reporting(0);     //暂时屏蔽错误
class Newdata extends CI_Controller
{

    private $_data = array();
    function __construct()
    {
        parent::__construct();
        $this->load->Model('common'); //加载公共核心model
        $this->load->model('product/productmodel', 'product');
        $this->load->model('newdata/newdatamodel', 'newdatamodel'); //加载新增数据model
        $this -> common -> requireLogin();        
    }


    function index($productId = 0)
    {

        //获取当前的应用信息
        $this->common->setCompareProducts(null); //存入session 中
        //如果没有查询到对应的 应用信息  会跳转到  控制台
        if ($this->product->checkUserPermissionToProduct($productId) == false) 
        {
            redirect(site_url());
        }
        //获取当前的应用信息
        $currentProduct = $this->common->getCurrentProduct();

        if ($currentProduct != null) 
        {
            if (!empty($productId)) 
            {
                // 第二次刷新页面 走这里
                $this->common->cleanCurrentProduct(); //清除当前应用的session
                $this->common->setCurrentProduct($productId); //重新把当前的应用 存入进去
                $this->_data['productId'] = $currentProduct->id; //取出id
            }else 
            {
                $this->common->requireProduct();
            }
        }else 
        {
            //第一次页面 默认走这里  
            if (empty($productId)) 
            {
            $this->common->requireProduct();
            }else 
            {
                $this->_data['productId'] = $productId;
                $this->common->setCurrentProduct($productId);         //重新把当前的应用 存入进去
                $currentProduct = $this->common->getCurrentProduct(); //获取当前的 应用的 信息
                
            }
        }
        $productId = $currentProduct->id; //取出id
        $this->common->loadHeader(); //载入不带头部日期的页面
        $this->load->view('newdata/newdataview');

    }



    
    //查询新增数据
    function getnewdata($timePhase, $fromDate = '')
    {

        $toTime = date('Y-m-d', strtotime('+1 day', time())); // +1 day
        if ($timePhase == "today") 
        {   
            // 当天数据
            $fromTime = date('Y-m-d', time());
            $toTime   = date('Y-m-d', strtotime('+1 day', time()));

        }

        if ($timePhase == "yestoday") 
        { 
            //昨天数据
            $fromTime = date("Y-m-d", strtotime("-1 day", time()));
            $toTime   = date('Y-m-d', time());
        }

        if ($timePhase == "last7days") 
        { 
            //过去七天数据
            $fromTime = date("Y-m-d", strtotime("-6 day", time()));
        }


        if ($timePhase == "last30days") 
        { 
            //过去一个月数据
            $fromTime = date("Y-m-d", strtotime("-31 day", time()));
        }


        if ($timePhase == "anythin") 
        { 
            //自定义时间数据
            $from = date('Y-m-d', $fromDate);       
            $toDate   = date('Y-m-d', strtotime('+1 day',$fromDate ) );
            $fromTime = $from ;
            $toTime   = $toDate;

        }

        $this->_data['f']   =   $fromTime;
        $this->_data['t']   =   $toTime;      


        $server = $this->newdatamodel->getnewlist($fromTime, $toTime);              //登录数据查询
        $logindata  =   $this->newdatamodel->total_login($fromTime, $toTime);
        $this->_data['total_loginnewequipment']       = $logindata['rdlsb'];        //日登陆设备数
        $this->_data['total_loginnewaccountnumber']   = $logindata['rdlyh'];        //日登陆账号数
        $this->_data['total_logindailyactiveaccount'] = $logindata['rdljs'];        //日登陆角色数

        $newdata  =   $this->newdatamodel->total_newdata($fromTime, $toTime);       //新增数据查询
        $this->_data['total_newequipment']           = $newdata['rxzsb'];           //日登录设备    
        $this->_data['total_newaccountnumber']       = $newdata['rxzyh'];           //日新增账号
        $this->_data['total_dailyactiveaccount']     = $newdata['rxzjs'];           //日新增角色


        // 获取新增数据列表
        $this->_data['datanewlist']   =   $server;

        if($this->_data['total_loginnewequipment'] == null) 
        {
            $this->_data['total_loginnewequipment'] = 0;
        }

        if($this->_data['total_hynewequipment'] == null) 
        {
            $this->_data['total_hynewequipment'] = 0;
        }

        if($this->_data['total_loginnewaccountnumber'] == null) 
        {
            $this->_data['total_loginnewaccountnumber'] = 0;
        }

        if($this->_data['total_hynewaccountnumber'] == null) 
        {
            $this->_data['total_hynewaccountnumber'] = 0;
        }

        if ($this->_data['total_logindailyactiveaccount'] == null) 
        {
            $this->_data['total_logindailyactiveaccount'] = 0;
        }

        if ($this->_data['total_dailyactiveaccount'] == null) 
        {
            $this->_data['total_dailyactiveaccount'] = 0;
        }

        if ($this->_data['total_hylogindailyactiveaccount'] == null) 
        {
            $this->_data['total_hylogindailyactiveaccount'] = 0;
        }
        //输出json
        echo json_encode($this->_data);

    }

















}