<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo lang('l_cobubRazor'); ?></title>
        <style>
            * {
                margin: 0;
                padding: 0;
            }
        </style>
        <link rel="icon" href="<?php echo base_url()?>favicon.ico" type="image/x-icon"/>
        <link rel="shortcut icon" href="<?php echo base_url()?>favicon.ico" type="image/x-icon"/>
        <link rel="Bookmark" href="<?php echo base_url()?>favicon.ico"/>
        <link rel="stylesheet"
        href="<?php echo base_url();?>assets/css/jquery.select.css"
        type="text/css" media="screen" />
        <script src="<?php echo base_url();?>assets/js/jquery.select.js"
        type="text/javascript"></script>
        <!--	<link rel="stylesheet" href="<?php echo base_url();?>assets/css/english_layout.css" type="text/css" media="screen" />-->
        <link rel="stylesheet"
        href="<?php echo base_url();?>assets/css/<?php  $style = get_cookie('style');
            if ($style == "") {echo "layout";
            } else {echo get_cookie('style');
            }
        ?>.css"
        type="text/css" media="screen" />
        <link rel="stylesheet"
        href="<?php echo base_url();?>/assets/css/helplayout.css"
        type="text/css" media="screen" />
        <link rel="stylesheet"
	href="<?php echo base_url();?>assets/css/bootstrap-tagmanager.css" type="text/css"
	media="screen" />
        <!--[if lt IE 9]>
        <link rel="stylesheet" href="<?php echo base_url();?>assets/css/ie.css" type="text/css" media="screen" />
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="stylesheet"
        href="<?php echo base_url();?>assets/css/tag/jquery-ui-1.10.3.custom.css" type="text/css"
        media="screen" />
        <link rel="stylesheet"
        href="<?php echo base_url();?>assets/css/<?php  $style = get_cookie('style');
            if ($style == "") {echo "layout";
            } else {echo get_cookie('style');
            }
        ?>pagination.css"
        type="text/css" media="screen" />
        <script src="<?php echo base_url();?>assets/js/json/json2.js"
        type="text/javascript"></script>


<!-- old version of jQuery --><!--
        <script src="<?php echo base_url();?>assets/js/jquery-1.7.1.min.js"
        type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/jquery-ui-1.8.min.js"
        type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/jquery-ui-1.8.16.custom.min.js"
        type="text/javascript"></script>
         -->

         <script src="<?php echo base_url();?>assets/js/tag/jquery-1.9.1.js"
	type="text/javascript"></script>

<script
	src="<?php echo base_url();?>assets/js/tag/jquery-ui-1.10.3.custom.js"
	type="text/javascript"></script>

	<script
	src="<?php echo base_url();?>assets/js/jquery-1.9-pack.js"
	type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.validate.js"
        type="text/javascript"></script>

        <script src="<?php echo base_url();?>assets/js/hideshow.js"
        type="text/javascript"></script>
        <script
        src="<?php echo base_url();?>assets/js/jquery.tablesorter.min.js"
        type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.pagination.js"
        type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/jquery.blockUI.js"
        type="text/javascript"></script>
        <script type="text/javascript"
        src="<?php echo base_url();?>assets/js/jquery.equalHeight.js"></script>
        <script src="<?php echo base_url();?>assets/js/estimate.js"
        type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/charts/highcharts.js"
        type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/charts/highcharts-more.js"
        type="text/javascript"></script>
        <script
        src="<?php echo base_url();?>assets/js/charts/modules/exporting.js"
        type="text/javascript"></script>
        <!-- easydialog -->
        <link rel="stylesheet"
        href="<?php echo base_url();?>assets/css/easydialog.css" type="text/css"
        media="screen" />
        <script	src="<?php echo base_url();?>assets/js/easydialog/easydialog.js"
        type="text/javascript"></script>
        <script	src="<?php echo base_url();?>assets/js/easydialog/easydialog.min.js"
        type="text/javascript"></script>
        <!-- easydialog -->
        <script src="<?php echo base_url();?>assets/js/jquery.uploadify.v2.1.4.min.js"
        type="text/javascript"></script>
        <script src="<?php echo base_url();?>assets/js/swfobject.js"
        type="text/javascript"></script>
        <link href="<?php echo base_url();?>assets/css/uploadify.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo base_url();?>assets/css/default.css" rel="stylesheet" type="text/css" />
        <script	src="<?php echo base_url();?>assets/js/bootstrap-tagmanager.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $(".tablesorter").tablesorter();
            });
            $(document).ready(function() {

                //When page loads...
                $(".tab_content").hide();
                //Hide all content
                $("ul.tabs li:first").addClass("active").show();
                //Activate first tab
                $(".tab_content:first").show();
                //Show first tab content

                //On Click Event
                $("ul.tabs li").click(function() {

                    $("ul.tabs li").removeClass("active");
                    //Remove any "active" class
                    $(this).addClass("active");
                    //Add "active" class to selected tab
                    $(".tab_content").hide();
                    //Hide all tab content

                    var activeTab = $(this).find("a").attr("href");
                    //Find the href attribute value to identify the active tab + content
                    $(activeTab).fadeIn();
                    //Fade in the active ID content
                    return false;
                });
            });

        </script>
        <script type="text/javascript">
            $(function() {
                $('.column').equalHeight();
            });

        </script>


    <script src="<?php echo base_url();?>assets/js/jquery-ui-timepicker-addon.js"
    type="text/javascript"></script>
    
     <script src="<?php echo base_url();?>assets/js/jquery-ui-timepicker-zh-CN.js"
    type="text/javascript"></script>

 <link href="<?php echo base_url();?>assets/css/jquery-ui-timepicker-addon.css" rel="stylesheet" type="text/css" />

<style>
#selectcurTime{

    position: relative;
    top: 5px;
    display: none;
}
</style>

    </head>
    <body id="body">
        <header id="header">
            <hgroup>
                <h1 class="site_title"><a href="<?php echo base_url();?>"> <img class="logo" src="<?php echo base_url();?>assets/images/razorlogo.png" style="border:0"/> <span style=""><?php echo lang('g_cobubRazor')?></span> </a></h1>
                <h3 class="section_title"><?php if(isset($username)):?>
                <?php  echo anchor('/', lang('v_console'));?> | <?php  echo anchor('/profile/modify/', lang('m_profile'));?> | <?php  echo anchor('/auth/change_password/', lang('m_changePassword'));?> | <?php  echo anchor('/auth/logout/', lang('m_logout'));?>
                <?php  else:?>
                <?php  echo anchor('/auth/login/', lang('l_login'));?>
                <?php  endif;?></h3>
            </hgroup>
        </header>
        <?php
if(! isset ($versioninform)&& isset ($versionvalue))
{
if(isset($username)):
        ?>
        <div id="newversioninform"   style="text-align:center;background-color:#E6DB55;font-size: 12px;padding:8px 0;" >
            <?php  echo lang('l_versioninform') . $versionvalue . lang('l_vinformtogo');?><a href="<?php  echo $version;?>" target="_blank"><?php  echo $version;?></a>
            <?php  echo lang('l_vinformupdate');?>
            <a href="javascript:void(o)" onclick="closeinform()" style="float:right;margin-right:2%;" ><img src="<?php  echo base_url();?>/assets/images/erroryell.png" /></a>
        </div>
        <?php  endif;
            }
        ?>
        <!-- end of header bar -->
        <?php if(isset($login) && $login):
        ?>
        <section id="secondary_bar">
            <div class="user">
                <p>
                    <?php
                    if (isset($username)) { echo $username;
                    }
                    ?>
                    (<?php echo anchor('/profile/modify',lang('m_profile'))
                    ?>)
                </p>
                <!-- <a class="logout_user" href="#" title="Logout">Logout</a> -->
            </div>
            <div class="breadcrumbs_container">
                <article class="breadcrumbs">
                    <a href="<?php echo base_url();?>"><?php echo lang('v_console')
                    ?></a>
                    <?php if(isset($product)):
                    ?>
                    <div class="breadcrumb_divider"></div>
                    <?php  echo anchor('/report/newdata/index/', $product -> name);?>
                    <?php  endif;?>
                    <?php if(isset($pageTitle)&&$pageTitle!=""):
                    ?>
                    <div class="breadcrumb_divider"></div>
                    <a class="current"><?php  echo $pageTitle;?></a>
                    <?php  endif;?>



                     <?php if(isset($viewname)&& $viewname!=""){ ?>
                    <div class="breadcrumb_divider"></div>
                    <a class="current"><?php  echo $viewname;?></a>
                    <?php  }?>
                </article>
            </div>
            <!-- Section for user date section selector -->
            <?php
            $fromTime = $this -> session -> userdata("fromTime");

      

            if (isset($fromTime) && $fromTime != null && $fromTime != "") {
            } else {
                $fromTime = date("Y-m-d", strtotime("-6 day"));
            }

  

            $toTime = $this -> session -> userdata("toTime");
            if (isset($toTime) && $toTime != null && $toTime != "") {

            } else {
                $toTime = date("Y-m-d", time());
            }
            ?>
            <?php if(isset($showDate)&&$showDate==true):
            ?>
            <div class="select_option fr"
            style="z-index:5555;position: absolute; right: 30px; margin-top: 3px">
                <div class="select_arrow fr"></div>
                <div id="selected_value" style="font-size: 12px;"
                class="selected_value fr">
                    <?php  echo $fromTime;?>~<?php  echo $toTime;?>
                </div>
                <div class="clear"></div>
                <div id="select_list_body" style="display: none;"
                class="select_list_body">
                    <ul>
                        <li>
                            <a class="" id=""
                            href="javascript:timePhaseChanged('7day')"> <?php echo  lang('g_lastweek')
                            ?></a>
                        </li>
                        <li>
                            <a class="" id=""
                            href="javascript:timePhaseChanged('1month');"> <?php echo  lang('g_lastmonth')
                            ?></a>
                        </li>
                        <li>
                            <a class=""
                            href="javascript:timePhaseChanged('3month');"> <?php echo  lang('g_last3months')
                            ?></a>
                        </li>
                        <li>
                            <a class=""
                            href="javascript:timePhaseChanged('all');"> <?php echo  lang('g_alltime')
                            ?></a>
                        </li>
                        <li class="date_picker noClick">
                            <a style=""><?php echo  lang('g_anytime')
                            ?></a>
                        </li>
                        <li style="padding: 0; display: none;"
                        class="date_picker_box noClick">
                            <div style="width: 100%; padding-left: 20px;" class="selbox">
                                <span><?php echo  lang('g_from')
                                    ?></span>
                                <input
                                type="text" name="dpMainFrom" id="dpMainFrom" value=""
                                class="datainp first_date date">
                                <br>
                                <span>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo  lang('v_rpt_ve_to')
                                    ?></span>
                                <input type="text" name="dpMainTo" id="dpMainTo" value=""
                                class="datainp last_date date">
                            </div>
                            <div class="" style="">
                                <input id="any" type="button" onclick="onAnyTimePhaseUpdate()"
                                value="&nbsp;<?php echo  lang('g_search')?>&nbsp;"
                                class="any" style="margin: 5px 60px 0 50px;">
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <?php  endif;?>
        </section>
        <!-- end of secondary bar -->
        <aside id="sidebar" class="column" >
            <?php if(!isset($product)):?>
            <h3><?php echo lang('m_manage')?></h3>
            <ul class="toggle">
                <!--li class="icn_my_application">
                    <?php  echo anchor('/', lang('v_console'));?>
                </li-->
                 <li class="icn_system">
                    <?php  echo anchor('/report/console', lang('m_myapps'));?>
                </li>
                <?php if(isset($admin)&& !(isset($product))):?>
                <li class="icn_add_apps">
                    <?php  echo anchor('/manage/product/create', lang('m_new_app'));?>
                </li>
                <?php  endif;?>
                 <li class="icn_manacategory">
                    <?php echo anchor('/user/applicationManagement/', lang('m_appType')); ?>
                </li>
                <li class="icn_app_channel">
                    <?php echo anchor('/manage/channel/', lang('m_channelManagement'));?>
                </li>
            </ul>
            <?php endif;?>

            <?php if(isset($product)):?>
            <form class="quick_search">
                <?php if(isset($productList)): ?>
                <select style="width: 90%;" id='select_head' onchange='changeProduct(value)'>
                    <?php foreach($productList as $row){?>
                    <option <?php
                        if ($product -> id == $row['id'])
                        {
                            echo 'selected';
                        }
                    ?> value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                    <?php  }?>
                </select>
                <?php  endif;?>
            </form>
            <hr />

<!--             <h3><?php echo lang('m_rpt_statisticsOverview')?></h3>
            <ul class="toggle">
                <li class="icn_basic_statis">
                    <?php  echo anchor('/report/productbasic/view/' . $product -> id, lang('m_rpt_dashboard'));?>
                </li>
                <li class="icn_dis_channel">
                    <?php  echo anchor("/report/market/viewMarket", lang('m_rpt_channels'));?>
                </li>
                <li class="icn_version">
                    <?php  echo anchor('/report/version/', lang('m_rpt_versions'));?>
                </li>
            </ul> 

          -->

            <h3><?php echo lang('Data_statistics') ?></h3>
            <ul class="toggle">
               
                <li class="icn_dis_channel">
                    <?php  echo anchor("/report/newdata/index/". $product -> id,lang('new_data'));?>
                </li>

                 <li class="icn_basic_statis">
                    <?php  echo anchor("/report/retained/index",lang('retained_statistical'));?>
                </li>
                
                <li class="icn_version">
                    <?php  echo anchor("/report/recharge/index",'付费数据');?>
                </li>


                <li class="icn_use_frequency">
                    <?php  echo anchor("/report/userstatistics/index",'账号统计');?>
                </li>
    

                <li class="icn_remainuser">
                    <?php  echo anchor("/report/gradelist/index",'等级分布');?>
                </li>


            </ul>



            

            <h3><?php echo '每日数据'?></h3>
            <ul class="toggle">
                <li class="icn_remainuser">
                    <?php  echo anchor('/report/daydata/','日报数据');  ?>
                </li>

                <li class="icn_use_frequency">
                <?php  echo anchor('/report/datedata/','分日数据');  ?>
                </li>

 

            </ul> 


    

            <h3><?php echo '留存数据'?></h3>
            <ul class="toggle">
                
                <li class="icn_pagevisit">
                    <?php  echo anchor('/report/heldoutdata/','设备留存');  ?>
                </li>


                <li class="icn_new_user">
                    <?php  echo anchor('/report/userretained/','账号留存');  ?>
                </li>

    
                <li class="icn_analy_region">
                    <?php  echo anchor('/report/roleofretained/','角色留存');  ?>
                </li>

                    
                <li class="icn_operator">
                    <?php  echo anchor('/report/playerretained/','付费账号留存');  ?>
                </li>    

            </ul>






        <h3><?php echo '实时数据'?></h3>
            <ul class="toggle">
                <li class="icn_funnel_list">
                    <?php  echo anchor('/report/realtimedata/','实时数据');  ?>
                </li>
    
                <li class="icn_funnel_list">
                    <?php  echo anchor('/report/lifetimevalue/','LTV数据');  ?>
                </li>

                <li class="icn_mark_event">
                    <?php  echo anchor('/report/topranking/','付费排行榜');  ?>
                </li>

            </ul>



        <h3><?php echo '流失数据'?></h3>
            <ul class="toggle">
                <li class="icn_custom_event">
                    <?php  echo anchor('/report/devicedrain/','设备流失');  ?>
                </li>

              <!--   <li class="icn_funnel_list">
                    <?php  echo anchor('/report/roleloss/','角色流失');  ?>
                </li> -->
            </ul>





<!-- 
            <?php if ($this->config->item('redis')) { ?>
            <h3><?php echo lang('v_rpt_realtime_title') ?></h3>
            <ul class="toggle">
                <li class="icn_use_frequency"><?php echo anchor('/realtime/usersessions', lang('v_rpt_realtime_onlineuser_title'));?></li>
                <li class="icn_phaseusetime"><?php echo anchor('/realtime/areas', lang('v_rpt_realtime_areas_title'));?></li>
                <li class="icn_use_time"><?php echo anchor('/realtime/pageviews', lang('v_rpt_realtime_pageviews_title'));?></li>
                <li class="icn_remainuser"><?php echo anchor('/realtime/event', lang('v_rpt_realtime_event_title'));?></li>
                <li class="icn_pagevisit"><?php echo anchor('/realtime/transrate', lang('v_rpt_realtime_transrate_title'));?></li>
            </ul>
            <?php }?> 

            -->

        <!--     <h3><?php echo lang('m_rpt_users')?></h3>
            <ul class="toggle">
                <li class="icn_remainuser">
                    <?php  echo anchor('/report/userremain/', lang('m_rpt_userRetention'));?>
                </li>
                <li class="icn_use_frequency">
                    <?php  echo anchor('/report/usefrequency', lang('m_rpt_frequencyOfUse'));?>
                </li>
                <li class="icn_use_time">
                    <?php  echo anchor('/report/usetime', lang('m_rpt_usageDuration'));?>
                </li>
                <li class="icn_phaseusetime">
                    <?php  echo anchor('/report/productbasic/phaseusetime', lang('m_rpt_timeTrendOfUsers'));?>
                </li>
                <li class="icn_pagevisit">
                    <?php  echo anchor('/report/pagevisit', lang('m_rpt_pageviews'));?>
                </li>
                <li class="icn_analy_region">
                    <?php  echo anchor('/report/region/', lang('m_rpt_geography'));?>
                </li>
            </ul> -->
           <!--  <h3><?php echo lang('m_rpt_terminalsOrNetwork')?></h3>
            <ul class="toggle">
                <li class="icn_equipment">
                    <?php  echo anchor('/report/device/', lang('m_rpt_devices'));?>
                </li>
                <li class="icn_system">
                    <?php  echo anchor('/report/os/', lang('m_rpt_os'));?>
                </li>
                <li class="icn_resolution">
                    <?php  echo anchor('/report/resolution/', lang('m_rpt_resolution'));?>
                </li>
                <li class="icn_operator">
                    <?php  echo anchor('/report/operator/', lang('m_rpt_carriers'));?>
                </li>
                <li class="icn_network">
                    <?php  echo anchor('/report/network/', lang('m_rpt_networking'));?>
                </li>
            </ul> -->
           <!--  <h3><?php echo lang('m_rpt_events')?></h3>
            <ul class="toggle">
                <li class="icn_event_list">
                    <?php  echo anchor('/report/eventlist/', lang('m_rpt_eventlist'));?>
                </li>
                <li class="icn_funnel_list">
                    <?php  echo anchor('/report/funnels/', lang('v_rpt_re_funnelModel'));?>
                </li>
            </ul> -->
    
       
<!--            <h3><?php echo lang('m_manage')?></h3>
            <ul class="toggle">
                <li class="icn_edit_application">
                    <?php  echo anchor('/manage/product/editproduct/', lang('m_rpt_editApp'));?>
                </li>
                <li class="icn_sendpolicy">
                    <?php  echo anchor('/manage/onlineconfig/', lang('m_rpt_sendPolicy'));?>
                </li>
                <li class="icn_custom_event">
                    <?php echo anchor('/manage/event/', lang('m_rpt_customEvent'));?>
                </li>
                <li class="icn_custom_exception">
                    <?php echo anchor('/manage/alert/', lang('m_rpt_exception'));?>
                </li>
                <li class="icn_app_channel">
                    <?php echo anchor('/manage/channel/appchannel/', lang('m_rpt_appChannel'));?>
                </li>
                <li class="icn_funnel_list">
                    <?php echo anchor('/manage/funnels', lang('m_rpt_editFunnel'));
                    ?>
                </li>
                <li class="icn_mark_event">
                    <?php echo anchor('/manage/pointmark/listmarkeventspage', lang('m_dateevents'));?>
                </li>
                <br/>
            </ul> -->
            <ul>
                <hr/>
                <br/>
            <!--    <li class="icn_term_define">
                    <a href="<?php echo site_url() ;?>/help" target="_blank"> <?php echo lang('m_termsAndD');?></a>
                </li>  -->

                <li class="icn_term_define">
                    <a href="<?php echo site_url() ;?>/help/helpmanager" target="_blank"> <?php echo '统计规则';?></a>
                </li>


            </ul> 

  
            <?php endif;?>
           

            <?php if(isset($admin)&& !(isset($product))): ?>
            <h3><?php echo lang('m_userPermission')?></h3>
            <ul class="toggle">
                <li class="icn_mangageuser">
                    <?php echo anchor('/user/', lang('m_userManagement'));?>
                </li>
                <li class="icn_managerole">
                    <?php echo anchor('/user/rolemanage/', lang('m_roleManagement'));?>
                </li>

                <li class="icn_manaresource"><?php echo anchor('/user/resourcemanage/', lang('m_resourceManagement'));?></li>

                <li class="icn_new_user">
                    <?php echo anchor('/user/newUser/', lang('t_newUser'));?>
                </li>
            </ul>

            <?php endif; ?>



            <footer>
                <hr />
<!--                 
                <p>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a id="greencss"
                href="javascript:setcssstyle('greenlayout')"><img
                src="<?php echo base_url();?>assets/images/greenbtn.png" style="border:0"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a id="layoutcss" href="javascript:setcssstyle('layout')"><img
                src="<?php echo base_url();?>assets/images/graybtn.png" style="border:0"/></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a id="bluecss" href="javascript:setcssstyle('bluelayout')"><img
                src="<?php echo base_url();?>assets/images/bluebtn.png" style="border:0"/></a>
                </p>
                 -->
                <p>
                </p>
            </footer>
        </aside>
        <?php endif;
        ?>

        <script type="text/javascript">
var username="<?php if(isset($username)){echo $username;}else{echo false;} ?>";
var nouse="<?php $isuse=get_cookie('nouse') ;if($isuse=="isfalse"){echo $isuse;}else{echo "istrue";}?>";
$(document).ready(function(){
//init time segment selector
initTimeSelect();
});

$(function() {
$("#dpMainFrom" ).datepicker({dateFormat: "yy-mm-dd","setDate":new Date()});
});

$(function() {
$( "#dpMainTo" ).datepicker({ dateFormat: "yy-mm-dd" ,"setDate":new Date()});
});

// $(function() {
// $("#dpMainFrom" ).datetimepicker();
// });


// $(function() {
// $( "#dpMainTo" ).datetimepicker();
// });





function blockUI()
{
var chart_canvas = $('#body');
var loading_img = $("<img src='<?php echo base_url();?>/assets/images/loader.gif'/>");

chart_canvas.block({
message: loading_img,
css:{
width:'32px',
border:'none',
background: 'none'
},
overlayCSS:{
backgroundColor: '#FFF',
opacity: 0.8
},
baseZ:997
});
}


//时间筛选
function timePhaseChanged(phase)
{
   console.log( '=---phase',phase);

blockUI();
var url = "<?php echo site_url()?>/report/console/changeTimePhase/"+phase;      //传到 console / changeTimePhase  时间筛选
jQuery.getJSON(url, null, function(data) {
window.location.reload();   //刷新页面
});

//把当前的日期筛选  存入cookie
setCookie("timephase",phase);
}

function onAnyTimePhaseUpdate()     //自定义时间查询
{
blockUI(); 
var fromTime = document.getElementById('dpMainFrom').value;     //获取任意时间开始  筛选

var toTime = document.getElementById('dpMainTo').value;         //获取任意时间结束  筛选

        console.log('--fromTime-',fromTime);
        console.log('--toTime-',toTime);

var url = "<?php echo site_url()?>/report/console/changeTimePhase/any/"+fromTime+"/"+toTime;
jQuery.getJSON(url, null, function(data) {
window.location.reload();   //重新刷新页面
});
}

//Change selected product to another  切换当前应用
function changeProduct(pid)
{
blockUI();
var url = "<?php echo site_url()?>/manage/product/changeProduct/"+pid;
jQuery.getJSON(url, null, function(data) {
window.location.href="<?php echo site_url()?>/report/newdata/index/"+pid;
//window.location.reload();
});
}

function setcssstyle(cssstyle)
{
setCookie("style",cssstyle);
window.location.reload();
}

function setCookie(name,value)
{
var Days = 365; //cookie will remain one year
var exp  = new Date();    //new Date("December 31, 9998");

//console.log('-exp-',exp);  //得到国际当前的时间
exp.setTime(exp.getTime() + Days*24*60*60);
//这句  看不懂
document.cookie = name + "="+ escape(value) +";expires="+ exp.toGMTString();

}

//update version inform  切换 版本
function closeinform()  
{
var item= document.getElementById("noinform") ;
var url="<?php echo site_url()?>/report/console/setnewversion";
jQuery.getJSON(url, null, function(data)
{
window.location.reload();

});
document.getElementById("newversioninform").style.display="none";
}


        </script>
