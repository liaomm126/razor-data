<?php
/*

充值排名
topranking controller

*/

class Topranking  extends CI_Controller
{


	function __construct()
	{
		parent::__construct();
		//加载公共核心model
		$this->load->Model('common');
		$this->load->Model('daydata/datedatamodel','datedata'); 
		$this->load->Model('realtimedata/toprankingmodel','toprankingmodel');
		$this->load->library('export'); 	//导出专用
		$this->common->requireLogin();		//判断是否登录
		$this->common->requireProduct();	//判断是否选择当前应用
	}


	function index()
	{
		$data['server']= $this->datedata->getserverinfo();
		$this->common->loadHeader();
		$this->load->view('realtimedata/topranking',$data);
	}





	function gettopranking($timePhase,$fromDate = '',$toDate = '',$sid='')
	{
		
		$toTime	=	date("Y-m-d", time() );
    	if ($timePhase == "last7days") 
    	{
    		$fromTime	=	date("Y-m-d", strtotime("-6 day",time()));
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
            $sid 	= $fromDate;
        
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

    	$data['f']	=	$fromTime;
    	$data['t']	=	$toTime;
    	$data['sid'] =	$sid;
    	$data['topranking'] = $this->toprankingmodel->getToprankingdata($fromTime,$toTime,$sid);
    	
    	echo  json_encode($data);

	}












}






?>