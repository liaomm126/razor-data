<?php
/**
 * retained Controller
 *
 * 统计留存数据
 */
error_reporting(0);  

class Retained extends CI_Controller{

    private $_data = array();
    function __construct()
    {
         parent::__construct();
        $this->load->Model('common');
        $this->load->model('product/userremainmodel', 'userremain');
        $this->load->model('retained/retainedmodel', 'retainedmodel');
        $this->load->model('retained/accoutmodel', 'accoutmodel');
        $this->load->model('retained/rolemodel', 'rolemodel');
        $this->load->library('export');
        $this->load->model('event/userevent', 'userevent');
        $this->load->model('channelmodel', 'channel');
        $this->common->checkCompareProduct();
        $this->common->requireLogin();      
        $this->common->requireProduct();    

    }

    function  index()
    {

        $this->common->loadHeaderWithDateControl(); 
        $this->load->view('retained/dataview');

    }


    //设备留存率
    function equipmentretained($delete = null, $type = null)
    {
        if(isset($_GET['type']) && $_GET['type'] == 'compare') 
        {
            $products = $this->common->getCompareProducts();
            if (count($products) == 0) {
                echo 'redirecthome';
                return;
            }
            $this->load->view('layout/reportheader');
            $this->data['show_version'] = 0;
            $this->load->view('widgets/userremain', $this->data);

        }else{
            // 走这里
            $productId = $this->common->getCurrentProduct();  //获取当前应用的信息
            $this->common->requireProduct(); //判断是否有这个应用 没有 就是跳转到首页
            $productId = $productId->id; //获取id
            $procuctversion = $this->userevent->getProductVersions($productId);   // 获取当前应用版本信息  返回资源类型
            $product_channels = $this->channel->getChannelList($productId);       //获取渠道名称
            if ($procuctversion != null && $procuctversion->num_rows > 0) 
            {  
                $this->data['productversion'] = $procuctversion;
            }
            $this->data['product_channels'] = $product_channels;
            if ($delete == null) 
            {
                $this->data['add'] = "add";
            }
            if ($delete == "del") 
            {
                $this->data['delete'] = "delete";
            }
            if ($type != null) 
            {
                $this->data['type'] = $type;
            }

            $this->load->view('layout/reportheader');
            $this->load->view('widgets/equipment_retention', $this->data);
        }
    }





    //账号留存率
    function accoutretained($delete = null, $type = null)
    {
        if(isset($_GET['type']) && $_GET['type'] == 'compare') 
        {
            $products = $this->common->getCompareProducts();
            if (count($products) == 0) 
            {
                echo 'redirecthome';
                return;
            }
            $this->load->view('layout/reportheader');
            $this->data['show_version'] = 0;
            $this->load->view('widgets/userremain', $this->data);
        }else{
            //走这里
            $productId = $this->common->getCurrentProduct();  //获取当前应用的信息
            $this->common->requireProduct(); //判断是否有这个应用 没有 就是跳转到首页
            $productId = $productId->id; //获取id
            $procuctversion = $this->userevent->getProductVersions($productId);   // 获取当前应用版本信息  返回资源类型
            $product_channels = $this->channel->getChannelList($productId);  //获取渠道名称
            if ($procuctversion != null && $procuctversion->num_rows > 0)
            {  
                $this->data['productversion'] = $procuctversion;
            }
            $this->data['product_channels'] = $product_channels;
            if ($delete == null) 
            {
                $this->data['add'] = "add";
            }
            if ($delete == "del") 
            {
                $this->data['delete'] = "delete";
            }
            if ($type != null) 
            {
                $this->data['type'] = $type;
            }
        $this->load->view('layout/reportheader');
        $this->load->view('widgets/accout_retention', $this->data);
        }
    }







    //角色留存率
    function roleretained($delete = null, $type = null)
    {
        if(isset($_GET['type']) && $_GET['type'] == 'compare') 
        {
            $products = $this->common->getCompareProducts();
            if(count($products) == 0){
                echo 'redirecthome';
                return;
            }           
            $this->load->view('layout/reportheader');
            $this->data['show_version'] = 0;
            $this->load->view('widgets/userremain', $this->data);
        }else{
            //走这里
            $productId = $this->common->getCurrentProduct();  //获取当前应用的信息
            $this->common->requireProduct(); //判断是否有这个应用 没有 就是跳转到首页
            $productId = $productId->id;     //获取id
            $procuctversion = $this->userevent->getProductVersions($productId);   // 获取当前应用版本信息  返回资源类型
            $product_channels = $this->channel->getChannelList($productId);       //获取渠道名称
            if ($procuctversion != null && $procuctversion->num_rows > 0) {  
                $this->data['productversion'] = $procuctversion;
            }
            $this->data['product_channels'] = $product_channels;
            if ($delete == null) {
                $this->data['add'] = "add";
            }
            if ($delete == "del") {
                $this->data['delete'] = "delete";
            }
            if ($type != null) {
                $this->data['type'] = $type;
            }
          $this->load->view('layout/reportheader');
         $this->load->view('widgets/role_retention', $this->data);
        }
    }






    //获取设备日率  
    function getretainedweekMonthData()    
    {
        $data = array();
        $productId = $this->common->getCurrentProduct();
        $from = $this->common->getFromTime();
        $to = $this->common->getToTime();
        $products = $this->common->getCompareProducts();
        if(count($products) >= 2) {
        for($i = 0; $i < count($products); $i ++) 
        {
            $data['userremainday'][$i]['data'] = $this->userremain->getUserRemainCountByDay($version, $products[$i]->id, $from, $to, $channel)->result_array();
            $data['userremainday'][$i]['name'] = $products[$i]->name;
            $data['userremainweek'][$i]['data'] = $this->userremain->getUserRemainCountByWeek($version, $products[$i]->id, $from, $to, $channel)->result_array();
            $data['userremainweek'][$i]['name'] = $products[$i]->name;
            $data['userremainmonth'][$i]['data'] = $this->userremain->getUserRemainCountByMonth($version, $products[$i]->id, $from, $to, $channel)->result_array();
            $data['userremainmonth'][$i]['name'] = $products[$i]->name;
        }
        echo json_encode($data);
        }elseif(! empty($productId)) 
        {
            $userremainday = $this-> retainedmodel ->getUserRemainCountByDay ($from, $to);
            $list= array();
            $data['userremainday'] = $userremainday;
            $data['userremainweek'] =  $list;
            $data['userremainmonth'] =  $list;   
            echo json_encode($data,true);
        }else
        {
            echo json_encode($data);
        }
    }




    //获取用户日率  
    function getaccoutdata()    
    {
        $data = array();
        $productId = $this->common->getCurrentProduct();
        $from = $this->common->getFromTime();
        $to = $this->common->getToTime();
        $products = $this->common->getCompareProducts();
        if(count($products) >= 2) 
        {
            for($i = 0; $i < count($products); $i ++) 
            {
                $data['userremainday'][$i]['data'] = $this->userremain->getUserRemainCountByDay($version, $products[$i]->id, $from, $to, $channel)->result_array();
                $data['userremainday'][$i]['name'] = $products[$i]->name;
                $data['userremainweek'][$i]['data'] = $this->userremain->getUserRemainCountByWeek($version, $products[$i]->id, $from, $to, $channel)->result_array();
                $data['userremainweek'][$i]['name'] = $products[$i]->name;
                $data['userremainmonth'][$i]['data'] = $this->userremain->getUserRemainCountByMonth($version, $products[$i]->id, $from, $to, $channel)->result_array();
                $data['userremainmonth'][$i]['name'] = $products[$i]->name;
            }
                echo json_encode($data);

        }elseif(! empty($productId)) 
        {
                $userremainday = $this-> accoutmodel -> getaccoutdataByDay ($from, $to);
                $list= array();
                $data['userremainday'] = $userremainday;
                $data['userremainweek'] =  $list;
                $data['userremainmonth'] =  $list;                
                echo json_encode($data);
 
        }else{
                echo json_encode($data);
        }
    }




    //获取 角色 日率  
    function getroledata()    
    {
        $data = array();
        $productId = $this->common->getCurrentProduct();
        $from = $this->common->getFromTime();
        $to = $this->common->getToTime();
        $products = $this->common->getCompareProducts();
        if (count($products) >= 2) 
        {
            for ($i = 0; $i < count($products); $i ++) 
            {
                $data['userremainday'][$i]['data'] = $this->userremain->getUserRemainCountByDay($version, $products[$i]->id, $from, $to, $channel)->result_array();
                $data['userremainday'][$i]['name'] = $products[$i]->name;
                $data['userremainweek'][$i]['data'] = $this->userremain->getUserRemainCountByWeek($version, $products[$i]->id, $from, $to, $channel)->result_array();
                $data['userremainweek'][$i]['name'] = $products[$i]->name;
                $data['userremainmonth'][$i]['data'] = $this->userremain->getUserRemainCountByMonth($version, $products[$i]->id, $from, $to, $channel)->result_array();
                $data['userremainmonth'][$i]['name'] = $products[$i]->name;
            }
                echo json_encode($data);
        } else if (! empty($productId)) 
        {
                $userremainday = $this-> rolemodel -> getroledataByDay ($from, $to);
                $list= array();
                $data['userremainday'] = $userremainday;
                $data['userremainweek'] =  $list;
                $data['userremainmonth'] =  $list;                
                echo json_encode($data);
 
       } else {
                echo json_encode($data);
        }
    }




}