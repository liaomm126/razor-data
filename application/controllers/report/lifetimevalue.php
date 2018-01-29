<?php
/**
 * life time value Controller
 *
 *   LTV 用户终身价值(life time value)
 */

error_reporting(0);

class Lifetimevalue extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->Model('common');
		$this->load->library('export');
		$this->load->Model('realtimedata/lifetimevaluemodel','lifetimevaluemodel');
		$this->common->requireLogin();
		$this->common->requireProduct();
	}



	function index()
	{
		$this->common->loadHeader();
		$this->load->view('realtimedata/lifetimevalue');
	}





	function getlifetimevalue($timePhase,$fromDate = '',$toDate = '',$detailed = 'briefly')
	{

    	$toTime	=  date( "Y-m-d", time() );
    	if ($timePhase == "last7days") 
    	{
    		$fromTime	=	date("Y-m-d", strtotime("-6 day",time()));
    		$opdetailed	 = $fromDate;
    	}


        if ($timePhase == "last14days") 
        {
            $fromTime = date("Y-m-d", strtotime("-14 day",time() ));
            $opdetailed	 = $fromDate;
        }

        if ($timePhase == "last30days") 
        {
            $fromTime = date("Y-m-d", strtotime("-31 day",time() ));
            $opdetailed	 = $fromDate;
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
    	$data['userremainday']	 =	$this->lifetimevaluemodel->getlifetimevalue($fromTime,$toTime,$opdetailed);
    	echo json_encode($data);

	}



}