<?php
/*
* Userstatistics Controller
* 用户统计
*/
error_reporting(0);  
class Userstatistics extends CI_Controller
{

    private $_data = array();
    function __construct()
    {
        parent::__construct();
        $this->load->Model('common');
        $this->load->Model('userstatistics/userstatistics_model','userstatisticsmodel');   //在线用户分布model
        $this->load->Model('userstatistics/statisticsdata','statisticsdata');       //在线数据统计model
        $this->common->requireLogin();
        $this->common->requireProduct();
    }

    //用户统计
    function index()
    {
        $this -> common -> loadHeader();
        $this->load->view('userstatistics/userdata', $this -> _data);
    }



    //图例显示
    function userdata()
    {
        $productId = $this -> common -> getCurrentProduct();
        if (!empty($productId)) 
        {
            if ($delete == null) 
            {
                $this -> _data['add'] = "add";
            }
            if ($delete == "del") 
            {
                $this -> _data['delete'] = "delete";
            }
        }else 
        {
            $products = $this -> common -> getCompareProducts();
            if (empty($products)) 
            {
                $this -> common -> requireProduct();
            }
        }
        if ($type != null) 
        {
            $this -> _data['type'] = $type;
        }
        $this -> load -> view('layout/reportheader');
        $this -> load -> view('widgets/userstatistics', $this -> _data);
    }






    //显示在线数据统计
    function  statisticsdata()
    {
        $this -> load -> view('layout/reportheader');
        $this -> load -> view('widgets/statisticsdata');
    }




    //在线用户分布
    function userdatatable()
    {
        $this -> load -> view('layout/reportheader');
        $this -> load -> view('widgets/userdatatable');
    }


    //请求的在线数据统计 
    function getstatisticsdata($timePhase, $fromDate = '', $toDate = '')
    {
        $year = date("Y");
        $month = date("m");
        $day = date("d");
        $toTime =  date('Y-m-d H:i:s',  mktime(23,59,59,$month,$day,$year));
        if ($timePhase == "last7days") 
        {
            $fromTime = date("Y-m-d", strtotime("-6 day",time()));
        }

        if ($timePhase == "last30days") 
        {
            $fromTime = date("Y-m-d", strtotime("-31 day",time() ));
        }

        if ($timePhase == "any") 
        {
            $fromDate = $fromDate;
            $toDate = date('Y-m-d H:i:s', strtotime($toDate) + 86399);
            if ($fromDate>$toDate) 
            {
                $fromTime = $toDate;
                $toTime = $fromDate;
            } else 
            {
                $fromTime = $fromDate;
                $toTime = $toDate;
            }
        }
        $data['statistics']  = $this->statisticsdata->getnewlist($fromTime,$toTime);   
        echo json_encode($data);
    }





    // 请求的  图表数据  （已屏蔽）
    function getuserData($timePhase, $fromDate = '', $toDate = '')
    {
        $currentProduct = $this -> common -> getCurrentProduct();

        $toTime = date('Y-m-d', time());

        if ($timePhase == "today") {
            $fromTime = date('Y-m-d', time());
            $toTime = date('Y-m-d', time());
        }


        if ($timePhase == "yestoday") {
            $fromTime = date("Y-m-d", strtotime("-1 day"));
            $toTime = date('Y-m-d', strtotime("-1 day"));
        }


        if ($timePhase == "last7days") {
            $fromTime = date("Y-m-d", strtotime("-6 day"));
        }


        if ($timePhase == "last30days") {
            $fromTime = date("Y-m-d", strtotime("-31 day"));
        }

        if ($timePhase == "any") {
            if ($fromDate>$toDate) {
                $fromTime = $toDate;
                $toTime = $fromDate;
            } else {
                $fromTime = $fromDate;
                $toTime = $toDate;
            }
        }
        $ret = array();
        if (empty($currentProduct)) {
            $products = $this -> common -> getCompareProducts();
            if (count($products) == 0) {
                echo 'noproducts';
                return;
            }
            for ($i = 0; $i < count($products); $i++) {
                $result = $this -> product -> getStarterUserCountByTime($fromTime, $toTime, $products[$i] -> id);
                $ret["content"][$i]['data'] = $result -> result_array();
                $ret["content"][$i]['name'] = $products[$i] -> name;
            }
            $ret["timeTick"] = $this -> common -> getTimeTick($toTime - $fromTime);
            $ret["type"] = array('name' => 'compare');
            echo json_encode($ret);
            return;
        }



            $toTime = date('Y-m-d', time());
            $fromTime = $this -> common -> getFromTime();
            $toreTime = $this -> common -> getToTime(); 
            $data['userdatalist']  = $this -> userstatisticsmodel -> getnewlist($fromTime,$toreTime); 
            echo json_encode($data);
    }





    // 请求: 在线用户分布的数据
    function  getusertabledata($timePhase, $fromDate = '')
    {
        $toTime = date('Y-m-d', time());
        if($timePhase == "today") 
        {
            $fromTime = date('Y-m-d', time());
            $toTime = date('Y-m-d', strtotime("+1 day",time() ));
        }


        if($timePhase == "yestoday") 
        {
            $fromTime = date("Y-m-d", strtotime("-1 day",time() ));
            $toTime =  $toTime ;
        }

        if($timePhase == "any") 
        {
             $fromTime = $fromDate;
             $toTime = date('Y-m-d', strtotime("+1 day", strtotime($fromDate) ));
        }
        
        $data['rechargelist']  = $this -> userstatisticsmodel -> getnewlist($fromTime,$toTime); 
        echo json_encode($data);
    }





}