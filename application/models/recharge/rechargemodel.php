<?php
/**
*
* 订单充值统计
*
*/
class Rechargemodel extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }

    //返回订单查询数据
    function getnewlist($fromTime,$toTime,$productid)
    {

        $data   = $this->getorderdata($fromTime,$toTime,$productid);
        $server = array();
        foreach ($data as $key => $value) 
        {
            $server[$key]['date'] = substr($value['rechargedate'],0,10);
            $server[$key]['time'] = substr($value['rechargedate'],10);
            $server[$key]['uid'] = $value['user_id'];
            $server[$key]['price'] = $value['money'];
            $server[$key]['ordertype'] = $value['imp'];
            $server[$key]['role_id']    =$value['role_id'];
        }
        return  $server;
    }

    //查询订单数据
    function  getorderdata($fromTime,$toTime,$productid)
    {

            $dwdb = $this->load->database('dw',true);
            $sql  = "
                    SELECT  *  from "  . $dwdb->dbprefix('order_info').  "  
                    WHERE  rechargedate  BETWEEN  '$fromTime'  and '$toTime'
                    and  app_id = ".$productid." 
                    ORDER BY rechargedate   desc";
            $query = $dwdb->query($sql);
            //echo $dwdb->last_query();
            $orderinfo = $query->result_array();  //返回对象类型
            return $orderinfo;

    }




}