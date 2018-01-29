<?php
/**
 * Cobub Razor
 *
 * An open source analytics for mobile applications
 *
 * @package		Cobub Razor
 * @author		WBTECH Dev Team
 * @copyright	Copyright (c) 2011 - 2012, NanJing Western Bridge Co.,Ltd.
 * @license		http://www.cobub.com/products/cobub-razor/license
 * @link		http://www.cobub.com/products/cobub-razor/
 * @since		Version 1.0
 * @filesource
 */
class Common extends CI_Model {
    function __construct() {
        parent::__construct();
        $this -> load -> library('session');
        $this -> load -> helper('url');
        $this -> load -> library('tank_auth');
        $this -> load -> library('ums_acl');
        $this -> load -> library('export');
        $this->load->model('pluginm');
        $this -> load -> database();
    }

    function getdbprefixtable($name) {
        $tablename = $this -> db -> dbprefix($name);
        return $tablename;
    }

    function getMaxY($count) {
        if ($count <= 5) {
            return 5;
        } else {
            return $count + $count / 10;
        }
    }



//处理时间差
function diffBetweenTwoDays($day1, $day2)
{
  $second1 = strtotime($day1);
  $second2 = strtotime($day2);
   
  if ($second2 < $second1) {
    $tmp = $second2;
    $second2 = $second1;
    $second1 = $tmp;
  }
  return ($second2 - $second1) / 86400;
}


    function getTimeTick($days) {


        if ($days <= 7) {
            return 1;
        }

        if ($days > 7 && $days <= 30) {
            return 2;
        }

        if ($days > 30 && $days <= 90) {
            return 10;
        }

        if ($days > 90 && $days <= 270) {
            return 30;
        }

        if ($days > 270 && $days <= 720) {
            return 70;
        }
        return 1;
    }

    function getStepY($count) {
        if ($count <= 5) {
            return 1;
        } else {
            return round($count / 5, 0);
        }
    }

    function loadCompareHeader($viewname = "", $showDate = TRUE) {
        $this -> load -> model('product/productmodel', 'product');
        $this -> load -> helper('cookie');
        if (!$this -> common -> isUserLogin()) {
            $dataheader['login'] = false;
            $this -> load -> view('layout/compareHeader', $dataheader);
        } else {
            $dataheader['user_id'] = $this -> getUserId();
            $dataheader['pageTitle'] = lang("c_" . $this -> router -> fetch_class());
            if ($this -> isAdmin()) {
                $dataheader['admin'] = true;
            }
            $dataheader['login'] = true;
            $dataheader['username'] = $this -> getUserName();
            $product = $this -> getCurrentProduct();
            if (isset($product) && $product != null) {
                $dataheader['product'] = $product;
                log_message("error", "HAS Product");
            }

            $productList = $this -> product -> getAllProducts($dataheader['user_id']);
            if ($productList != null && $productList -> num_rows() > 0) {
                $productInfo = array();
                $userId = $this -> getUserId();
                foreach ($productList->result() as $row) {
                    if (!$this -> product -> isAdmin() && !$this -> product -> isUserHasProductPermission($userId, $row -> id)) {
                        continue;
                    }
                    $productObj = array('id' => $row -> id, 'name' => $row -> name);
                    array_push($productInfo, $productObj);
                }
                if (count($productInfo) > 0) {
                    $dataheader["productList"] = $productInfo;
                }
            }
            $products = $this -> getCompareProducts();
            $dataheader['products'] = $products;
            log_message("error", "Load Header 123");
            $dataheader['language'] = $this -> config -> item('language');
            $dataheader['viewname'] = $viewname;
            if ($showDate) {
                $dataheader["showDate"] = true;
            }
            $this -> load -> view('layout/compareHeader', $dataheader);
        }
    }

    function checkCompareProduct() {
        $products = $this -> common -> getCompareProducts();
        if (isset($_GET['type']) && 'compare' == $_GET['type']) {
            if (empty($products) || count($products) < 2 || count($products) > 4) {
                redirect(base_url());
            }
        } else {
        }
    }

    function loadHeaderWithDateControl($viewname = "") {
        $this -> loadHeader($viewname, TRUE);
    }

    function loadHeader($viewname = "", $showDate = FALSE) {
        $this -> load -> model('interface/getnewversioninfo', 'getnewversioninfo');
        $this -> load -> model('product/productmodel', 'product');
        $this -> load -> model('pluginlistmodel','plugin');
        $this -> load -> helper('cookie');
        if (!$this -> common -> isUserLogin()) {

           // echo "hello";
            $dataheader['login'] = false;
            $version = $this -> config -> item('version');
            $versiondata = $this -> getnewversioninfo -> newversioninfo($version);
            if ($versiondata) {
                $dataheader['versionvalue'] = $versiondata['version'];
                $dataheader['version'] = $versiondata['updateurl'];
            }
            $inform = $this -> session -> userdata('newversion');
            if ($inform == "noinform") {
                $dataheader['versioninform'] = $inform;
            }
            $this -> load -> view('layout/header', $dataheader);
        } else {
  
        //   不显示查询时间
        //     echo "world";  走这里  统计充值数据
            $dataheader['user_id'] = $this -> getUserId();
            $dataheader['pageTitle'] = lang("c_" . $this -> router -> fetch_class());
            if ($this -> isAdmin()) {
                $dataheader['admin'] = true;
            }
            $dataheader['login'] = true;
            $dataheader['username'] = $this -> getUserName();
            $product = $this -> getCurrentProduct();
            if (isset($product) && $product != null) {
                $dataheader['product'] = $product;
                log_message("error", "HAS Product");
            }

            $productList = $this -> product -> getAllProducts($dataheader['user_id']);
            if ($productList != null && $productList -> num_rows() > 0) {
                $productInfo = array();
                $userId = $this -> getUserId();
                foreach ($productList->result() as $row) {
                    if (!$this -> product -> isAdmin() && !$this -> product -> isUserHasProductPermission($userId, $row -> id)) {
                        continue;
                    }
                    $productObj = array('id' => $row -> id, 'name' => $row -> name);
                    array_push($productInfo, $productObj);
                }
                if (count($productInfo) > 0) {
                    $dataheader["productList"] = $productInfo;
                }
            }
            log_message("error", "Load Header 123");
            $dataheader['language'] = $this -> config -> item('language');
            $dataheader['viewname'] = $viewname;
            if ($showDate) {                                                  
                $dataheader["showDate"] = true;
            }
            $version = $this -> config -> item('version');
            $versiondata = $this -> getnewversioninfo -> newversioninfo($version);
            if ($versiondata) {
                $dataheader['versionvalue'] = $versiondata['version'];
                $dataheader['version'] = $versiondata['updateurl'];
            }
            $dataheader['versionvalue'] = $versiondata['version'];
            $dataheader['version'] = $versiondata['updateurl'];
            $inform = $this -> session -> userdata('newversion');
            if ($inform == "noinform") {
                $dataheader['versioninform'] = $inform;
            }
           
            
            $this->load->model ( 'pluginlistmodel' );
            	
            $userId = $this -> getUserId();
            $userKeys = $this->pluginlistmodel->getUserKeys ( $userId );
            if ($userKeys) {
            	$dataheader['key'] = $userKeys->user_key;
            	$dataheader['secret'] = $userKeys->user_secret;
            }

           // var_dump($dataheader);
            $this -> load -> view('layout/header', $dataheader);



        }
    }

    

    function show_message($message) {  
        $this -> session -> set_userdata('message', $message);
        redirect('/auth/');
    }

    function requireLogin() {   //判断是否登录
        if (!$this -> tank_auth -> is_logged_in()) {
            redirect('/auth/login/');
        }
    }

    function requireProduct() {  //判断是否有这个应用 没有 就是跳转到首页
        $product = $this -> getCurrentProduct();   //获取当前应用信息
        if (empty($product)) {
            redirect(site_url());
        } else {
            $userId = $this -> getUserId();
            $productId = $product -> id;
            $this -> checkUserPermissionToProduct($userId, $productId);
        }
    }

    function checkUserPermissionToProduct($userId, $productId) {
        $this -> load -> model('product/productmodel', 'product');
        if (!$this -> isAdmin() && !$this -> product -> isUserHasProductPermission($userId, $productId)) {
            $this -> session -> set_userdata('message', "You don't have permission to view this product.");
            redirect(site_url());
        }
    }

    function isAdmin() {
        $userid = $this -> tank_auth -> get_user_id();
        $role = $this -> getUserRoleById($userid);
        if ($role == 3) {
            return true;
        }
        return false;
    }

    function getUserId() {
        return $this -> tank_auth -> get_user_id();
    }

    function getUserName() {
        return $this -> tank_auth -> get_username();
    }

    function isUserLogin() {
        return $this -> tank_auth -> is_logged_in();
    }

    function canRead($controllerName) {
        $id = $this -> getResourceIdByControllerName($controllerName);
        if ($id) {
            $acl = new Ums_acl();
            $userid = $this -> tank_auth -> get_user_id();
            $role = $this -> getUserRoleById($userid);
            if ($acl -> can_read($role, $id)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function getPageTitle($controllerName) {
        $this -> db -> where('name', $controllerName);
        $query = $this -> db -> get('user_resources');
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> first_row() -> description;
        }
        return "";
    }

    // private functiosn
    function getResourceIdByControllerName($controllerName) {
        $this -> db -> where('name', $controllerName);
        $query = $this -> db -> get('user_resources');
        if ($query != null && $query -> num_rows() > 0) {
            return $query -> first_row() -> id;
        }
        return null;
    }

    function getUserRoleById($id) {
        if ($id == '')
            return '2';
        $this -> db -> select('roleid');
        $this -> db -> where('userid', $id);
        $query = $this -> db -> get('user2role');
        $row = $query -> first_row();
        if ($query -> num_rows > 0) {
            return $row -> roleid;
        } else
            return '2';
    }

    function getCurrentProduct() {
        // 取出存入的 当前应用的 session 
        $currentProduct = $this -> session -> userdata("currentProduct"); 


        if ($currentProduct) {
            $userId = $this -> getUserId();
            $productId = $currentProduct -> id;
            $this -> checkUserPermissionToProduct($userId, $productId);
        }
        return $currentProduct;
    }

    function getCurrentProductIfExist() {
        $currentProduct = $this -> session -> userdata("currentProduct");
        if (isset($currentProduct)) {
            return $currentProduct;
        } else {
            return false;
        }
    }

    //清除当前应用的session
    function cleanCurrentProduct() {
        $this -> session -> unset_userdata("currentProduct");
    }

    //根据应用id  重新存入新的应用信息
    function setCurrentProduct($productId) {
        $this -> load -> model('product/productmodel', 'product');
        $currentProduct = $this -> product -> getProductById($productId);
        $this -> session -> set_userdata("currentProduct", $currentProduct);
    }

    function setCompareProducts($productIds = array()) {    
        $this -> session -> set_userdata('compareProducts', $productIds);
    }


    //取出  compareProducts  的数据
    function getCompareProducts() {
        $this -> load -> model('product/productmodel', 'product');
        $pids = $this -> session -> userdata("compareProducts");
        $products = array();
        for ($i = 0; $i < count($pids); $i++) {
            $product = $this -> product -> getProductById($pids[$i]);
            $products[$i] = $product;
        }
        return $products;
    }




    function changeTimeAll($pase,$from,$to){


         switch ($pase) {
        case "today":
                {
                    //    今天开始时间筛选
                    //    今天结束时间筛选
                            $year = date("Y");
                            $month = date("m");
                            $day = date("d");
                            $fromTime =  date('Y-m-d H:i:s',  mktime(0,0,0,$month,$day,$year));
                            $toTime = date('Y-m-d H:i:s', time());

                }
                break;
        case "anythin":
                {
                     $fromTime = $from; //   任意开始时间筛选
                     $toTime = $to;     //   任意结束时间筛选
                     $fromTime = date('Y-m-d H:i:s',  $fromTime);   //先测试数据正确否
                     $toTime = date('Y-m-d H:i:s',   $toTime);
                }
                break;
   
            default :
                {
                 //   $fromTime = date("Y-m-d", strtotime("-6 day"));     //默认7天之前 到今天
                            $year = date("Y");
                            $month = date("m");
                            $day = date("d");
                            $fromTime =  date('Y-m-d H:i:s',  mktime(0,0,0,$month,$day,$year));   
                            $toTime = date('Y-m-d H:i:s', time());
                }
                break;
            
            }


              //如果  开始时间 大于 结束时间   那么就互换

        if ($fromTime > $toTime) {
            $tmp = $toTime;
            $toTime = $fromTime;
            $fromTime = $tmp;
        }

          //var_dump($fromTime);   //得到要筛选时间
         //var_dump($toTime);    // 现在的时间

        $this -> session -> set_userdata('fromTimeall', $fromTime); //存入 session中
        $this -> session -> set_userdata('toTimeall', $toTime);      //存入 session中 



    }


    function changeTimeSegment($pase, $from, $to) {
   
        $this -> load -> model('product/productmodel', 'product');  //载入应用信息
        $toTime = date('Y-m-d', time());    //现在的时间

        // var_dump($toTime); 
        // var_dump($pase);
        //$pase     选择筛选的时间
        //$from     自定义选择筛选的开始时间
        //$to       自定义选择筛选的结束时间
        switch ($pase) {
            case "7day" :
                {
                    $fromTime = date("Y-m-d", strtotime("-6 day"));  // 默认选择的时间  7day  
                }
                break;
            case "1month" :
                {
                    $fromTime = date("Y-m-d", strtotime("-31 day"));  // 默认选择的时间  31day
                }
                break;
            case "3month" :
                {
                    $fromTime = date("Y-m-d", strtotime("-92 day")); // 默认选择的时间  92day     
                }
                break;
            case "all" :    //查询所有时间   开始时间 默认是  应用新建时间
                {
                    $currentProduct = $this -> getCurrentProductIfExist();   //取出存入当前的 currentProduct session的值

                     //  var_dump($currentProduct);   

                    if ($currentProduct) {   // 判断正确

                        $fromTime = $this -> product -> getReportStartDate($currentProduct, '1970-02-01');      //传入当前的应用信息  

                       // var_dump($fromTime); 接收到当前应用时间

                        $fromTime = date("Y-m-d", strtotime($fromTime));  

                      // var_dump($fromTime);  只要  年 月 日

                    } else {
                        $fromTime = $this -> product -> getUserStartDate($this -> getUserId(), '1970-01-01');
                        $fromTime = date("Y-m-d", strtotime($fromTime));
                    }
                }
                break;
            case "any" :
                {
                    $fromTime = $from; //   任意开始时间筛选
                    $toTime = $to;     //   任意结束时间筛选
                }
                break;

   
            default :
                {
                    $fromTime = date("Y-m-d", strtotime("-6 day"));     //默认7天之前 到今天
                }
                break;
        }


        //如果  开始时间 大于 结束时间   那么就互换

        if ($fromTime > $toTime) {
            $tmp = $toTime;
            $toTime = $fromTime;
            $fromTime = $tmp;
        }

        //   var_dump($fromTime);   //得到要筛选时间
        //  var_dump($toTime);    // 现在的时间

        $this -> session -> set_userdata('fromTime', $fromTime); //存入 session中
        $this -> session -> set_userdata('toTime', $toTime);      //存入 session中 
    }


//取出没有时分秒的日期
    function getFromTime() {
        $fromTime = $this -> session -> userdata("fromTime");  //从session 中取出  开始搜索时间

            //  var_dump( $fromTime );

        if (isset($fromTime) && $fromTime != null && $fromTime != "") {
            return $fromTime; //如果变量存在  走这里
        }
        $fromTime = date("Y-m-d", strtotime("-6 day"));  
        return $fromTime;
    }


//取出带有时分秒的日期
//
    function getFromTimeall() {

        $fromTime = $this -> session -> userdata("fromTimeall");  //从session 中取出  开始搜索时间

         

        if (isset($fromTime) && $fromTime != null && $fromTime != "") {
            return $fromTime; //如果变量存在  走这里
        }


        //第一次没有 session 默认进这里
                            $year = date("Y");
                            $month = date("m");
                            $day = date("d");
                            $fromTime =  date('Y-m-d H:i:s',  mktime(0,0,0,$month,$day,$year));   
                            return $fromTime;
    }



    function getToTimeall() {          
        $toTime = $this -> session -> userdata("toTimeall");  //从session 中取出  现在时间

      

        if (isset($toTime) && $toTime != null && $toTime != "") {
            return $toTime;   //如果变量存在  走这里
        }

     
        //第一次没有 默认进这里
        $toTime = date('Y-m-d H:i:s', time());
        return $toTime;
    }
   /*_______________________________*/ 



     //获取当前开始时间  再减去五天的数据
    function getPredictiveValurFromTime() {    
        $time = $this -> getFromTime();   

        $fromTime = date("Y-m-d", strtotime("$time -5 day"));
        return $fromTime;
    }

    function getToTime() {          
        $toTime = $this -> session -> userdata("toTime");  //从session 中取出  现在时间

        //var_dump( $toTime );

        if (isset($toTime) && $toTime != null && $toTime != "") {
            return $toTime;   //如果变量存在  走这里
        }

        $toTime = date('Y-m-d', time());


        return $toTime;
    }

//返回 处理日期当前列表
    function getDateList($from, $to) {
        $defdate = array();
        for ($i = strtotime($from); $i <= strtotime($to); $i += 86400) {
            if (!in_array(date('Y-m-d', $i), $defdate))
                array_push($defdate, date('Y-m-d', $i));
        }
        return $defdate;
    }

    function export($from, $to, $data) {
        $productId = $this -> getCurrentProduct() -> id;
        $productName = $this -> getCurrentProduct() -> name;

        $export = new Export();

        $export -> setFileName($productName . '_' . $from . '_' . $to . '.xls');

        $fields = array();
        foreach ($data->list_fields () as $field) {
            array_push($fields, $field);
        }
        $export -> setTitle($fields);

        foreach ($data->result () as $row)
            $export -> addRow($row);
        $export -> export();
        die();
    }
    
    function curl_post($url, $vars) {
    	$ch = curl_init();
    	curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
    	curl_setopt($ch, CURLOPT_URL,$url);
    	curl_setopt($ch, CURLOPT_POST, 1 );
    	curl_setopt($ch, CURLOPT_HEADER, 0 ) ;
    	curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
    	$response = curl_exec($ch);
    	curl_close($ch);
    	
    	if ($response)
    	{
    		return $response;
    	}
    	else
    	{
    		return false;
    	}
    }

}
