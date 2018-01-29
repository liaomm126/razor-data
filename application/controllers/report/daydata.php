<?php
/*
	每日数据
	daydataController
 */
error_reporting(0); 
class  Daydata extends CI_Controller
{

	private  $_data = array();
	//加载构造函数
	public function __construct()
	{
		parent::__construct();
		//加载公共核心model
		$this->load->Model('common');
		$this->load->Model('product/productmodel','product');
    	$this->load-> Model('daydata/dailydatamodel','dailydata'); 	//每日总计
    	$this->load-> Model('daydata/datedatamodel','datedata'); 	//分日总计
    	$this->load->library('export'); 
		$this->common->requireLogin();
		$this->common->requireProduct();
	}


	//显示每日数据页面
	public function  index()
	{
		$this->common->loadHeader();
		$this->load->view('daydata/daydata');
	}
	


	//根据条件查询 显示日报数据
	public function daydatalist($timePhase, $fromDate = '')
	{

		//获取今日时间 方便转成时间戳查询
		$fromTime  = date('Y-m-d', time());
		//默认今天
	    if($timePhase == "today") 
	    {
	        $from 	=	date('Y-m-d', time());
	        $toTime =	date('Y-m-d', strtotime('+1 day', time()));
	    }
	    //默认昨天
	    if($timePhase == "yestoday") 
	    {
	        $from  	= date("Y-m-d", strtotime("-1 day", time()));
	        $toTime = date('Y-m-d', time());
	    }
	    //任意时间
	    if($timePhase == "any") 
	    {
		    $from 	=  $fromDate;
		    $toTime	=  date('Y-m-d',    strtotime("+1 day",  strtotime($from)) );
	    }

	    $data['dailydata'] =  $this->dailydata->getdailydata($from,$toTime);
	    $data['f'] = $from;
	    $data['t'] = $toTime;
	    $data['totaldata'] = $this->datedata->getdatedatalist($from,$toTime,'all'); //去重

		//不去重
		//$dayliy  =   $data['dailydata'];
		//    $totaldata = array();

		//    if(	$dayliy != NULL )
		// {

		//     foreach ($dayliy as $k => $v) 
		//     {
		//     	$totaldata['date']  =	$v['date']; //日期
		//     	$totaldata['sname']	=	'合计(不去重)';					//合计	
		//     	$totaldata['rdlyh']	+=	$v['rdlyh'];	//日登陆用户
		//     	$totaldata['rxzyh']	+=	$v['rxzyh'];	//日新增用户
		//     	$totaldata['rhyyh']	+=	$v['rhyyh'];	//日活跃用户
		//     	$totaldata['rjxycs']+=	$v['rjxycs'];	//日均使用次数
		//     	$totaldata['ACU']	+=	$v['ACU'];	//日均使用次数
		//     	$totaldata['PCU']	+=	$v['PCU'];	//日均使用次数
		//     	$totaldata['rjzxsc']+=	$v['rjzxsc'];	//日均使用时长
		//     	$totaldata['ffyh']	+=	$v['ffyh'];	//日付费用户
		//     	$totaldata['xzffyh']+=	$v['xzffyh'];	//日新增付费用户
		//     	//日登陆付费率
		//     	$totaldata['dlffl']	 = $totaldata['rdlyh'] ? number_format($totaldata['ffyh'] / $totaldata['rdlyh'] * 100,1).'%' : 0;
		//     	//日注册付费率
		//     	$totaldata['zcffl']	 = $totaldata['rxzyh'] ? number_format($totaldata['xzffyh'] / $totaldata['rxzyh'] * 100,1).'%' : 0;

		//     	$totaldata['ffcs']	+=	$v['ffcs'];	//日付费次数
		//     	$totaldata['ffje']	+=	$v['ffje'];	//日付费金额
		//     	$totaldata['ffARPU'] =	$totaldata['ffyh'] ? sprintf('%.2f',$totaldata['ffje'] / $totaldata['ffyh']) : 0 ;	
		//     	//日付费ARPU 
		//     	$totaldata['dlARPU'] =	$totaldata['rdlyh'] ? sprintf('%.2f',$totaldata['ffje'] / $totaldata['rdlyh']) : 0 ;
		// 		//日登陆ARPU
		//     	$totaldata['day1']	+=	$v['day1'];	//次日留存1天
		//     	$totaldata['day3']	+=	$v['day3'];	//次日留存3天
		//     	$totaldata['day7']	+=	$v['day7'];	//次日留存7天
		//     }

		//    }
		//    else
		//    {
		//    	$totaldata['date']   =	$from ; //日期
		//    	$totaldata['sname']	 =	'合计(不去重)';					//合计	
		//    	$totaldata['rdlyh']	 =	0;	//日登陆用户
		//    	$totaldata['rxzyh']	 =	0;	//日新增用户
		//    	$totaldata['rhyyh']	 =	0;	//日活跃用户
		//    	$totaldata['rjxycs'] =	0;	//日均使用次数
		//    	$totaldata['ACU']	 =	0;	//日均使用次数
		//    	$totaldata['PCU']	 =	0;	//日均使用次数
		//    	$totaldata['rjzxsc'] =	0;	//日均使用时长
		//    	$totaldata['ffyh']	 =	0;	//日付费用户
		//    	$totaldata['xzffyh'] =	0;	//日新增付费用户
		//    	//日登陆付费率
		//    	$totaldata['dlffl']	 = 0;
		//    	//日注册付费率
		//    	$totaldata['zcffl']	 =  0;
		//    	$totaldata['ffcs']	 =	0;	//日付费次数
		//    	$totaldata['ffje']	 =	0;	//日付费金额
		//    	$totaldata['ffARPU'] =	0;	//日付费ARPU
		//    	$totaldata['dlARPU'] =	0;	//日登陆ARPU
		//    	$totaldata['day1']	 =	0;	//次日留存1天
		//    	$totaldata['day3']	 =	0;	//次日留存3天
		//    	$totaldata['day7']	 =	0;	//次日留存7天
		//    }

		//    	$data['totaldata'] = $totaldata;
		// 		var_dump($totaldata);
	    echo json_encode($data);   


	}



	//导出配置
	public function daydatalistcsv($timePhase, $fromDate = '')
	{

		//获取今日时间 方便转成时间戳查询
		$fromTime  = date('Y-m-d', time());
		//默认今天
	    if($timePhase == "today") 
	    {
	        $from 	=	date('Y-m-d', time());
	        $toTime =	date('Y-m-d', strtotime('+1 day', time()));
	    }
	    //默认昨天
	    if($timePhase == "yestoday") 
	    {
	        $from  	= date("Y-m-d", strtotime("-1 day", time()));
	        $toTime = date('Y-m-d', time());
	    }
	    //任意时间
	    if($timePhase == "any") 
	    {
		    $from 	=  $fromDate;
		    $toTime	=  date('Y-m-d',    strtotime("+1 day",  strtotime($from)) );
	    }

	    $data['dailydata'] =  $this->dailydata->getdailydata($from,$toTime);
	    $data['f'] = $from;
	    $data['t'] = $toTime;
	    $dayliy  =   $data['dailydata'];

	    //var_dump($dayliy);
	   // die();

	    if($dayliy)
	    {
	    	 $titlename = '奔雷无双-日报数据'.$from.'.csv';
	    	 $title =  $titlename;
	    	 $this -> export -> setFileName($title);
	    	 $excel_title = array(
	    	 	iconv("UTF-8", "GBK", "日期"), 
	    	 	iconv("UTF-8", "GBK", "区服"), 
	    	 	iconv("UTF-8", "GBK", "日登陆用户"), 
	    	 	iconv("UTF-8", "GBK", "日新增用户"), 
	    	 	iconv("UTF-8", "GBK", "日活跃用户"), 
	    	 	iconv("UTF-8", "GBK", "日均使用次数"), 
	    	 	iconv("UTF-8", "GBK", "ACU"), 
	    	 	iconv("UTF-8", "GBK", "PCU"), 
	    	 	iconv("UTF-8", "GBK", "平均在线时长"), 
	    	 	iconv("UTF-8", "GBK", "付费用户"), 
	    	 	iconv("UTF-8", "GBK", "新增付费数"), 
	    	 	iconv("UTF-8", "GBK", "登陆付费率"), 
	    	 	iconv("UTF-8", "GBK", "注册付费率"), 
	    	 	iconv("UTF-8", "GBK", "付费次数"), 
	    	 	iconv("UTF-8", "GBK", "付费金额"), 
	    	 	iconv("UTF-8", "GBK", "付费用户ARPU"), 
	    	 	iconv("UTF-8", "GBK", "登陆用户ARPU"), 
	    	 	iconv("UTF-8", "GBK", "次日存留"), 
	    	 	iconv("UTF-8", "GBK", "3日存留"), 
	    	 	iconv("UTF-8", "GBK", "7日存留")
	    	 	);
		    $this -> export -> setTitle($excel_title);
		    for ($i=0; $i < count($dayliy) ; $i++) 
		    { 
		    	
		    	$totaldata['date']  =	$dayliy[$i]['date']; //日期
		    	$totaldata['sname']	=	'合计';					//合计	
		    	$totaldata['rdlyh']	+=	$dayliy[$i]['rdlyh'];	//日登陆用户
		    	$totaldata['rxzyh']	+=	$dayliy[$i]['rxzyh'];	//日新增用户
		    	$totaldata['rhyyh']	+=	$dayliy[$i]['rhyyh'];	//日活跃用户
		    	$totaldata['rjxycs']+=	$dayliy[$i]['rjxycs'];	//日均使用次数
		    	$totaldata['ACU']	+=	$dayliy[$i]['ACU'];	//日均使用次数
		    	$totaldata['PCU']	+=	$dayliy[$i]['PCU'];	//日均使用次数
		    	$totaldata['rjzxsc']+=	$dayliy[$i]['rjzxsc'];	//日均使用时长
		    	$totaldata['ffyh']	+=	$dayliy[$i]['ffyh'];	//日付费用户
		    	$totaldata['xzffyh']+=	$dayliy[$i]['xzffyh'];	//日新增付费用户
		    	//日登陆付费率
		    	$totaldata['dlffl']	 = $totaldata['rdlyh'] ? number_format($totaldata['ffyh'] / $totaldata['rdlyh'] * 100,1).'%' : 0;
		    	//日注册付费率
		    	$totaldata['zcffl']	 = $totaldata['rxzyh'] ? number_format($totaldata['xzffyh'] / $totaldata['rxzyh'] * 100,1).'%' : 0;

		    	$totaldata['ffcs']	+=	$dayliy[$i]['ffcs'];	//日付费次数
		    	$totaldata['ffje']	+=	$dayliy[$i]['ffje'];	//日付费金额
		    	$totaldata['ffARPU']+=	$dayliy[$i]['ffARPU'];	//日付费ARPU
		    	$totaldata['dlARPU']+=	$dayliy[$i]['dlARPU'];	//日登陆ARPU
 				$totaldata['day1']  +=	$dayliy[$i]['day1'];
		    	$totaldata['day3']  +=	$dayliy[$i]['day3']; 
		    	$totaldata['day7']  +=	$dayliy[$i]['day7'];
		    }
			$totaldata['day1']  =	number_format($totaldata['day1'] / $totaldata['rdlyh'] * 100,1).'%';
			$totaldata['day3']  =	number_format($totaldata['day3'] / $totaldata['rdlyh'] * 100,1).'%';
			$totaldata['day7']  =	number_format($totaldata['day7'] / $totaldata['rdlyh'] * 100,1).'%';
		    $this -> export -> addRow($totaldata);
		    for ($i=0; $i < count($dayliy) ; $i++) 
		    { 
		    	
		    	$totaldata['date']  =	$dayliy[$i]['date']; //日期
		    	$totaldata['sname']	=	$dayliy[$i]['sname'];
		    	$totaldata['rdlyh']	=	$dayliy[$i]['rdlyh'];	//日登陆用户
		    	$totaldata['rxzyh']	=	$dayliy[$i]['rxzyh'];	//日新增用户
		    	$totaldata['rhyyh']	=	$dayliy[$i]['rhyyh'];	//日活跃用户
		    	$totaldata['rjxycs']=	$dayliy[$i]['rjxycs'];	//日均使用次数
		    	$totaldata['ACU']	=	$dayliy[$i]['ACU'];	//日均使用次数
		    	$totaldata['PCU']	=	$dayliy[$i]['PCU'];	//日均使用次数
		    	$totaldata['rjzxsc']=	$dayliy[$i]['rjzxsc'];	//日均使用时长
		    	$totaldata['ffyh']	=	$dayliy[$i]['ffyh'];	//日付费用户
		    	$totaldata['xzffyh']=	$dayliy[$i]['xzffyh'];	//日新增付费用户
		    	//日登陆付费率
		    	$totaldata['dlffl']	 =  $totaldata['rdlyh'] ? number_format($totaldata['ffyh'] / $totaldata['rdlyh'] * 100,1).'%' : 0;
		    	//日注册付费率
		    	$totaldata['zcffl']	 =  $totaldata['rxzyh'] ? number_format($totaldata['xzffyh'] / $totaldata['rxzyh'] * 100,1).'%' : 0;
		    	$totaldata['ffcs']	=	$dayliy[$i]['ffcs'];	//日付费次数
		    	$totaldata['ffje']	=	$dayliy[$i]['ffje'];	//日付费金额
		    	$totaldata['ffARPU']=	$dayliy[$i]['ffARPU'];	//日付费ARPU
		    	$totaldata['dlARPU']=	$dayliy[$i]['dlARPU'];	//日登陆ARPU
 				$totaldata['day1']	= number_format($dayliy[$i]['day1'] / $totaldata['rdlyh'] * 100,1).'%';
		    	$totaldata['day3']	= number_format($dayliy[$i]['day3'] / $totaldata['rdlyh'] * 100,1).'%';
		    	$totaldata['day7']	= number_format($dayliy[$i]['day7'] / $totaldata['rdlyh'] * 100,1).'%';
		   
		    	$this -> export -> addRow($totaldata);
		    }

		    $this -> export -> export();
		    die();
	    }
	    else
	    {
	    	$titlename = '奔雷无双-日报数据'.$from.'.csv';
	    	$title =  $titlename;
	    	$this -> export -> setFileName($title);
	    	$excel_title = array(
	    	 	iconv("UTF-8", "GBK", "日期"), 
	    	 	iconv("UTF-8", "GBK", "区服"), 
	    	 	iconv("UTF-8", "GBK", "日登陆用户"), 
	    	 	iconv("UTF-8", "GBK", "日新增用户"), 
	    	 	iconv("UTF-8", "GBK", "日活跃用户"), 
	    	 	iconv("UTF-8", "GBK", "日均使用次数"), 
	    	 	iconv("UTF-8", "GBK", "ACU"), 
	    	 	iconv("UTF-8", "GBK", "PCU"), 
	    	 	iconv("UTF-8", "GBK", "平均在线时长"), 
	    	 	iconv("UTF-8", "GBK", "付费用户"), 
	    	 	iconv("UTF-8", "GBK", "新增付费数"), 
	    	 	iconv("UTF-8", "GBK", "登陆付费率"), 
	    	 	iconv("UTF-8", "GBK", "注册付费率"), 
	    	 	iconv("UTF-8", "GBK", "付费次数"), 
	    	 	iconv("UTF-8", "GBK", "付费金额"), 
	    	 	iconv("UTF-8", "GBK", "付费用户ARPU"), 
	    	 	iconv("UTF-8", "GBK", "登陆用户ARPU"), 
	    	 	iconv("UTF-8", "GBK", "次日存留"), 
	    	 	iconv("UTF-8", "GBK", "3日存留"), 
	    	 	iconv("UTF-8", "GBK", "7日存留")
	    	 	);
		    $this -> export -> setTitle($excel_title);

	    	$totaldata['date']   =	$from ; //日期
	    	$totaldata['sname']	 =	'合计';					//合计	
	    	$totaldata['rdlyh']	 =	0;	//日登陆用户
	    	$totaldata['rxzyh']	 =	0;	//日新增用户
	    	$totaldata['rhyyh']	 =	0;	//日活跃用户
	    	$totaldata['rjxycs'] =	0;	//日均使用次数
	    	$totaldata['ACU']	 =	0;	//日均使用次数
	    	$totaldata['PCU']	 =	0;	//日均使用次数
	    	$totaldata['rjzxsc'] =	0;	//日均使用时长
	    	$totaldata['ffyh']	 =	0;	//日付费用户
	    	$totaldata['xzffyh'] =	0;	//日新增付费用户
	    	//日登陆付费率
	    	$totaldata['dlffl']	 = 0;
	    	//日注册付费率
	    	$totaldata['zcffl']	 =  0;
	    	$totaldata['ffcs']	 =	0;	//日付费次数
	    	$totaldata['ffje']	 =	0;	//日付费金额
	    	$totaldata['ffARPU'] =	0;	//日付费ARPU
	    	$totaldata['dlARPU'] =	0;	//日登陆ARPU
	    	$totaldata['day1']	 =	0;	//次日留存1天
	    	$totaldata['day3']	 =	0;	//次日留存3天
	    	$totaldata['day7']	 =	0;	//次日留存7天
	    	$this -> export -> addRow($totaldata);
	    	$this -> export -> export();
		    die();
	    }

	}









}