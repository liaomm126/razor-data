<?php
/**
 * heldoutdata Controller
 *
 * 设备留存数据
 */
error_reporting(0);
class  Heldoutdata  extends CI_Controller
{
   

    function __construct()
    {
        parent::__construct();
        $this->load->Model('common');
        $this->load->library('export');
		$this->load->Model('daydata/datedatamodel','datedata'); 
		$this->load->Model('serverHeldoutdata/heldoutdatamodel','heldoutdatamodel'); 
        $this->common->requireLogin();      
        $this->common->requireProduct();    
    }   



    function index()
    {
    	$this->common->loadHeader(); 
    	$this->load->view('heldoutdata/dataview');
    }





    //显示设备留存页面
    function  equipmentretained()
    {

		// $productId = $this->common->getCurrentProduct();  //获取当前应用的信息
		// $this->common->requireProduct(); //判断是否有这个应用 没有 就是跳转到首页
		// $productId = $productId->id; //获取id
		$data['server']= $this->datedata->getserverinfo();
        $this->load->view('layout/reportheader');
        $this->load->view('widgets/server_equipment',$data);

    }



    //设备留存数据请求

    function heldoutdatalist($timePhase,$fromDate = '',$toDate = '',$detailed = 'briefly')
    {

    	$toTime	= date("Y-m-d", time() );
    	if ($timePhase == "last7days") 
    	{
    		$fromTime	=	date("Y-m-d", strtotime("-6 day",time()));
    		$opdetailed = $fromDate;
    	}


        if ($timePhase == "last14days") 
        {
            $fromTime = date("Y-m-d", strtotime("-14 day",time() ));
            $opdetailed = $fromDate;

        }

        if ($timePhase == "last30days") 
        {
            $fromTime = date("Y-m-d", strtotime("-31 day",time() ));
            $opdetailed = $fromDate;

        }        


        if ($timePhase == "any") 
        {

            $from = $fromDate;
            $to	  =	$toDate;
            $opdetailed	 = $detailed;
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
    	$data['opdetailed'] =	$opdetailed;

	    //判断是概略表 还是详细表
	    if($opdetailed == "briefly")
	    {
			$data['userremainday']	= $this->heldoutdatamodel->getUserRemainCountByDay($fromTime,$toTime);	//概略查询
		}
		else
		{
			$data['userremainday']	= $this->heldoutdatamodel->getdetailedByDay($fromTime,$toTime);	//详细查询
		}


    	echo  json_encode($data);
    }




















}