<?php
/**
* RegeisterData class 账号信息处理
*/
class RegeisterData extends CI_Model
{
    
    function __construct()
    {
        parent::__construct();
    }

    /**
    * addData
    */
    function addRegisterData($postData)
    {

        $dwdb    =  $this->load->database('dw', true);
        $app_id  =  $this->getProductappid( $postData->app_info->key); //应用id

        $time    =  $postData->timestamp->register_ts / 1000;
        $date_sk =  $this->getdatesk(  date('Y-m-d', $time ) );
        $regts   =   date('Y-m-d H:i:s', $time );

        $uid = $this->getuid( $postData->user_info->uid );
        $nowtime = date('Y-m-d H:i:s');
        $data = array(
        'user_id' => isset($postData->user_info->uid ) ? $postData->user_info->uid :'',
        'device_id' => isset($postData->device_info->device_id) ? $postData->device_info->device_id:'',
        'date_sk' =>  isset($date_sk) ? $date_sk : '',
        'app_id' => isset($app_id) ? $app_id : '',
        'sid' => isset( $postData->user_info->sid ) ? $postData->user_info->sid : '',
        'create_register_time' => isset($regts) ?  $regts : '',
        'insertdate' => $nowtime,
        );
        if($uid == null)
        {
            $dwdb->insert('register_info', $data);
        }
    }
 


    //获取时间对应的编码
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

    //获取key对应用的id
    function getProductappid($key)
    {
        $db =  $this->load->database('default', true);
        $query = $this->db->query("select product_id from " . $this->db->dbprefix('channel_product') . " where productkey = '$key'");
        if ($query != null && $query->num_rows() > 0) 
        {
            return $query->first_row()->product_id;
        }
        return null;
    }

    //查询userid 是否存在
    function  getuid($uid)
    {
        $dwdb =  $this->load->database('dw', true);
        $query = $dwdb->query("select  user_id  from " .$dwdb->dbprefix('register_info') . " where user_id = '$uid'");
        if ($query != null && $query->num_rows() > 0) 
        {
            return $query->first_row()->user_id;
        }
        // echo $dwdb -> last_query();
        return null;
    }   



}
?>
