<!--充值数据统计-->
<section class="section_maeginstyle" id="highchart">
    <article class="module width_full">
        <header>
            <h3 class="h3_fontstyle"> <?php echo  lang('top_up_data_statistics'); ?> </h3>
         <select style="position: relative; top: 5px;" onchange="switchTimePhase(this.options[this.selectedIndex].value)" id='startselect'>
                <option value=last7days><?php echo  lang('g_last7days')?></option>
                <option value=last30days><?php echo  lang('g_last30days')?></option> 
                <option value=any><?php echo  lang('g_anytime')?></option>
          </select>
          <!-- 任意时间筛选  默认隐藏  slelect any 就会显示出来 -->
          <div id='selectcurTime'>
                <input type="text" id="dpTimeFrom"> <input type="text" id="dpTimeTo">
                <input type="submit" id='timebtn'  value="<?php echo  lang('g_search')?>" class="alt_btn" onclick="onAnyTimeClicked()" >
          </div>
        </header>
        <table class="tablesorter" cellspacing="0"> 
            <thead> 
                <tr> 
                    <th><?php echo  lang('date') ;?></th> 
                    <th><?php echo  '付费账号'; ?></th> 
                    <th><?php echo  '新增付费账号'; ?></th> 
                    <th><?php echo  lang('pay_rate_of_landing'); ?></th> 
                    <th><?php echo  '新增创角付费率'; ?></th> 
                    <th><?php echo  lang('times_of_payment'); ?></th> 
                    <th><?php echo  lang('amount_of_payment'); ?></th> 
                    <th><?php echo  lang('payment_ARPU'); ?></th> 
                    <th><?php echo  lang('landing_ARPU'); ?></th> 
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
     
    var fromCurTime;                 //从那一天  
    var toCurTime;                  // 到某天    
    var timephase = 'last7days';           //默认日期  7天 
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
    //任意时间段选择查询走这里
    function onAnyTimeClicked()
    {    
        fromCurTime = document.getElementById('dpTimeFrom').value;   
        toCurTime = document.getElementById('dpTimeTo').value;   
        getfirstchartdata();    
    }                         

    $(function(){
    $("#dpTimeFrom" ).datepicker({dateFormat: "yy-mm-dd","setDate":new Date()});
    });

    $(function(){
    $( "#dpTimeTo" ).datepicker({ dateFormat: "yy-mm-dd" ,"setDate":new Date()});
    });

    function switchTimePhase(time)
    {
        //判断是否显示任意时间段筛选
        dispalyOrHideCurTimeSelect();  
        timephase=time;    
        if(time!="any")
        {
            getfirstchartdata();   
        }
    }

    function getfirstchartdata()
    {
        //判断 选择的日期 查询方式
        var myurl="";
        if(timephase=='any')
        {        
            myurl="<?php echo site_url()?>/report/recharge/getrechargeStatistics/"+timephase+"/"+fromCurTime+"/"+toCurTime;
        }
        else
        {
            myurl = "<?php echo site_url()?>/report/recharge/getrechargeStatistics/"+timephase+"?date="+new Date().getTime();
        }
        renderCharts(myurl);       
    }
</script>


<script type="text/javascript">

    function renderCharts(myurl)
    {   
        jQuery.getJSON(myurl, null, function(data)
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
            var list = data.rechargelist;
            rechargelist  = eval(list);
            newdatainitPagination(); 
            rechargedataCallback(0,null); 
            chart_canvas.unblock();
        });
    }

    function rechargedataCallback(page_index, jq)
    {    
        page_index = arguments[0] ? arguments[0] : "0";
        jq = arguments[1] ? arguments[1] : "0";   
        var index = page_index*10;
        var pagenum = 10;    
        var msg = "";
        var msg = ""; 
        for(i=0;i<pagenum && (index+i)<rechargelist.length ;i++)    
        { 
            msg = msg+"<tr><td>";
            msg = msg+ rechargelist[i+index].date;
            msg = msg+"</td><td>";
            msg = msg+ rechargelist[i+index].ffyh;
            msg = msg+"</td><td>";
            msg = msg+ rechargelist[i+index].newffyh;
            msg = msg+"</td><td>";
            msg = msg+ rechargelist[i+index].dlffl;
            msg = msg+"</td><td>";
            msg = msg+ rechargelist[i+index].zcffl;
            msg = msg+"</td><td>";
            msg = msg+ rechargelist[i+index].ffcs;
            msg = msg+"</td><td>";
            msg = msg+ rechargelist[i+index].ffje;
            msg = msg+"</td><td>";
            msg = msg+ rechargelist[i+index].ffarpu;
            msg = msg+"</td><td>";
            msg = msg+ rechargelist[i+index].dlarpu;
            msg = msg+ "</td></tr>";

        }
        $('#content1').html(msg);
    }

    function newdatainitPagination() 
    {
        var num_entries = (rechargelist.length)/10;
        $("#pagination1").pagination(num_entries, {
         num_edge_entries: 2,
         prev_text: '<?php echo  lang('g_previousPage')?>',
         next_text: '<?php echo  lang('g_nextPage')?>',           
         num_display_entries: 4,
         callback: rechargedataCallback,
         items_per_page:1               
               });
    }

</script>