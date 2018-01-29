<?php
/**
 * Cobub Razor
 *
 * An open source mobile analytics system
 *
 * PHP versions 5
 *
 * @category  MobileAnalytics
 * @package   CobubRazor
 * @author    Cobub Team <open.cobub@gmail.com>
 * @copyright 2011-2016 NanJing Western Bridge Co.,Ltd.
 * @license   http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link      http://www.cobub.com
 * @since     Version 0.1
 */

 function function_dump($funcname)
 {
    try {
        if(is_array($funcname)) {
            $func = new ReflectionMethod($funcname[0], $funcname[1]);
            $funcname = $funcname[1];
        } else {
            $func = new ReflectionFunction($funcname);
        }
    } catch (ReflectionException $e) {
        echo $e->getMessage();
        return;
    }
    $start = $func->getStartLine() - 1;
    $end =  $func->getEndLine() - 1;
    $filename = $func->getFileName();
    echo "function $funcname defined by $filename($start - $end)\n";
}

function print_stack_trace()
{
    $array =debug_backtrace();
  //print_r($array);//��Ϣ����ȫ
   unset($array[0]);
   foreach($array as $row)
    {
       $html .=$row['file'].':'.$row['line'].'��,���÷���:'.$row['function']."<p>";
    }
    return$html;
}

/**
 * Accountdata class
 *
 * @category PHP
 * @package  Service
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Accountdata extends CI_Model
{
    /**
     * Construct
     *
     * @return void
     */
    function __construct()
    {
        parent::__construct();
        $this->load->database();  //�������ݿ�
    }

    /**
     * addAccountdata
     *
     * @param array $content json data
     *
     * @return void
     */
    function addAccountdata($content)
    {
        $this->load->model('servicepublicclass/accountdatapublic', 'accountdatapublic'); // ����model
        $accountdata = new accountdatapublic();  // ʵ��������
        $accountdata->loadaccountdata($content);

        $nowtime = date('Y-m-d H:i:s');
        if (isset($accountdata->time))
        {
            $nowtime = $accountdata->time;
            if (strtotime($nowtime) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime) == '')
            {
                $nowtime = date('Y-m-d H:i:s');
            }
        }

        $data = array(
            'appkey' => isset($accountdata->appkey) ? $accountdata->appkey : '',
            'deviceid' => isset($accountdata->deviceid) ? $accountdata->deviceid:'',
            'userid' => isset($accountdata->userid) ? $accountdata->userid:'',
            'create_date' => $nowtime,
        );

        $this->db->insert('accountdata', $data);
    }
}
?>
