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
  //print_r($array);//信息很齐全
   unset($array[0]);
   foreach($array as $row)
    {
       $html .=$row['file'].':'.$row['line'].'行,调用方法:'.$row['function']."<p>";
    }
    return$html;
}

/**
 * Clientdata class
 *
 * @category PHP
 * @package  Service
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class Clientdata extends CI_Model
{
    /**
     * Construct
     *
     * @return void
     */
    function Clientdata()
    {
        parent::__construct();
        $this->load->model('utility');
        $this->load->database();
        $this->load->helper("date");
        $this->load->model('lbs_service/google', 'google');
        $this->load->model('lbs_service/ipinfodb', 'ipinfodb');
        $this->load->model('service/utility', 'utility');
        $this->load->library('iplibrary');
    }

    /**
     * Add clientdata
     *
     * @param array $content json data
     *
     * @return void
     */
    function addClientdata($content)
    {
       // var_dump($content)
        $this->load->model('servicepublicclass/clientdatapublic', 'clientdatapublic');
        $clientdata = new clientdatapublic();
        $clientdata->loadclientdata($content);
        $ip = $this->utility->getOnlineIP();

        $nowtime = date('Y-m-d H:i:s');
        if (isset($clientdata->time)) {
            $nowtime = $clientdata->time;
            if (strtotime($nowtime) < strtotime('1970-01-01 00:00:00') || strtotime($nowtime) == '') {
                $nowtime = date('Y-m-d H:i:s');
            }
        }
        $data = array(
            'productkey' => $clientdata->appkey,
            'platform' => $clientdata->platform,
            'osversion' => $clientdata->os_version,
            'language' => $clientdata->language,
            'deviceid' => $clientdata->deviceid,
            'resolution' => $clientdata->resolution,
            'devicename' => isset($clientdata->devicename) ? $clientdata->devicename : 'unknown',
            'modulename' => isset($clientdata->modulename) ? $clientdata->modulename : '',
            'imei' => isset($clientdata->imei) ? $clientdata->imei : '',
            'imsi' => isset($clientdata->imsi) ? $clientdata->imsi : '',
            'havegps' => isset($clientdata->havegps) ? $clientdata->havegps : '',
            'havebt' => isset($clientdata->havebt) ? $clientdata->havebt : '',
            'havewifi' => isset($clientdata->havewifi) ? $clientdata->havewifi : '',
            'havegravity' => isset($clientdata->havegravity) ? $clientdata->havegravity : '',
            'wifimac' => isset($clientdata->wifimac) ? $clientdata->wifimac : '',
            'version' => isset($clientdata->version) ? $clientdata->version : '',
            'network' => isset($clientdata->network) ? $clientdata->network : '',
            'latitude' => isset($clientdata->latitude) ? $clientdata->latitude : '',
            'longitude' => isset($clientdata->longitude) ? $clientdata->longitude : '',
            'isjailbroken' => isset($clientdata->isjailbroken) ? $clientdata->isjailbroken : 0,
            'useridentifier' => isset($clientdata->userid) ? $clientdata->userid : '',
            'date' => $nowtime,
            'service_supplier' => isset($clientdata->mccmnc) ? $clientdata->mccmnc : '0',
            'clientip' => $ip
        );
        $latitude = isset($clientdata->latitude) ? $clientdata->latitude : '';
        $choose = $this->config->item('get_geographical');
        $data["country"] = 'unknown';
        $data["region"] = 'unknown';
        $data["city"] = 'unknown';
        $data["street"] = '';
        $data["streetno"] = '';
        $data["postcode"] = '';
        if ($choose == 2) {
            $this->iplibrary->setLibrary('GeoIpLibrary', $ip);

            $data['country'] = $this->iplibrary->getCountry();
            $data['region'] = $this->iplibrary->getRegion();
            $data['city'] = $this->iplibrary->getCity();
        }
        if ($choose == 1) {
            $this->iplibrary->setLibrary('IpIpLibrary', $ip);

            $data['country'] = $this->iplibrary->getCountry();
            $data['region'] = $this->iplibrary->getRegion();
            $data['city'] = $this->iplibrary->getCity();
        }

        //function_dump(array($this->db, "insert"));
        $this->db->insert('clientdata', $data);
    }
}
?>
