<?php
/*
	等级分布统计
	Gradelist Controller
 */

class  Gradelist extends CI_Controller 
{

    private $_data = array();
    function __construct()
    {
    	parent::__construct();
    	//加载公共核心model
    	$this->load->Model('common'); 
    	$this ->load-> helper(array('form', 'url'));
    	$this->load-> Model('gradelist/grademodel','grademodel'); 
    	$this ->load-> model('product/productmodel', 'product');
    	$this->load-> Model('daydata/datedatamodel','datedata'); 
    	$this->load->library('export'); 
    	$this->common->requireLogin();		
    	$this->common->requireProduct();	

    }

    //显示等级分布
    function index()
    {

    	$data['server']	= $this->datedata->getserverinfo();
    	$this->common->loadHeader();
    	$this->load->view('gradelist/gradelist',$data);

    }


  //获取等级数据
   function gradedatalist($timePhase, $fromDate = '',$sid = '1')
   {

		//获取今日时间 方便转成时间戳查询
		$fromTime  = date('Y-m-d', time());
		//默认今天
	    if($timePhase == "today") 
	    {
	        $from 	=   strtotime($fromTime);
	        $toTime =   strtotime("+1 day",time());
	        $sid 	=   $fromDate;
	    }

	    //默认昨天
	    if($timePhase == "yestoday") 
	    {
	        $fTime = date("Y-m-d", strtotime("-1 day", time()));  //减少一天
	        $from 	=  strtotime($fTime);
	        $toTime =  strtotime( $fromTime );
	        $sid 	=   $fromDate; 	

	    }

	    //任意时间
	    if($timePhase == "anythin") 
	    {

	        $fTime = strtotime( $fromDate );
	     	$tTime = strtotime("+1 day" , $fTime);
	        $from = $fTime;  
	        $toTime =  $tTime;
	        $sid = $sid;	
	    }

	    $data['gradelist']  = $this ->grademodel-> getnewlist($from,$toTime,$sid); 
	    $data['f'] = $from;
	    $data['t'] = $toTime;
	    $data['sid'] = $sid;
	    echo json_encode($data);     
   }

	//图例显示
	function gradedatacharts()
	{
		$this -> load -> view('layout/reportheader');
        $this -> load -> view('widgets/gradedatacharts', $this -> _data);
	}

	//导出配置
	function  gradelistcsv($timePhase, $fromDate = '',$sid =  '1')
	{
	    //获取今日时间 方便转成时间戳查询
		$fromTime  = date('Y-m-d', time());
		//默认今天
	    if($timePhase == "today") 
	    {
	        $from 	=   strtotime($fromTime);
	        $toTime =   strtotime("+1 day",time());
	        $sid 	=   $fromDate;
	    }

	    //默认昨天
	    if($timePhase == "yestoday") 
	    {
	        $fTime = date("Y-m-d", strtotime("-1 day", time()));  //减少一天
	        $from 	=  strtotime($fTime);
	        $toTime = strtotime( $fromTime );
	        $sid 	=   $fromDate; 	

	    }

	    //任意时间
	    if($timePhase == "anythin") 
	    {

	        $fTime = strtotime( $fromDate );
	     	$tTime = strtotime("+1 day" , $fTime);
	        $from = $fTime;  
	        $toTime =  $tTime;
	        $sid = $sid;	
	    }


	    $gradelist  = $this ->grademodel-> getnewlist($from,$toTime,$sid); 
	    //输出数据
		$data = $gradelist['level_info']['d'];
		//总数
	    $num = $gradelist['level_info']['c'];
	    if($gradelist)
	    {
		    $titlename = '奔雷无双-用户等级分布'.date("Y-m-d", $from).'.csv';
		   	$title =  $titlename;
		    $this -> export -> setFileName($title);
		     //Set the column headings
		    $excel_title = array(iconv("UTF-8", "GBK", "等级"), iconv("UTF-8", "GBK", "数量"), iconv("UTF-8", "GBK", "占比率") );
		    $this -> export -> setTitle($excel_title);
		    //output content
		    for($i = 0; $i < count($data); $i++) 
	        {
	            $row['0'] = $data[$i]['0'];
	            $row['1'] = $data[$i]['1'];
	            $row['2'] = number_format( $data[$i]['1'] / $num *100, 1 ).'%';
	            $this -> export -> addRow($row);

	        }
	        	$row['0'] = '总计';
	            $row['1'] =  $num;
	            $row['2'] = $num ? '100.0%' : '0.0%';
	        $this -> export -> addRow($row);
	        $this -> export -> export();
	        die();
        }
	}






}