<?php
/**
 * Userretained Controller
 * 用户留存数据
 */
error_reporting(0);
class  Userretained  extends CI_Controller
{
   

    function __construct()
    {
        parent::__construct();
        $this->load->Model('common');
        $this->load->library('export');
		$this->load->Model('daydata/datedatamodel','datedata'); 
		$this->load->Model('serverHeldoutdata/userretainedmodel','userretainedmodel'); 
        $this->common->requireLogin();      
        $this->common->requireProduct();    
    }   



    function index()
    {
         $data['server']= $this->datedata->getserverinfo();
    	 $this->common->loadHeader(); 
    	 $this->load->view('heldoutdata/userretained',$data);
    }





    //角色留存数据请求

    function Roleofretainedlist($timePhase,$fromDate = '',$toDate = '',$sid='',$detailed = 'briefly')
    {

    	$toTime	=	date("Y-m-d", time() );
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
            $opdetailed 	= $fromDate;
        
        }        


        if ($timePhase == "any") 
        {

            $from = $fromDate;
            $to	  =	$toDate;
            $opdetailed	 = $detailed;
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
			$data['userremainday']	= $this->userretainedmodel->getUserRemainCountByDay($fromTime,$toTime);	//概略查询
		}
		else
		{
			$data['userremainday']	= $this->userretainedmodel->getdetailedByDay($fromTime,$toTime);	//详细查询
		}


    	echo  json_encode($data);
    }




















}