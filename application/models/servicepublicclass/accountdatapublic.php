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

/**
 * Accountdatapublic class
 *
 * @category PHP
 * @package  Service
 * @author   Cobub Team <open.cobub@gmail.com>
 * @license  http://www.cobub.com/docs/en:razor:license GPL Version 3
 * @link     http://www.cobub.com
 */
class accountdatapublic extends CI_Model
{
    var $appkey;
    var $deviceid;
    var $userid;
    var $time;


    /**
     * Load clientdata
     *
     * @param array $content postclientdata data
     *
     * @return void
     */
    function loadaccountdata($content)
    {
        $this->appkey = $content->appkey;
        $this->deviceid = isset($content->deviceid) ? $content->deviceid : '';
        $this->userid = isset($content->userid) ? $content->userid : '';
        $this->time = isset($content->time) ? $content->time : '';
    }

}
