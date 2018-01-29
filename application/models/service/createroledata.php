<?php
/**
 *创建角色
 *
 */
class CreateRoleData extends CI_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
    * addData
    */
    function addData($postData)
    {

        //检测设备id
        $changedid  = $this->changedid($postData->user_info->uid, $postData->device_info->device_id); 
        $dwdb      =  $this->load->database('dw', true);                                       
        $app_id    =  $this->getProductappid( $postData->app_info->key);                                    //应用id
        $nowtime   = date('Y-m-d H:i:s');                                                                  //插入记录时间
        $time = $postData->timestamp->create_role_ts / 1000 ;
        $date_sk =  $this->getdatesk(  date('Y-m-d', $time)  );
        $createroletime =  date( 'Y-m-d H:i:s', $time );
    
        $rid       =  $postData->user_info->rid. $postData->user_info->uid.$postData->user_info->sid  ;  //组合rid
        $getrusid  =  $this->getrusid( $rid );                                                          //验证rid是否存在,不存在则插入记录 
        $data      =  array(
        'role_id'           => isset($postData->user_info->rid) ? $postData->user_info->rid :'',                                //角色id
        'device_id'         => isset($postData->device_info->device_id) ? $postData->device_info->device_id:'',                 //设备id
        'date_sk'           => isset($date_sk) ? $date_sk : '',                     //编码              
        'user_id'           => isset($postData->user_info->uid) ? $postData->user_info->uid :'',                                //用户id
        'app_id'            => isset( $app_id ) ?  $app_id : '',                                                                //应用id
        'sid'               => isset( $postData->user_info->sid ) ? $postData->user_info->sid : '',                             //服务器id
        'rus_id'            => $postData->user_info->rid. $postData->user_info->uid.$postData->user_info->sid ,   //组和rusid
        'create_role_time'  => isset($createroletime) ?  $createroletime : '',  
        ); 
        //返回记录为null  则写入进去
        if (  $getrusid   ==  null  ) 
        {
          $dwdb ->insert('role_info', $data);
        }

    }
    


    // 判断rus_id 是否已存在
    function  getrusid($rid)
    {
        $dwdb  =  $this->load->database('dw', true);
        $query =  $dwdb->query("select  rus_id  from " .$dwdb->dbprefix('role_info') . " where rus_id = '$rid'");
        if ($query != null && $query->num_rows() > 0) 
        {
            return $query->first_row()->rus_id;
        }
        return null;
    }


    // 返回对应的 date_sk
    function  getdatesk($date)
    {
        $dwdb  =  $this->load->database('dw', true);
        $query =  $dwdb->query("select  date_sk  from " .$dwdb->dbprefix('dim_date') . " where datevalue = '$date'");
        if ($query != null && $query->num_rows() > 0) 
        {
            return $query->first_row()->date_sk;
        }
        return null;
    }


    // 返回对应的 应用id
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



    //更新设备id
    function changedid($uid,$did)
    {

        $dwdb  =  $this->load->database('dw', true);
        if( $did  !=  '61566302695580912' )
        {

            $sql = "SELECT
                    *
                    FROM 
                    " .$dwdb->dbprefix('register_info') . " re
                    WHERE re.user_id = '$uid'
                    AND   re.device_id  != '61566302695580912' "; 
            $query = $dwdb->query($sql);
            if ($query != null && $query->num_rows() > 0) 
            {
                
                $sql = "UPDATE " .$dwdb->dbprefix('register_info') . " re
                        SET  device_id = '$did'
                        WHERE   user_id = '$uid'  ";
                $query  =   $dwdb->query( $sql );
            }

        }
    }









}
?>
