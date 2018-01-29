<?php
/**
 * Playerretained Controller
 *
 * 付费用户留存数据
 */
error_reporting(0);
class  Playerretained  extends CI_Controller
{
   

    function __construct()
    {
        parent::__construct();
        $this->load->Model('common');
        $this->load->library('export');
		$this->load->Model('serverHeldoutdata/playerretainedmodel','playerretainedmodel'); 
        $this->common->requireLogin();      
        $this->common->requireProduct();    
    }   



    function index()
    {
    	$this->common->loadHeader(); 
    	$this->load->view('heldoutdata/playerretained');
    }









    //角色留存数据请求

    function playerretainedlist($timePhase,$fromDate = '',$toDate = '')
    {

    	$toTime	=	date("Y-m-d", time() );
    	if ($timePhase == "last7days") 
    	{
    		$fromTime	=	date("Y-m-d", strtotime("-6 day",time()));
    	}


        if ($timePhase == "last14days") 
        {
            $fromTime = date("Y-m-d", strtotime("-14 day",time() ));
        }

        if ($timePhase == "last30days") 
        {
            $fromTime = date("Y-m-d", strtotime("-31 day",time() ));
        }        


        if ($timePhase == "any") 
        {

            $from = $fromDate;
            $to	  =	$toDate;
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
	    $data['userremainday']	= $this->playerretainedmodel->getplayerretainedByDay($fromTime,$toTime,$sid);	
    	echo  json_encode($data);
    }




















}