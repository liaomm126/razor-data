<section id="main" class="column">
  <article class="module width_full">
    <header>
      <h3 class="tabs_involved"><?php echo  lang('real_time_new') ?></h3>
             <select style="position: relative; top: 5px;"
                onchange="switchTimePhase(this.options[this.selectedIndex].value)"
                id='startselect'>
                <option value=today selected><?php echo  lang('g_today')?></option>    
                <option value=yestoday><?php echo  lang('g_yesterday')?></option>
<!--                 <option value=last7days><?php echo  lang('g_last7days')?></option>
                <option value=last30days><?php echo  lang('g_last30days')?></option> -->
                <option value=anythin><?php echo  lang('g_anytime')?></option>
            </select>
            <div id='selectcurTime'>
                <input type="text" id="dpTimeFrom">
             <!--     <input type="text" id="dpTimeTo"> -->
                <input type="submit" id='timebtn'
                    value="<?php echo  lang('g_search')?>" class="alt_btn"
                    onclick="onAnyTimeClicked()">
            </div>   
    </header>
    <table class="tablesorter" cellspacing="0"> 
      <thead> 
        <tr> 
            <th><?php echo  lang('server');?></th> 
            <th><?php echo  lang('landing_equipment_daily'); ?></th>
            <th><?php echo  lang('new_equipment_daily'); ?></th>  
            <th><?php echo  lang('active_equipment_daily'); ?></th>  
            <th><?php echo  lang('landing_account_daily'); ?></th>
            <th><?php echo  lang('new_account_daily'); ?></th> 
            <th><?php echo  lang('active_account_daily'); ?></th>
            <th><?php echo  lang('landing_role_daily'); ?></th> 
            <th><?php echo  lang('new_role_daily'); ?></th>  
            <th><?php echo  lang('active_role_daily'); ?></th> 
        </tr> 
      </thead> 
      <tbody id="content1">        
      
      </tbody>
    </table> 
    <footer>
    <div id="pagination1"  class="submit_link">
    </div>
    </footer>
  </article>  
</section>


<script>

    //When page loads...
    $(document).ready(function(){  
      getfirstchartdata();
    });

    function newdatapageselectCallback(page_index, jq){     
        page_index = arguments[0] ? arguments[0] : "0";
        jq = arguments[1] ? arguments[1] : "0";   
        var index = page_index*<?php echo PAGE_NUMS?>;
        var pagenum = <?php echo PAGE_NUMS?>; 
        var msg = ""; 
        msg = msg+"<tr><td>";
        msg = msg+  "<?php echo lang('total') ;?>";
        msg = msg+"</td><td>";
        msg = msg+ total_loginnewequipment;    // 日登陆设备数
        msg = msg+"</td><td>";
        msg = msg+ total_newequipment;     // 日新增设备数
        msg = msg+"</td><td>";
        msg = msg+ total_hynewequipment;     // 日活跃设备数
        msg = msg+"</td><td>";
        msg = msg+ total_loginnewaccountnumber;  // 日登陆账号数
        msg = msg+"</td><td>";
        msg = msg+ total_newaccountnumber;  // 日新增账号数
        msg = msg+"</td><td>";
        msg = msg+ total_hynewaccountnumber;  // 日活跃账号数
        msg = msg+"</td><td>";
        msg = msg+ total_logindailyactiveaccount; // 日登陆角色数
        msg = msg+"</td><td>";
        msg = msg+  total_dailyactiveaccount;  // 日新增角色数
        msg = msg+"</td><td>";
        msg = msg+ total_hylogindailyactiveaccount;  // 日活跃角色数
        msg = msg+ "</td></tr>";

        for(i=0;i<pagenum && (index+i)<datanewlist.length ;i++)
        { 
            msg = msg+"<tr><td>";
            msg = msg + "<?php  echo lang('server');?>" + datanewlist[i+index].server ;  // 服务器
            msg = msg+"</td><td>";
            msg = msg+ datanewlist[i+index].loginnewequipment;   // 日登陆设备数
            msg = msg+"</td><td>";
            msg = msg+ datanewlist[i+index].newequipment;   // 日新增设备数
            msg = msg+"</td><td>";
            msg = msg+ datanewlist[i+index].hynewequipment;   // 日活跃设备数
            msg = msg+"</td><td>";
            msg = msg+ datanewlist[i+index].loginnewaccountnumber;  // 日登陆账号数
            msg = msg+"</td><td>";
            msg = msg+ datanewlist[i+index].newaccountnumber;       // 日新增账号数
            msg = msg+"</td><td>";
            msg = msg+ datanewlist[i+index].hynewaccountnumber;       // 日活跃账号数
            msg = msg+"</td><td>";
            msg = msg+ datanewlist[i+index].logindailyactiveaccount;  // 日登陆角色数
            msg = msg+"</td><td>";
            msg = msg+ datanewlist[i+index].dailyactiveaccount;  // 日新增角色数
            msg = msg+"</td><td>";
            msg = msg+ datanewlist[i+index].hylogindailyactiveaccount;   // 日活跃角色数
            msg = msg+ "</td></tr>";
        }
        $('#content1').html(msg);     
        return false;
        } 

     
    function newdatainitPagination() 
    {
        var num_entries = (datanewlist.length)/10;
        $("#pagination1").pagination(num_entries, {
             num_edge_entries: 2,
             prev_text: '<?php echo  lang('g_previousPage')?>',
             next_text: '<?php echo  lang('g_nextPage')?>',           
             num_display_entries: 4,
             callback: newdatapageselectCallback,
             items_per_page:1               
                   });
    }


</script>



<script>

    var chartdata;          
    var fromCurTime;        //从那一天  
    var toCurTime;          // 到某天    
    var chartname = 'startuser';  //活跃用户
    var timephase = 'today';           //默认日期  今天 
    var total_loginnewequipment;
    var total_hynewequipment;
    var total_loginnewaccountnumber;
    var total_logindailyactiveaccount;
    var total_dailyactiveaccount;
    var total_hylogindailyactiveaccount;
    var total_newequipment;
    var total_newaccountnumber;
    var total_hynewaccountnumber 
    //When page loads...
    dispalyOrHideCurTimeSelect();


    function dispalyOrHideCurTimeSelect()
    {
        var value = document.getElementById('startselect').value;
        if(value=='anythin')
        {
            document.getElementById('selectcurTime').style.display="inline";
        }
        else
        { 
            document.getElementById('selectcurTime').style.display="none";
        }
    } 



</script>




<script type="text/javascript">

    function switchTimePhase(time)
    {
        dispalyOrHideCurTimeSelect();  //判断是否 显示 任意时间段 筛选
        timephase=time;    
        if(time!="anythin")
        {
            getfirstchartdata();   //如果选择 any  就不进行查询数据 图表渲染 
        }
    }

    function getfirstchartdata()
    {
        var myurl="";
        if(timephase=='anythin')
        {    
            myurl="<?php echo site_url()?>/report/newdata/getnewdata/"+timephase+"/"+fromTime;
        }
        else
        {
            myurl = "<?php echo site_url()?>/report/newdata/getnewdata/"+timephase+"?date="+new Date().getTime();
        }
        renderCharts(myurl); 
    }


    function renderCharts(myurl)
    {   
        var chart_canvas = $('#content');  //获取对象
        var loading_img = $("<img src='<?php echo base_url(); ?>/assets/images/loader.gif'/>"); //加载图片
        //最先加载 loading_img 数据
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

        jQuery.getJSON(myurl, null, function(data){   
        total_loginnewequipment          =  data.total_loginnewequipment;
        total_hynewequipment             =  data.total_loginnewequipment -  data.total_newequipment;;
        total_loginnewaccountnumber      =  data.total_loginnewaccountnumber;
        total_logindailyactiveaccount    =  data.total_logindailyactiveaccount;
        total_dailyactiveaccount         =  data.total_dailyactiveaccount;
        total_hylogindailyactiveaccount  =  data.total_logindailyactiveaccount -  data.total_dailyactiveaccount;
        total_newequipment               =  data.total_newequipment;
        total_newaccountnumber           =  data.total_newaccountnumber;
        total_hynewaccountnumber         =  data.total_loginnewaccountnumber -   data.total_newaccountnumber;
        var  list = data.datanewlist;
        datanewlist  = eval(list);
        newdatainitPagination(); 
        newdatapageselectCallback(0,null);
       
        });

        // chart_canvas.unblock();
    }


    function get_unix_time(dateStr)
    {
        var newstr = dateStr.replace(/-/g,'/'); 
        var date =  new Date(newstr); 
        var time_str = date.getTime().toString();
        return time_str.substr(0, 10);
    }


    //任意时间段选择查询  走这里
    function onAnyTimeClicked()
    {    
        fromCurTime = document.getElementById('dpTimeFrom').value;    
        fromTime =  get_unix_time(fromCurTime);
        getfirstchartdata();    
    }    

</script>



<script type="text/javascript">

 $(function() {
  $("#dpTimeFrom" ).datepicker({dateFormat: "yy-mm-dd","setDate":new Date()});
     });



</script>

