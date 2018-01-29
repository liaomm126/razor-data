<?php
/**
* Grademodel
*
* 统计等级分布模型
*
*/
class Grademodel extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }


    function getnewlist($fromtime,$totime,$sid)
    {

        $lvinfo  =   $this-> getlvinfo($fromtime,$totime,$sid);      // 新增数据统计
        $newdata = json_decode( $lvinfo['lvinfo'],true);
        //为空处理
        if($newdata == null )
        {
          $json =   '{"level_info":{"d":[],"c":0} }';
          return json_decode( $json,true);
        }

        return $newdata;
     }



    //获取等级信息
    function  getlvinfo($fromtime,$totime,$sid)
    {
        $dwdb = $this->load->database('dw', true);
        $sql  =   "
        SELECT  lvinfo  FROM  ".  $dwdb->dbprefix('lv_info ')  ." WHERE  id =  (  SELECT max(id) AS id  FROM
        " .  $dwdb->dbprefix('lv_info ') . "
        WHERE
        lvtime BETWEEN '$fromtime' and  '$totime'  AND  sid = '$sid'
        )";
        $query = $dwdb->query($sql);
        //echo $dwdb -> last_query();
        $lvinfo = $query-> row_array();  //返回数组类型
        if( $lvinfo )
        { 
            return  $lvinfo;
        } 
    }   





      
}