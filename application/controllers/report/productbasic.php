<?php
/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */

/**
 * Productbasic Controller
 *
 * @category PHP
 * @package  Model
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Productbasic extends CI_Controller
{
     /**
     * Data array $data
     */
    private $_data = array();

    /**
     * Construct funciton, to pre-load database configuration
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this -> load -> helper(array('form', 'url'));
        $this -> load -> library('form_validation');
        $this -> load -> Model('common');
        $this -> load -> model('channelmodel', 'channel');
        $this -> load -> model('product/productmodel', 'product');
        $this -> load -> model('product/newusermodel', 'newusermodel');
        $this -> load -> model('product/productanalyzemodel', 'productanalyze');
        $this -> common -> requireLogin();
        $this -> load -> model('product/usinganalyzemodel', 'usinganalyzemodel');
        $this -> load -> model('dashboard/dashboardmodel', 'dashboard');
        $this -> load -> model('analysis/trendandforecastmodel', 'gettrend');
        $this -> load -> library('export');
        $this -> common -> checkCompareProduct();
    }

    /**
     * View
     *
     * @param int $productId productId
     *
     * @return void
     */
    function view($productId = 0)
    {
        //if compare then load compare page
        

        // if (isset($_GET['type']) && 'compare' == $_GET['type']) {
        //     $products = $this -> common -> getCompareProducts();
        //     $this -> common -> loadCompareHeader(lang('m_rpt_dashboard'));
        //     $this -> load -> view('compare/userbehavorview');
        //     return;
        // }



        $this -> common -> setCompareProducts(null);  //存入session 中  

        //var_dump($productId);   1
        
        //如果没有查询到对应的 应用信息  会跳转到  控制台
        if ($this -> product ->checkUserPermissionToProduct($productId)==false) {
            redirect(site_url()); 
        }


        //获取当前的应用信息
        $currentProduct = $this -> common -> getCurrentProduct();

        if ($currentProduct != null) {
            if (!empty($productId)) {
                 // echo  "hello";  //  第二次刷新页面 走这里
                $this -> common -> cleanCurrentProduct();     //清除当前应用的session
                $this -> common -> setCurrentProduct($productId);  //重新把当前的应用 存入进去
                $this -> _data['productId'] = $currentProduct -> id;        //取出id
            } else {
                $this -> common -> requireProduct();
            }
        } else {
            //  第一次页面 默认走这里   echo  "world";
            if (empty($productId)) {
                $this -> common -> requireProduct();  
            } else {
                $this -> _data['productId'] = $productId;      
                $this -> common -> setCurrentProduct($productId); //重新把当前的应用 存入进去
                $currentProduct = $this -> common -> getCurrentProduct();  //获取当前的 应用的 信息
            }
        }


        $productId = $currentProduct -> id; //取出id

        $this -> common -> loadHeaderWithDateControl(); //载入头部日期页面

        $toTime = date('Y-m-d', time());    //得到当前的日期 

        $yestodayTime = date("Y-m-d", strtotime("-1 day"));     // 昨天日期

        $this -> _data['today1'] = $this -> productanalyze -> getTodayInfo($productId, $toTime);    //组合查询 近日概括

        $this -> _data['yestoday'] = $this -> productanalyze -> getTodayInfo($productId, $yestodayTime);

        $this -> _data['overall'] = $this -> productanalyze -> getOverallInfo($productId);  //查询 总体概括

   
        $fromTime = $this -> common -> getFromTime();       //从session 中获取当前的时间
        $toreTime = $this -> common -> getToTime();         //从session 中获取结束的时间    


        $this -> _data['dashboardDetailData'] = $this -> newusermodel -> getDetailUserDataByDay($fromTime, $toTime); //取得用户数据明细

        $this -> loadaddreport($productId);

    


        $this -> load -> view('overview/productview', $this -> _data);
      
    }

    /**
     * GetTypeAnalyzeData 
     *
     * @param string $timePhase timePhase
     * @param string $fromDate  fromDate
     * @param string $toDate    toDate
     *
     * @return json
     */
    function getTypeAnalyzeData($timePhase, $fromDate = '', $toDate = '')
    {
        $currentProduct = $this -> common -> getCurrentProduct();

        $toTime = date('Y-m-d', time());


        if ($timePhase == "today") {
            $fromTime = date('Y-m-d', time());
            $toTime = date('Y-m-d', time());
        }


        if ($timePhase == "yestoday") {
            $fromTime = date("Y-m-d", strtotime("-1 day"));
            $toTime = date('Y-m-d', strtotime("-1 day"));
        }


        if ($timePhase == "last7days") {
            $fromTime = date("Y-m-d", strtotime("-6 day"));
        }


        if ($timePhase == "last30days") {
            $fromTime = date("Y-m-d", strtotime("-31 day"));
        }

        if ($timePhase == "any") {
            if ($fromDate>$toDate) {
                $fromTime = $toDate;
                $toTime = $fromDate;
            } else {
                $fromTime = $fromDate;
                $toTime = $toDate;
            }
        }

        $ret = array();

     //   var_dump( empty($currentProduct) ); 

        //load compare data
        if (empty($currentProduct)) {
            $products = $this -> common -> getCompareProducts();
            if (count($products) == 0) {
                echo 'noproducts';
                return;
            }
            for ($i = 0; $i < count($products); $i++) {
                $result = $this -> product -> getStarterUserCountByTime($fromTime, $toTime, $products[$i] -> id);
                $ret["content"][$i]['data'] = $result -> result_array();
                $ret["content"][$i]['name'] = $products[$i] -> name;
            }
            $ret["timeTick"] = $this -> common -> getTimeTick($toTime - $fromTime);
            $ret["type"] = array('name' => 'compare');
            echo json_encode($ret);
            return;
        }

        //走这里
        //load other data
        
        $query = $this -> product -> getStarterUserCountByTime($fromTime, $toTime, $currentProduct -> id);
        $ret["content"] = $query -> result_array();
        $ret["timeTick"] = $this -> common -> getTimeTick($toTime - $fromTime);
        echo json_encode($ret);
    }
    /**
     * Addphaseusetimereport
     *
     * @param string $delete delete
     * @param string $type   type
     *
     * @return void
     */
    function addphaseusetimereport($delete = null, $type = null)
    {
        $productId = $this -> common -> getCurrentProduct();
        if (!empty($productId)) {
            if ($delete == null) {
                $this -> _data['add'] = "add";
            }
            if ($delete == "del") {
                $this -> _data['delete'] = "delete";
            }
        } else {
            $products = $this -> common -> getCompareProducts();
            if (empty($products)) {
                $this -> common -> requireProduct();
            }
        }
        if ($type != null) {
            $this -> _data['type'] = $type;
        }
        $this -> load -> view('layout/reportheader');

        //   var_dump($this-> _data);  add
        $this -> load -> view('widgets/phaseusetime', $this -> _data);
    }

    /**
     * Phaseusetime
     * @return void
     */
    function phaseusetime()
    {
        if (isset($_GET['type']) && $_GET['type'] == 'compare') {
            $this -> common -> loadCompareHeader(lang('m_rpt_timeTrendOfUsers'), false);
            $data = array();
            $data['type'] = 'compare';
            $this -> load -> view('usage/phaseusetimeview', $data);
            return;
        } else {
            $this -> common -> loadHeader();
            $this -> load -> view('usage/phaseusetimeview');
        }
    }

    /**
     * Adduserbehavorviewreport
     *
     * @return void
     */
    function adduserbehavorviewreport()     //用户行为基本概括
    {
        $fromTime = $this -> common -> getFromTime(); //  获取存在session 的默认值
        $toreTime = $this -> common -> getToTime();   //  获取存在session 的默认值

                //getTimePhaseStr  自定义辅助函数 处理日期区间 样式 函数
        $this -> _data['reportTitle'] = array('timePase' => getTimePhaseStr($fromTime, $toreTime), 'newUser' => lang("t_newUserSta"), 'totalUser' => lang("t_accumulatedUserSta"), 'activeUser' => lang("t_activeUserSta"), 'sessionNum' => lang("t_sessionsSta"), 'avgUsage' => lang("t_averageUsageDuration"));





        /***/


        if (isset($_GET['type']) && 'compare' == $_GET['type']) {
            $this -> _data['common'] = array('show_thrend' => 0, 'show_markevent' => 0);
        }
        /**/

        //var_dump( $this-> _data);  图表的 title  

        $this -> load -> view('layout/reportheader');         // 用户行为基本概括 图表
        $this -> load -> view('widgets/userbehavorview', $this -> _data);   
    }

    /**
     * GetUsersDataByTime
     *
     * @param string $productid productid
     *
     * @return void
     */
    function loadaddreport($productid)
    {
        $userid = $this -> common -> getUserId();
        $addreport = $this -> dashboard -> getaddreport($productid, $userid);
        if ($addreport) {
            $this -> _data['addreport'] = $addreport;
        }
    }

    /**
     * GetUsersDataByTime
     *
     * @return void
     */
    
    //  数用户行为基本概况  处理 方法
    function getUsersDataByTime()  
    {
        $currentProduct = $this -> common -> getCurrentProduct();   //获取当前应用的信息
        $fromTime = $this -> common -> getFromTime(); //获取存在session的开始时间
        $toTime = $this -> common -> getToTime();   //获取存在session的结束时间
        $ret = array();  


         // 不走if , 走下面的  
        if ($currentProduct == null) {
            $products = $this -> common -> getCompareProducts();
            if (count($products) < 1) {
                echo json_encode('redirecthome');
                return;
            }
            for ($i = 0; $i < count($products); $i++) {
                $query = $this -> newusermodel -> getallUserDataByPid($fromTime, $toTime, $products[$i] -> id);
                $ret[$i]['name'] = $products[$i] -> name;
                $ret[$i]['content'] = $query -> result_array();
            }
      
            echo "hello world";

            echo json_encode($ret);
            return;
        }

  

        $query = $this -> newusermodel -> getallUserData($fromTime, $toTime); 

        $ret["content"] = $query -> result_array();   //取得用户行为基本概况  数据

        //获取当前开始时间  再减去五天的数据
       // var_dump( $this -> common -> getPredictiveValurFromTime() );    
        $trendresult = $this -> newusermodel -> getallUserData($this -> common -> getPredictiveValurFromTime(), $toTime);
   
       
        //getPredictiveValur   处理 数据 - 5 天  并且值不是 字符型
        $result = $this -> gettrend -> getPredictiveValur($trendresult -> result_array());

        $ret["trendcontent"] = $result;  // 取得用户行为基本概况

        // diffBetweenTwoDays 自定义 算出 日期相差数
         //   var_dump( $this -> common -> diffBetweenTwoDays($fromTime,$toTime) );

       

        $ret["timeTick"] = $this -> common -> getTimeTick(  $this -> common -> diffBetweenTwoDays($fromTime,$toTime) ); // 计算日期 数
        //load markevent
        $this -> load -> model('point_mark', 'pointmark');    //加载 point_mark model
        // 参数 用户id  应用id   开始、结束 时间    
        // 作用 : 取出用户在  数据点上的 数据点标记
        $markevnets = $this -> pointmark -> listPointviewtochart($this -> common -> getUserId(), $currentProduct -> id, $fromTime, $toTime) -> result_array();

        $marklist = $this -> pointmark -> listPointviewtochart($this -> common -> getUserId(), $currentProduct -> id, $fromTime, $toTime, 'listcount');


        $ret['marklist'] = $marklist;
        $ret['markevents'] = $markevnets;

        //返回 处理日期当前列表
        $ret['defdate'] = $this -> common -> getDateList($fromTime, $toTime);


        //var_dump ( $ret );
        //返回 json 数据
        echo json_encode($ret);

    }
    /**
     * Exportdetaildata
     *
     * @return void
     */
    function exportdetaildata()
    {
        $fromTime = $this -> common -> getFromTime();
        $toTime = $this -> common -> getToTime();
        $currentProduct = $this -> common -> getCurrentProduct();
        $productName = trim($currentProduct -> name);


        $detaildata = $this -> newusermodel -> getDetailUserDataByDay($fromTime, $toTime);
        if ($detaildata != null && count($detaildata) > 0) {
            $data = $detaildata;
            $titlename = getExportReportTitle($productName, lang("v_rpt_pb_userDataDetail"), $fromTime, $toTime);
            $title = iconv("UTF-8", "GBK", $titlename);
            $this -> export -> setFileName($title);
            //Set the column headings
            $excel_title = array(iconv("UTF-8", "GBK", lang('g_date')), iconv("UTF-8", "GBK", lang('t_newUsers')), iconv("UTF-8", "GBK", lang('t_accumulatedUsers')), iconv("UTF-8", "GBK", lang('t_activeUsers')), iconv("UTF-8", "GBK", lang('t_sessions')), iconv("UTF-8", "GBK", lang('t_averageUsageDuration')));
            $this -> export -> setTitle($excel_title);
            //output content
            for ($i = 0; $i < count($data); $i++) {
                if($data[$i]['start'])
                $data[$i]['aver'] = round(($data[$i]['aver']/$data[$i]['start'])/1000,2);

                $data[$i]['aver'] = $data[$i]['aver'].lang('g_s');
                $row = $data[$i];
                $this -> export -> addRow($row);
            }
            $this -> export -> export();
            die();

        } else {
            $this -> load -> view("usage/nodataview");
        }
    }





    /**
     * ExportComparedata
     *
     * @return void
     */
    function exportComparedata()
    {
        $fromTime = $this -> common -> getFromTime();
        $toTime = $this -> common -> getToTime();
        $products = $this -> common -> getCompareProducts();
        if (empty($products)) {
            $this -> common -> requireProduct();
            return;
        }
        $this -> load -> library('export');
        $export = new Export();
        $titlename = getExportReportTitle("Compare", lang("v_rpt_pb_userDataDetail"), $fromTime, $toTime);
        $titlename = iconv("UTF-8", "GBK", $titlename);
        $export -> setFileName($titlename);
        $maxlength = 0;
        $labels = array(lang('t_newUsers'), lang('t_accumulatedUsers'), lang('t_activeUsers'), lang('t_sessions'), lang('t_averageUsageDuration'));
        $label = array('newusers', 'allusers', 'startusers', 'sessions', 'usingtime');
        for ($i = 0; $i < 5; $i++) {
            if ($i == 0) {
                $title[0] = iconv("UTF-8", "GBK", $labels[$i]);
                $title[1] = iconv("UTF-8", "GBK", lang('g_date'));
                for ($j = 0; $j < count($products); $j++) {
                    $detailData[$j] = $this -> newusermodel -> getallUserDataByPid($fromTime, $toTime, $products[$j] -> id) -> result_array();
                    if (count($detailData[$j]) > $maxlength) {
                        $maxlength = count($detailData[$j]);
                    }
                    $title[$j + 2] = iconv("UTF-8", "GBK", $products[$j] -> name);
                }
                $export -> setTitle($title);
            } else {
                $title[0] = $labels[$i];
                $title[1] = lang('g_date');
                for ($m = 0; $m < count($products); $m++) {
                    $title[$m + 2] = $products[$m] -> name;
                }
                $export -> addRow($title);
            }
            $this -> getExportRowData($export, $maxlength, $detailData, $products, $label[$i]);
        }
        $export -> export();
        die();
    }

    /**
     * GetExportRowData
     *
     * @param string $export   export
     * @param string $length   length
     * @param string $userData userData
     * @param string $products products
     * @param string $label    label
     *
     * @return void
     */
    function getExportRowData($export, $length, $userData, $products, $label)
    {
        $k = 0;
        for ($i = 0; $i < $length; $i++) {
            $result[$k++] = ' ';
            for ($j = 0; $j < count($products); $j++) {
                $obj = $userData[$j];
                if ($j == 0) {
                    $result[$k++] = substr($obj[$i]['datevalue'], 0, 10);
                }
                $currentdata = $obj[$i][$label];
                if ($label == "usingtime") {
                    if ($obj[$i]['sessions'] != 0) {
                        $currentdata = ($currentdata / $obj[$i]['sessions']) / 1000;
                        $currentdata = round($currentdata, 2);
                    }
                    $currentdata = $currentdata . lang('g_s');
                }
                $result[$k++] = $currentdata;
            }
            $export -> addRow($result);
            $k = 0;
        }
    }

    /**
     * Timephaseexport
     *
     * @param string $timePhase timePhase
     * @param string $fromDate  fromDate
     * @param string $toDate    toDate
     *
     * @return void
     */
    function timephaseexport($timePhase, $fromDate = '', $toDate = '')
    {
        $currentProduct = $this -> common -> getCurrentProduct();
        $time = $this -> changeDate($timePhase, $fromDate, $toDate);
        $fromTime = $time['fromTime'];
        $toTime = $time['toTime'];
        $query = $this -> product -> getStarterUserCountByTime($fromTime, $toTime, $currentProduct -> id);
        $detailcontent = $query -> result_array();
        if ($detailcontent != null && count($detailcontent) > 0) {

            $titlename = getExportReportTitle($currentProduct -> name, lang("v_rpt_pb_timeTrendOfUsers"), $fromTime, $toTime);
            $title = iconv("UTF-8", "GBK", $titlename);
            $this -> export -> setFileName($title);
            //Set the column headings
            $excel_title = array(iconv("UTF-8", "GBK", "时间"), iconv("UTF-8", "GBK", lang('t_activeUsers')), iconv("UTF-8", "GBK", lang('t_newUsers')));
            $this -> export -> setTitle($excel_title);
            //output content
            for ($i = 0; $i < count($detailcontent); $i++) {

                $row['hour'] = $detailcontent[$i]['hour'] . ":00";
                $row['startusers'] = $detailcontent[$i]['startusers'];
                $row['newusers'] = $detailcontent[$i]['newusers'];
                $this -> export -> addRow($row);
            }
            $this -> export -> export();
            die();
        } else {
            $this -> load -> view("usage/nodataview");
        }
    }

    /**
     * ExportComparePhaseusetime
     *
     * @param string $timePhase timePhase
     * @param string $fromDate  fromDate
     * @param string $toDate    toDate
     *
     * @return void
     */
    function exportComparePhaseusetime($timePhase, $fromDate = '', $toDate = '')
    {
        $time = $this -> changeDate($timePhase, $fromDate, $toDate);
        $fromTime = $time['fromTime'];
        $toTime = $time['toTime'];
        $products = $this -> common -> getCompareProducts();
        if (empty($products)) {
            $this -> common -> requireProduct();
            return;
        }
        $this -> load -> library('export');
        $export = new Export();
        $titlename = getExportReportTitle("Compare", lang("v_rpt_pb_timeTrendOfUsers_detail"), $fromTime, $toTime);
        $titlename = iconv("UTF-8", "GBK", $titlename);
        $export -> setFileName($titlename);
        $j = 0;
        $mk = 0;
        $maxlength = 0;
        $title[$j++] = iconv("UTF-8", "GBK", '');
        $space[$mk++] = lang('t_date_part');
        for ($i = 0; $i < count($products); $i++) {
            $detailData[$i] = $this -> product -> getStarterUserCountByTime($fromTime, $toTime, $products[$i] -> id) -> result_array();
            $maxlength = count($detailData[$i]);
            $title[$j++] = iconv("UTF-8", "GBK", $products[$i] -> name);
            $title[$j++] = iconv("UTF-8", "GBK", '');
            $space[$mk++] = lang('t_activeUsers');
            $space[$mk++] = lang('t_newUsers');
        }
        $export -> setTitle($title);
        $export -> addRow($space);
        $k = 0;
        $j = 0;
        for ($m = 0; $m < $maxlength; $m++) {
            $detailcontent = array();
            for ($j = 0; $j < count($products); $j++) {
                $obj = $detailData[$j];
                if ($j == 0) {
                    array_push($detailcontent, $obj[$m]['hour'] . ":00");
                }
                array_push($detailcontent, $obj[$m]['startusers']);
                array_push($detailcontent, $obj[$m]['newusers']);
            }
            $export -> addRow($detailcontent);
        }
        $export -> export();
        die();
    }

    /**
     * ChangeDate
     *
     * @param string $timePhase timePhase
     * @param string $fromDate  fromDate
     * @param string $toDate    toDate
     *
     * @return array
     */
    function changeDate($timePhase, $fromDate = '', $toDate = '')
    {
        $toTime = date('Y-m-d', time());
        if ($timePhase == "today") {
            $fromTime = date('Y-m-d', time());
            $toTime = date('Y-m-d', time());
        }
        if ($timePhase == "yestoday") {
            $fromTime = date("Y-m-d", strtotime("-1 day"));
            $toTime = date('Y-m-d', strtotime("-1 day"));
        }

        if ($timePhase == "last7days") {
            $fromTime = date("Y-m-d", strtotime("-6 day"));
        }
        if ($timePhase == "last30days") {
            $fromTime = date("Y-m-d", strtotime("-31 day"));
        }
        if ($timePhase == "any") {
            $fromTime = $fromDate;
            $toTime = $toDate;
        }
        return array('fromTime' => $fromTime, 'toTime' => $toTime);
    }

}
?>