<?php
/*
	分日数据
	daydataController
 */
//error_reporting(0); 
class  Datedata extends CI_Controller
{
	private  $_data = array();
	//加载构造函数
	public function __construct()
	{
		parent::__construct();
		//加载公共核心model
		$this->load->Model('common');
		$this->load->Model('product/productmodel','product');
    	$this->load-> Model('daydata/datedatamodel','datedata'); 
    	$this->load->library('export'); 
		$this->common->requireLogin();
		$this->common->requireProduct();
	}	

	//显示分日数据页面
	public function  index()
	{

		$data['server']	= $this->datedata->getserverinfo();
		$this->common->loadHeader();
		$this->load->view('datedata/datedata',$data);
	}



	//返回分日数据列表
	public function datedatalist($timePhase, $fromDate = '', $toDate = '',$sid='')
	{

		//截止今天的日期
		// $year   = date("Y");
		// $month  = date("m");
		// $day    = date("d");
		// $toTime = date('Y-m-d H:i:s',  mktime(23,59,59,$month,$day,$year));
		
		$toTime	=	date("Y-m-d", strtotime("+1 day",time()));

	    if ($timePhase == "last7days") 
        {
            $fromTime = date("Y-m-d", strtotime("-6 day",time()));
            $sid = $fromDate;
        }

        if ($timePhase == "last14days") 
        {
            $fromTime = date("Y-m-d", strtotime("-14 day",time() ));
            $sid = $fromDate;
        }


        if ($timePhase == "last30days") 
        {
            $fromTime = date("Y-m-d", strtotime("-31 day",time() ));
            $sid = $fromDate;
        }

        if ($timePhase == "any") 
        {

            $from = $fromDate;
            $to	  =	$toDate;
            $sid  = $sid;
            //$toDate = date('Y-m-d H:i:s', strtotime($toDate) + 86399);
            if ($from > $to) 
            {
                $fromTime = $toDate;
                $toTime = $fromDate;
            }else 
            {
                $fromTime = $from;
                $toTime = $to;
            }
        }


        $data['datedata'] =  $this->datedata->getdatedatalist($fromTime,$toTime,$sid);

        $data['f'] =	$fromTime;
        $data['t'] =	$toTime;

        echo json_encode($data);


	}







	
}
