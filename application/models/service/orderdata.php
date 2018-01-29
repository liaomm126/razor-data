<?php
/*
* OrderData class
*/
class Orderdata extends CI_Model
{


    function __construct()
    {
      parent::__construct();
    }

    /**
    * addData
    *
    * @param array $data json data
    *
    * @return void
    */
    function addData($postData)
    {

        $app_id  =  $this->getProductappid( $postData->app_info->key);
        $uid =   $postData->user_info->uid ;
        $rid =   $postData->user_info->rid ;
        $sid =   $postData->user_info->rid ;
        $did =   $postData->device_info->device_id;
        if( $uid == 0  &&  $rid == 0  &&  $sid ==0 )
        {
            //获取id定数据
            $result  =  $this->getidinfo( $did );
            $postData->user_info->uid  =   $result['uid'];
            $postData->user_info->rid  =   $result['rid'];
            $postData->user_info->sid  =   $result['sid'];
        }
        $dwdb =  $this->load->database('dw', true);
        $date_sk = $this->getdatesk( date('Y-m-d', $postData->timestamp ->recharge_ts / 1000) );
        $data = array(
            'device_id' => isset($postData->device_info->device_id) ? $postData->device_info->device_id:'',
            'sid' => isset($postData->user_info->sid) ? $postData->user_info->sid : '',
            'user_id' => isset($postData->user_info->uid) ? $postData->user_info->uid : 0,
            'role_id' => isset($postData->user_info->rid) ? $postData->user_info->rid : 0,
            'role_name' => isset($postData->order_info->rname) ? $postData->order_info->rname : '',
            'imp'	=>  isset($postData->order_info->imp) ? $postData->order_info->imp : '',
            'money'	=>  isset($postData->order_info->money) ? $postData->order_info->money : '',
            'diamonds'	=>  isset($postData->order_info->diamonds) ? $postData->order_info->diamonds : '',
            'rechargedate'	=>  isset($postData->timestamp->recharge_ts) ? date('Y-m-d H:i:s' , $postData->timestamp->recharge_ts / 1000 ): '',
            'date_sk' =>  isset($date_sk) ? $date_sk : '',
            'productid' => isset($postData->order_info->productid) ? $postData->order_info->productid : '',
            'app_id'       => isset( $app_id ) ?  $app_id : ''
        );

        $result	=  $dwdb -> insert('order_info',$data);
    }


    //效验appid
    function getProductappid($key)
    {

        $db =  $this->load->database('default', true);
        $query = $db->query("select product_id from " . $db->dbprefix('channel_product') . " where productkey = '$key'");
        if ($query != null && $query->num_rows() > 0)
        {
          return $query->first_row()->product_id;
        }
        return null;
    }


    //获取日期节点
    function  getdatesk($date)
    {

        $dwdb =  $this->load->database('dw', true);
        $query = $dwdb->query("select  date_sk  from " .$dwdb->dbprefix('dim_date') . " where datevalue = '$date'");
        if ($query != null && $query->num_rows() > 0)
        {
          return $query->first_row()->date_sk;
        }
        return null;
    }


    //更新order表里uid为0的记录
    function getidinfo($did)
    {

        $dwdb   =  $this->load->database('dw', true);
        $from   =  date('Y-m-d',  time() );
        $tofrom =  date('Y-m-d',  strtotime("+1 day",time() )  );
        $sql = "
                SELECT
                COUNT(device_id) AS did,
                user_id as uid,
                role_id as rid,
                sid
                FROM
                "  . $dwdb->dbprefix('role_info')  . "  ro
                WHERE
                ro.create_role_time  BETWEEN  '2016-07-27'  AND '2016-07-28'
                AND device_id = '".$did."'
                GROUP BY
                device_id
                HAVING
                did = 1 ";
        $query   =  $dwdb->query($sql);
        //echo  $dwdb ->last_query();
        $result  =   $query->row_array();
        return  $result;
    }


}