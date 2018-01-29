<?php
/**
实时数据
*/
error_reporting(0);  
class Realtimedata extends CI_Controller
{


    private $_data = array();
    function __construct()
    {

        parent::__construct();
        $this->load->Model('common');
        $this->load->Model('realtimedata/realtimedatamodel','realtimedatamodel');
        $this->common->requireLogin();     
        $this->common->requireProduct(); 

    }


    //实时数据新增页面
    
    public function index()
    {
    	$this->common->loadHeader();
		$this->load->view('realtimedata/realtimedata');
    }


    

    //返回实时数据
    public  function realtimedatalist($timePhase, $fromDate = '',$detailed = 'briefly')
    {

        //获取今日时间 方便转成时间戳查询
        $fromTime  = date('Y-m-d', time());
        if ($timePhase == "today") 
        {
            $fromTime = date('Y-m-d', time());
            $toTime   = date('Y-m-d', strtotime('+1 day' , time() ) );
            $opdetailed   =  $fromDate;
        }

        if ($timePhase == "yestoday") 
        {
            $fromTime = date("Y-m-d", strtotime("-1 day",time() ));
            $toTime = date('Y-m-d', time()  );
            $opdetailed   =  $fromDate;
        }

        if ($timePhase == "any") 
        {

            $fromTime =  $fromDate ;  
            $toTime   =  date('Y-m-d' , strtotime("+1 day"  , strtotime( $fromDate ) ) );  
            //$fromTime =  date('Y-m-d', time()  );
            //$toTime  =    date('Y-m-d H:i:s', $fromDate   );
            $opdetailed   =  $detailed;
        }   

        $data['f'] = $fromTime;
        $data['t'] = $toTime;
     

        //判断是概略表 还是详细表
        
        if($opdetailed == "briefly")
        {
            //概略查询
            $data['realtimedata']  =  $this-> realtimedatamodel -> getrealtimedata($fromTime,$toTime);
            //总计查询
            $data['realtimedatatotal']  =    $this -> realtimedatamodel -> gettotalrealtimedata($fromTime,$toTime);
        }
        else
        {
            //详细查询 
            $data['realtimedata']  =  $this-> realtimedatamodel -> getrealtimedetaileddata($fromTime,$toTime);
            //总计查询
            $data['realtimedatatotal']  =    $this -> realtimedatamodel -> gettotalrealtimedata($fromTime,$toTime);

            $info =  $this-> realtimedatamodel-> gettotalinfodata();
            $data['realtimedatatotal']['zrxzsb']    = $info['zrxzsb'];
            $data['realtimedatatotal']['zrxzyh']    = $info['zrxzyh'];
            $data['realtimedatatotal']['zffyh']    = $info['zffyh'];
            $data['realtimedatatotal']['zffje']    = $info['zffje'];
            
        }


        $data['opdetailed'] =    $opdetailed;

        echo json_encode($data); 



    }
    




}