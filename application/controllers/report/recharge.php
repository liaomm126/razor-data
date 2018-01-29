<?php
/**
充值数据
*/
error_reporting(0);  
class Recharge extends CI_Controller
{

    private $_data = array();
    function __construct()
    {
        parent::__construct();
        $this->load->Model('common');
        $this -> load -> library('session');
        $this->load-> Model('recharge/rechargemodel','rechargemodel');     
        $this->load-> Model('recharge/Statisticsmodel','statistics');
        $this -> load -> model('channelmodel', 'channel');
        $this -> load -> model('product/productmodel', 'product');
        $this ->load -> library('export');
        $this->common->requireLogin();     
        $this->common->requireProduct(); 
    }


    //充值数据
    function index()
    {
        $productId = $this -> common -> getCurrentProduct();
        $toTime = date('Y-m-d', time());
        $fromTime = $this -> common -> getFromTime();
        $toreTime = $this -> common -> getToTime(); 
        $data['rechargelist']  = $this -> rechargemodel -> getnewlist($fromTime,$toreTime,$productId->id); 
        if (isset($_GET['type']) && $_GET['type'] == 'compare') 
        {
            $this -> common -> loadCompareHeader(lang('m_rpt_timeTrendOfUsers'), false);
            $data = array();
            $data['type'] = 'compare';
            $this -> load -> view('usage/phaseusetimeview', $data);
            return;
        }else 
        {
            $this -> common -> loadHeader();
            $this->load->view('recharge/rechargedata',$data);
        }
    }


    //订单查询
    function rechargereport($delete = null, $type = null)
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
        $this ->load-> view('layout/reportheader');
        $this ->load-> view('widgets/rechargereport', $this -> _data);
    }



    //充值数据统计
    function rechargedata($delete = null, $type = null)
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
        $this -> load -> view('widgets/rechargedata', $this -> _data);
    }



    //返回订单查询数据
    function getrecharData($timePhase, $fromDate = '', $toDate = '')
    {

        $productId = $this -> common -> getCurrentProduct();
        $toTime = date('Y-m-d',  strtotime('+1 day' , time() ) );
        if ($timePhase == "today") 
        {
            $fromTime = date('Y-m-d', time());
            $toTime = date('Y-m-d', strtotime('+1 day' , time() ) );
        }

        if ($timePhase == "yestoday") 
        {
            $fromTime = date("Y-m-d", strtotime("-1 day",time() ));
            $toTime = date('Y-m-d', time()  );
        }

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

            $fromDate = date('Y-m-d H:i:s',$fromDate);
            $toDate = date('Y-m-d H:i:s',$toDate);
            if ($fromDate>$toDate) 
            {
                $fromTime = $toDate;
                $toTime = $fromDate;
            }else{
                $fromTime = $fromDate;
                $toTime = $toDate;
            }
        }

        $data['rechargelist']  = $this->rechargemodel->getnewlist($fromTime,$toTime,$productId->id); 
        echo json_encode($data);
    }


    //返回充值数据统计数据
    function getrechargeStatistics($timePhase, $fromDate = '', $toDate = '')
    {
        $productId = $this -> common -> getCurrentProduct();
        // $year   = date("Y");
        // $month  = date("m");
        // $day    = date("d");
        // $toTime =  date('Y-m-d H:i:s',  mktime(23,59,59,$month,$day,$year));

        $toTime =   date("Y-m-d", strtotime("+1 day",time()));

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
            $toDate = $toDate;
            //$toDate = date('Y-m-d H:i:s', strtotime($toDate) + 86399);

            if ($fromDate>$toDate) 
            {
                $fromTime = $toDate;
                $toTime = $fromDate;
            }else 
            {
                $fromTime = $fromDate;
                $toTime = $toDate;
            }
        }
     $data['rechargelist']  = $this->statistics->getnewlist($fromTime,$toTime,$productId->id);   
     echo json_encode($data);
    }







    //导出配置
    function  orderphaseexport($timePhase, $fromDate = '', $toDate = '')
    {
        $productId = $this -> common -> getCurrentProduct();
        $toTime = date('Y-m-d', strtotime('+1 day' , time() ) );
        if ($timePhase == "today") 
        {
            $fromTime = date('Y-m-d', time());
            $toTime = date('Y-m-d', strtotime('+1 day' , time() ) );
        } 

        if ($timePhase == "yestoday") 
        {
            $fromTime = date("Y-m-d", strtotime("-1 day",time() ));
            $toTime = date('Y-m-d', time()  );
        }

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

            $fromDate = date('Y-m-d H:i:s',$fromDate);
            $toDate = date('Y-m-d H:i:s',$toDate);
            if ($fromDate>$toDate) {
                $fromTime = $toDate;
                $toTime = $fromDate;
            } else {
                $fromTime = $fromDate;
                $toTime = $toDate;
            }
        }
    
        $orderdata = $this->rechargemodel->getnewlist($fromTime,$toTime,$productId->id); 
        if($orderdata  != null && count($orderdata) > 0)
        {
            $data = $orderdata;
            $titlename = getExportReportTitle('奔雷无双' , lang("v_rpt_pb_orderDataDetail"),$fromTime,$toTime);
            $title = iconv("UTF-8", "GBK", $titlename);
            $this -> export -> setFileName($title);
            //Set the column headings
            $excel_title = array(iconv("UTF-8", "GBK", "充值日期"), iconv("UTF-8", "GBK", "充值时间"), iconv("UTF-8", "GBK", "RID"), iconv("UTF-8", "GBK", "UID"), iconv("UTF-8", "GBK", " 金额(元)"), iconv("UTF-8", "GBK", "充值钻石"),iconv("UTF-8", "GBK", "订单类型"),iconv("UTF-8", "GBK", "充值渠道"));
            $this -> export -> setTitle($excel_title);
            //output content
            for ($i = 0; $i < count($data); $i++)
            {
                 $row['date'] = $data[$i]['date'] . ":00";
                 $row['time'] = $data[$i]['time'];
                 $row['rid'] = $data[$i]['role_id'];
                 $row['uid'] = $data[$i]['uid'];
                 $row['price'] = $data[$i]['price'];
                 $row['pricezs'] = $data[$i]['pricezs'];
                 $row['ordertype'] = $data[$i]['ordertype'];
                 $row['channels'] = $data[$i]['channels'];
                $this -> export -> addRow($row);
            }
            $this -> export -> export();
            die();
            }else
            {
                $this -> load -> view("usage/nodataview");
            }
    }







}