<?php
/**
 * Sessions class  在线用户时长统计
 */
class Sessions extends CI_Model
{
    
    function __construct()
    {
        parent:: __construct();
        $this->load->database();
    }

    //添加用户时长记录
    function addData($postData)
    {
        $dwdb =  $this->load->database('dw', true);
        $sessions = array();
        if (is_array( $postData->sessions )) 
        {
            foreach ($postData->sessions as $key => $value) 
            {
                $sessions[$key]['id'] = isset($value->id) ? $value->id:'';
                $sessions[$key]['start_time'] =date('Y-m-d H:i:s' , $value->start_time / 1000 );
                $sessions[$key]['end_time'] = date('Y-m-d H:i:s' , $value->end_time / 1000 );
                $sessions[$key]['duration'] = isset($value->duration) ? $value->duration:'';
                $sessions[$key]['hour_sk'] = $this->gethoursk( $sessions[$key]['start_time'] );    
                $data = array(
                'device_id'     => isset($postData->device_info->device_id) ? $postData->device_info->device_id:'',
                'user_id'       => isset($postData->user_info->uid) ? $postData->user_info->uid :'',
                'role_id'       => isset($postData->user_info->rid) ? $postData->user_info->rid :'',
                'sid'           => isset( $postData->user_info->sid ) ? $postData->user_info->sid : '',
                'session_id'    => $sessions[$key]['id'],
                'start_time'    => $sessions[$key]['start_time'],
                'end_time'      => $sessions[$key]['end_time'],
                'duration'      => $sessions[$key]['duration'],
                'hour_sk'       => $sessions[$key]['hour_sk'],
                'date_sk'       => $this->getdatesk( date('Y-m-d' , $value->start_time / 1000 ) )   
                ); 
                $dwdb ->insert('user_distribution', $data);
            }
        }
    }

    //返回对应hour_sk
    function gethoursk($time)
    {
        $time =  substr($time, 11);
        if( substr($time,0,3)."00:00" <= $time  &&  $time  <=  substr($time,0,3)."30:00" ) 
        {
            $hour_sk = substr($time,0,2).'00';
        }
        elseif( substr($time,0,3)."30:00" < $time  &&  $time  <  substr($time,0,2)+1 .":00:00" )
        {
            $hour_sk = substr($time,0,2).'30';
        }
        return $hour_sk;
    }


    //返回对应日期key
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




}
?>
