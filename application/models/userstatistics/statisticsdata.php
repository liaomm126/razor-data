<?php
/*
* 统计数据模型
*/

class Statisticsdata extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }


    //返回用户统计数据
    function getnewlist($fromTime,$toTime)
    {

        $data = $this->getstatisticsdata($fromTime,$toTime);
        $server = array();
        foreach ($data as $key => $value) 
        {
            $server[$key]['date'] = $value['date'];
            $server[$key]['dlsb'] = $value['dlsb'];
            $server[$key]['dljs'] = $value['dljs'];
            $server[$key]['duration'] = number_format( $value['duration'] / 3600 / 1000 , 1) ? number_format( $value['duration'] / 3600 / 1000 , 1) : 0;
            $server[$key]['rjzxsc']=$server[$key]['duration']  / $value['dlsb'] ? number_format($server[$key]['duration'] / $value['dlsb'] , 1 ) : 0 ;
            $server[$key]['dlzs'] = $value['dlzs'];
            $server[$key]['rjdlcs'] = $value['dlzs'] / $value['dlsb']  ?   number_format($value['dlzs'] / $value['dlsb'],1)   : 0 ;
            $server[$key]['ACU'] =  '';
            $server[$key]['PCU'] =  '';
        }
        return  $server;
    }



    //查询用户统计数据
    function  getstatisticsdata($fromTime,$toTime)
    {
        $dwdb = $this->load->database('dw',true);
        $sql =
              "SELECT  
              date( datevalue ) date,
              IFNULL(fff.dlsb,0) dlsb,
              IFNULL(fff.dljs,0) dljs,
              IFNULL(fff.dlzs,0) dlzs,
              IFNULL(fff.duration,0) duration
              from " .$dwdb->dbprefix('dim_date') . "  dd  
              LEFT JOIN
              (
              SELECT  
              COUNT(DISTINCT lo.device_id) dlsb ,  
              COUNT(DISTINCT lo.rus_id) dljs , 
              COUNT(lo.id) dlzs,  
              ff.duration , 
              lo.date_sk
              from "  .$dwdb->dbprefix('login_info') . "     lo
              LEFT JOIN 
              ( 
              SELECT  
              SUM(ud.duration) as  duration ,
              ud.date_sk   
              from " .$dwdb->dbprefix('user_distribution') . "  ud  
              WHERE  start_time  BETWEEN '$fromTime'  and  '$toTime'
              GROUP BY ud.date_sk 
              )  ff   on  ff.date_sk  = lo.date_sk 
              WHERE  lo.create_login_ts  BETWEEN '$fromTime'  and  '$toTime'
              GROUP BY lo.date_sk
              )  fff  on  fff.date_sk  = dd.date_sk
              WHERE  dd.datevalue  BETWEEN '$fromTime'  and  '$toTime'
              ORDER BY date asc";
        $query = $dwdb->query($sql);
        //echo  $dwdb->last_query();
        $getstatisticsdata = $query->result_array();  //返回对象类型
        return $getstatisticsdata;
    }













}