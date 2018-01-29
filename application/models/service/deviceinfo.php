<?php
/**
* Deviceinfo class
*/
class Deviceinfo extends CI_Model
{

    function __construct()
    {
      parent::__construct();
    }

    /**
    *   添加设备信息
    */
    function addDeviceData($postData)
    {


        $dwdb         =  $this->load->database('dw', true);
        $time         = $postData->timestamp->activate_ts / 1000;
        $date_sk      =  $this->getdatesk(  date('Y-m-d', $time ) );
        $actime       =   date('Y-m-d H:i:s', $time);
        $getdeviceid  =  $this->getdeviceid( $postData->device_info->device_id );
        $nowtime      = date('Y-m-d H:i:s');
        $app_id       =  $this->getProductappid( $postData->app_info->key); //应用id
        $data         = array(
        'device_id'           => isset($postData->device_info->device_id) ? $postData->device_info->device_id : '',
        'date_sk'             =>  isset($date_sk) ? $date_sk : '',
        'device_model'        => isset($postData-> device_info ->device_model) ? $postData-> device_info -> device_model : '',
        'device_board'        => isset($postData-> device_info ->device_board) ? $postData-> device_info -> device_board : '',
        'device_brand'        => isset($postData-> device_info ->device_brand) ? $postData-> device_info -> device_brand : '',
        'device_manutime'     => isset($postData-> device_info ->device_manutime) ? $postData-> device_info -> device_manutime : '0',
        'device_manufacturer' => isset($postData-> device_info ->device_manufacturer) ? $postData-> device_info -> device_manufacturer : '',
        'device_manuid'       => isset($postData-> device_info ->device_manuid) ? $postData-> device_info -> device_manuid : '',
        'device_name'         => isset($postData-> device_info ->device_name) ? $postData-> device_info -> device_name : '',
        'cpu'                 => isset($postData-> device_info ->cpu) ? $postData-> device_info ->cpu : '',
        'resolution'          => isset($postData-> device_info ->resolution) ? $postData-> device_info -> resolution : '',
        'mac_address'         => isset($postData-> device_info ->mac_address) ? $postData-> device_info -> mac_address : '',
        'os_version'          => isset($postData-> device_info ->os_version) ? $postData-> device_info -> os_version : '',
        'os_language'         => isset($postData-> device_info ->os_language) ? $postData-> device_info -> os_language : '',
        'os_timezone'         => isset($postData-> device_info ->os_timezone) ? $postData-> device_info -> os_timezone : '',
        'app_key'             => isset($postData-> app_info->key) ? $postData-> app_info -> key : '',
        'version'             => isset($postData-> app_info->version) ? $postData-> app_info -> version : '',
        'activity_time'       => isset($actime) ? $actime : '',
        'insertdate'          => $nowtime
                          );
      //验证key存在
      if( $app_id != null)
      { 
          //如果设备存在 则不在增加
          if( $getdeviceid  ==  null )
          {
              $dwdb ->insert('device_info', $data);
          }
      }

    }




     //获取datesk  
    function  getdatesk($date)
    {
        $dwdb =  $this->load->database('dw', true);
        $query = $dwdb->query("select  date_sk  from " .$dwdb->dbprefix('dim_date') . " where datevalue = '$date'");
        if ($query != null && $query->num_rows() > 0){
        return $query->first_row()->date_sk;
        }
        return null;
    }



    //查询是否有重复的设备id 、如果有则不在次写入
    function  getdeviceid($id)
    {
        $dwdb =  $this->load->database('dw', true);
        $query = $dwdb->query("select  device_id  from " .$dwdb->dbprefix('device_info') . " where device_id = '$id'");
        if ($query != null && $query->num_rows() > 0) {
        return $query->first_row()->device_id;
        }
        // echo $dwdb -> last_query();
        return null;
    }


    //验证key 不存在 则不写入
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







}
?>
