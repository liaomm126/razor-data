<?php 
/*
* Gradedata class  用户等级分布
*/
class Gradedata extends CI_Model
{
    
    function __construct()
    {
      parent::__construct();
    }

    /**
    * addData  添加用户等级分布数据
    */
    function addData($postData)
    {     

        $data = json_decode($postData);
        $dwdb =  $this->load->database('dw', true);
        $data = array(
            'lvinfo'    =>  isset( $postData )   ?  $postData : '',
            'lvtime'    =>  isset( $data->level_info->t)  ?  $data->level_info->t : '',
            'sid'       =>  isset( $data->level_info->sid) ? $data->level_info->sid : ''
        ); 
    
      $dwdb -> insert('lv_info',$data);

    }





}