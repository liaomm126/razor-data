<!-- 订单查询 -->
<section class="section_maeginstyle" id="highchart">
    <article class="module width_full">
        <header>
            <!-- 日期时间筛选 -->
            <h3 class="h3_fontstyle"> <?php echo  lang('order_query'); ?> </h3>
            <select style="position: relative; top: 5px;"
                onchange="switchTimePhase(this.options[this.selectedIndex].value)"
                id='startselect'>
                <option value=today selected><?php echo  lang('g_today')?></option>    
                <option value=yestoday><?php echo  lang('g_yesterday')?></option>
                <option value=last7days><?php echo  lang('g_last7days')?></option>
                <option value=last30days><?php echo  lang('g_last30days')?></option>
                <option value=any><?php echo  lang('g_anytime')?></option>
            </select>
            <!-- 任意时间筛选  默认隐藏  slelect any 就会显示出来 -->
            <div id='selectcurTime'>
                <input type="text" id="dpTimeFrom"> <input type="text" id="dpTimeTo">
                <input type="submit" id='timebtn'
                    value="<?php echo  lang('g_search')?>" class="alt_btn"
                    onclick="onAnyTimeClicked()">
            </div>
          <span class="relative r" id="export"> 
          <a href="javascript:void(0)" onclick="exportphasetime()" class="bottun4 hover"><font><?php echo  lang('g_exportToCSV')?></font></a>
          </span>
        </header>
        <!-- header 结束 -->
        <table class="tablesorter" cellspacing="0"> 
            <thead> 
                <tr> 
                    <th><?php echo   lang('top_up_date');?></th> 
                    <th><?php echo   lang('top_up_time'); ?></th>
                    <th><?php echo   lang('UID');?></th> 
                    <th><?php echo    'RID';?></th> 
                    <th><?php echo   lang('amount');?></th> 
                    <th><?php echo   lang('order_type'); ?></th> 
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

       
    var fromCurTime;                //从那一天  
    var toCurTime;                // 到某天    
    var rechargelist;
    var timephase = 'today';    //默认日期  今天 
    //When page loads...
    dispalyOrHideCurTimeSelect();
  
    $(document).ready(function(){
        getfirstchartdata();   
    });

    function dispalyOrHideCurTimeSelect()
    {
        var value = document.getElementById('startselect').value;
        if(value=='any')
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
        toCurTime = document.getElementById('dpTimeTo').value;    
        fromCurTime =  get_unix_time(fromCurTime);
        toCurTime =  get_unix_time(toCurTime);
        getfirstchartdata();    
    }


    //日期选择器
    $(function(){
        $( "#dpTimeFrom" ).datetimepicker({
        changeMonth: true,
        dateFormat: "yy-mm-dd", 
        onClose: function( selectedDate ){
        $( "#dpTimeTo" ).datepicker( "option", "minDate", selectedDate );
          }
        });
    });


    $(function(){
        $( "#dpTimeTo" ).datetimepicker({
        changeMonth: true,
        dateFormat: "yy-mm-dd", 
        onClose: function( selectedDate ){
        $( "#dpTimeFrom" ).datepicker( "option", "maxDate", selectedDate );
          }
        });
    });



</script>




<script type="text/javascript">


    function switchTimePhase(time)
    {
        dispalyOrHideCurTimeSelect();  //判断是否 显示 任意时间段 筛选
        timephase=time;    
        if(time!="any")
        {
            getfirstchartdata();   //如果选择 any  就不进行查询数据 图表渲染 
        }
       
    }

    function getfirstchartdata()
    {
        //判断 选择的日期 查询方式
        var myurl="";
        if(timephase=='any')
        {        
            myurl="<?php echo site_url()?>/report/recharge/getrecharData/"+timephase+"/"+fromCurTime+"/"+toCurTime;
        }
        else
        {
            myurl = "<?php echo site_url()?>/report/recharge/getrecharData/"+timephase+"?date="+new Date().getTime();
        }
       renderCharts(myurl);   
    }


</script>



<script type="text/javascript">

    function renderCharts(myurl)
    {   
        var chart_canvas = $('#content1'); 
        var loading_img = $("<img src='<?php echo base_url(); ?>/assets/images/loader.gif'/>"); 
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
            var  list = data.rechargelist;
            rechargelist  = eval(list);
            rechargereportPagination();  
            rechargereportCallback(0,null);
            chart_canvas.unblock();
        });
    }

    //日留存率
    function rechargereportCallback(page_index, jq)
    {    
        page_index = arguments[0] ? arguments[0] : "0";
        jq = arguments[1] ? arguments[1] : "0";   
        var index = page_index*10;
        var pagenum = 10;    
        var msg = "";
        for(i=0;i<pagenum && (index+i)<rechargelist.length ;i++)   
        { 
            msg = msg+"<tr><td>";
            msg = msg+ rechargelist[i+index].date;
            msg = msg+"</td><td>";
            msg = msg+ rechargelist[i+index].time;
            msg = msg+"</td><td>";
            msg = msg+ rechargelist[i+index].uid;
            msg = msg+"</td><td>";
            msg = msg+ rechargelist[i+index].role_id;
            msg = msg+"</td><td>";
            msg = msg+ rechargelist[i+index].price;
            msg = msg+"</td><td>";
            msg = msg+ rechargelist[i+index].ordertype;
            msg = msg+ "</td></tr>";
        }
        $('#content1').html(msg);         
 }

    function rechargereportPagination()
    {
        var num_entries = (rechargelist.length)/10;
        $("#pagination1").pagination(num_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo  lang('g_previousPage')?>',
        next_text: '<?php echo  lang('g_nextPage')?>',
        num_display_entries: 4,
        callback: rechargereportCallback,
        items_per_page:1
        });
    }


    function exportphasetime()    //导出CSV格式
    {
        window.location.href="<?php echo site_url()?>/report/recharge/orderphaseexport/"+timephase+"/"+fromCurTime+"/"+toCurTime;
    }


</script>