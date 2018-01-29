<?php
/*
* Devicedrain Controller
*  设备流失统计
*/
error_reporting(0); // 暂时屏蔽错误
class Devicedrain extends CI_Controller
{

	private $_data = array();

	public function __construct()
	{
		parent::__construct();
		//加载公共核心model
		$this->load->Model('common'); 
		$this->load->model('devicedrain/devicedrainmodel', 'devicedrainmodel');
		$this->common->requireLogin();		
    	$this->common->requireProduct();
	}


	//显示
    public function index()
    {

    	$this->common->loadHeader();
    	$this->load->view('devicedrain/devicedrain');

    }



    //获取节点数据
    public  function  getdevicedrain($timePhase, $fromDate = '', $toDate = '')
    {

 		$toTime = date('Y-m-d', strtotime('+1 day', time())); // +1 day
        if ($timePhase == "today") 
        {   
            // 当天数据
            $fromTime = date('Y-m-d', time());
            $toTime   = date('Y-m-d', strtotime('+1 day', time()));

        }

        if ($timePhase == "yestoday") 
        { 
            //昨天数据
            $fromTime = date("Y-m-d", strtotime("-1 day", time()));
            $toTime   = date('Y-m-d', time());
        }

        if ($timePhase == "last7days") 
        { 
            //过去七天数据
            $fromTime = date("Y-m-d", strtotime("-6 day", time()));
        }


        if ($timePhase == "last30days") 
        { 
            //过去一个月数据
            $fromTime = date("Y-m-d", strtotime("-31 day", time()));
        }


        if ($timePhase == "anythin") 
        { 
            //自定义时间数据
            $fromDate = date('Y-m-d H:i:s', $fromDate);
            $toDate   = date('Y-m-d H:i:s', $toDate);
            if ($fromDate > $toDate) 
            {
                $fromTime = $toDate;
                $toTime   = $fromDate;
            }
            else
            {
                $fromTime = $fromDate;
                $toTime   = $toDate;
            }
        }

        $this->_data['f']   =   $fromTime;
        $this->_data['t']   =   $toTime;      
        $this->_data['devicedrain']	= $this->devicedrainmodel->getdevicedraindata($fromTime,$toTime);

        echo json_encode($this->_data);

    }


}