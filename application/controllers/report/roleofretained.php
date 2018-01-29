<?php
/**
 * roleofretained Controller
 *
 * 角色留存数据
 */
error_reporting(0);
class  Roleofretained  extends CI_Controller
{
   

    function __construct()
    {
        parent::__construct();
        $this->load->Model('common');
        $this->load->library('export');
		$this->load->Model('daydata/datedatamodel','datedata'); 
		$this->load->Model('serverHeldoutdata/Roleofretainedmodel','Roleofretainedmodel'); 
        $this->common->requireLogin();      
        $this->common->requireProduct();    
    }   



    function index()
    {
         $data['server']= $this->datedata->getserverinfo();
    	 $this->common->loadHeader(); 
    	 $this->load->view('heldoutdata/roleofretained',$data);
    }




    //角色留存数据请求

    function Roleofretainedlist($timePhase,$fromDate = '',$toDate = '',$sid='',$detailed = 'briefly')
    {

    	$toTime	=	date("Y-m-d", time() );
    	if ($timePhase == "last7days") 
    	{
    		$fromTime	=	date("Y-m-d", strtotime("-6 day",time()));
    		$sid = $fromDate;
    		$opdetailed	 = $toDate;
    	}


        if ($timePhase == "last14days") 
        {
            $fromTime = date("Y-m-d", strtotime("-14 day",time() ));
            $sid = $fromDate;
            $opdetailed	 = $toDate;
        }

        if ($timePhase == "last30days") 
        {
            $fromTime = date("Y-m-d", strtotime("-31 day",time() ));
            $sid 	= $fromDate;
            $opdetailed	 = $toDate;
        }        


        if ($timePhase == "any") 
        {

            $from = $fromDate;
            $to	  =	$toDate;
            $sid  = $sid;
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
    	$data['sid'] =	$sid;
    	$data['opdetailed'] =	$opdetailed;

	    //判断是概略表 还是详细表
	    if($opdetailed == "briefly")
	    {
            //概略查询
			$data['userremainday']	= $this->Roleofretainedmodel->getUserRemainCountByDay($fromTime,$toTime,$sid);
		}
		else
		{
            //详细查询
			$data['userremainday']	= $this->Roleofretainedmodel->getdetailedByDay($fromTime,$toTime,$sid);	
		}


    	echo  json_encode($data);
    }




















}