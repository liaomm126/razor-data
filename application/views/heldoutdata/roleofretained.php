<!-- 角色留存 -->
<section id="main" class="column">
        <article class="module width_full">
        <header>    
        <h3 class="h3_fontstyle">        
        <?php echo  lang('retention_rates_of_role'); ?></h3>

        <select style="position: relative; top: 5px;" onchange="switchTimePhase(this.options[this.selectedIndex].value)" id='startselect'>
                <option value=last7days><?php echo  lang('g_last7days')?></option>
                <option value=last14days><?php echo '过去14天';?></option> 
                <option value=last30days><?php echo  lang('g_last30days')?></option> 
                <option value=any><?php echo  lang('g_anytime')?></option>
        </select>

        <div id='selectcurTime'>
            <input type="text" id="dpTimeFrom"> 
            <input type="text" id="dpTimeTo">
            <input type="submit" id='timebtn'  value="<?php echo  lang('g_search')?>" class="alt_btn" onclick="onAnyTimeClicked()" >
        </div>
    

        <select style="position: relative; top: 5px;" onchange="switchserverPhase(this.options[this.selectedIndex].value)" id='serverselect'>
        <option value='all'><?php echo '全服'; ?></option>
        <?php  foreach($server as $k=>$v ): ?>                   
        <option value="<?php echo $v['sid_sk']?>"><?php echo $v['sname'];  ?></option>
        <?php  endforeach; ?>   
        </select>
             

        <select style="position: relative; top: 5px;" onchange="switchdetailedPhase(this.options[this.selectedIndex].value)" id='detailedselect'>
        <option value='briefly'><?php echo  '概略表'; ?></option>
        <option value="detailed"><?php echo '详细表';?></option>

        </select>
        </header>

        <div id="contents">
   
                <table class="tablesorter" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo  lang('v_rpt_ur_firstUseDay')?></th>
                            <th><?php echo  lang('new_role')  ?></th>
                            <th><?php echo  lang('v_rpt_ur_one_days')?></th>
                            <th><?php echo  lang('v_rpt_ur_two_days')?></th>
                            <th><?php echo  lang('v_rpt_ur_three_days')?></th>
                            <th><?php echo  lang('v_rpt_ur_four_days')?></th>
                            <th><?php echo  lang('v_rpt_ur_five_days')?></th>
                            <th><?php echo  lang('v_rpt_ur_six_days')?></th>
                            <th><?php echo  lang('v_rpt_ur_seven_days')?></th>
                            <th><?php echo  lang('v_rpt_ur_shisi_days')?></th>
                            <th><?php echo  '+30天';?></th>
                            <th><?php echo  '+90天';?></th>
                        </tr>
                    </thead>
                    <tbody id='daydata'>
                    </tbody>
                </table>
                <footer>
                    <div id="daypage" class="submit_link"></div>
                </footer>
     </div>


    </article>
</section>




<script type="text/javascript">

    var fromCurTime;                 //从那一天  
    var toCurTime;                  // 到某天    
    var timephase = 'last7days';   //默认日期  7天 
    var server    = 'all';        //默认全服
    var opdetailed = 'briefly';   //默认缩略表



    $(document).ready(function(){
        getfirstchartdata();            
    });


    //显示任意时间
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


    //任意时间段选择查询  走这里
    function onAnyTimeClicked()
    {    
        fromCurTime = document.getElementById('dpTimeFrom').value; 
        toCurTime = document.getElementById('dpTimeTo').value;    
        getfirstchartdata();       
    } 

    $(function() {
    $("#dpTimeFrom" ).datepicker({dateFormat: "yy-mm-dd","setDate":new Date()});
    });

    $(function(){
    $( "#dpTimeTo" ).datepicker({ dateFormat: "yy-mm-dd" ,"setDate":new Date()});
    });


    //日期选项
    function switchTimePhase(time)
    {
        dispalyOrHideCurTimeSelect();  //判断是否显示任意时间段筛选
        timephase=time;    
        if(time!="any")
        {
            getfirstchartdata();   //如果选择any就不进行查询数据 
        }
    }


    //判断服务器
    function switchserverPhase(serverselect)
    {

        dispalyOrHideCurTimeSelect();  //判断是否显示任意时间段筛选
        server = serverselect;  
        if(timephase == "any")
        {
            getfirstchartdata();   //如果选择any就不进行查询数据 
        }else
        {
            getfirstchartdata();   //如果选择any就不进行查询数据 
        }

    }


    //判断概略表
    function switchdetailedPhase(option)
    {

        

    if(option == 'briefly')
    {

        $("#contents").html('<table class="tablesorter" style="text-align: center;" cellspacing="0"><thead> <tr><th><?php echo  lang('v_rpt_ur_firstUseDay')?></th><th><?php echo '新增角色'  ?></th> <th><?php echo  lang('v_rpt_ur_one_days')?></th><th><?php echo  lang('v_rpt_ur_two_days')?></th><th><?php echo  lang('v_rpt_ur_three_days')?></th><th><?php echo  lang('v_rpt_ur_four_days')?></th>  <th><?php echo  lang('v_rpt_ur_five_days')?></th><th><?php echo  lang('v_rpt_ur_six_days')?></th> <th><?php echo  lang('v_rpt_ur_seven_days')?></th><th><?php echo  lang('v_rpt_ur_shisi_days')?></th> <th><?php echo  '+30天';?></th>  <th><?php echo  '+90天';?></th> </tr></thead>  <tbody id="daydata"></tbody> </table> <footer> <div id="daypage" class="submit_link"></div></footer>');
    }
    else
    {

        $("#contents").html('<div style="width:100%;height:auto;overflow-x:scroll;">  <table class="tablesorter1" style="text-align: center;" cellspacing="0"><thead> <tr><th><?php echo  lang('v_rpt_ur_firstUseDay')?></th><th><?php echo  '新增角色'  ?></th> <th><?php echo  lang('v_rpt_ur_one_days')?></th><th><?php echo  lang('v_rpt_ur_two_days')?></th><th><?php echo  lang('v_rpt_ur_three_days')?></th><th><?php echo  lang('v_rpt_ur_four_days')?></th>  <th><?php echo  lang('v_rpt_ur_five_days')?></th><th><?php echo  lang('v_rpt_ur_six_days')?></th> <th><?php echo  lang('v_rpt_ur_seven_days')?></th><th><?php echo  '+8天'?></th> <th> <?php echo  '+9天'?></th><th><?php echo  '+10天'?></th> <th><?php echo  '+11天'?></th><th><?php echo  '+12天'?></th><th><?php echo  '+13天'?></th> <th><?php echo  lang('v_rpt_ur_shisi_days')?><th><?php echo  '+15天'?></th> <th><?php echo  '+16天'?><th><?php echo  '+17天'?></th><th><?php echo  '+18天'?></th><th><?php echo  '+19天'?></th><th><?php echo  '+20天'?></th><th><?php echo  '+21天'?></th><th><?php echo  '+22天'?></th><th><?php echo  '+23天'?></th><th><?php echo  '+24天'?></th><th><?php echo  '+25天'?></th><th><?php echo  '+26天'?></th><th><?php echo  '+27天'?></th><th><?php echo  '+28天'?></th><th><?php echo  '+29天'?></th></th></th>  <th><?php echo  '+30天';?></th>  <th><?php echo  '+60天';?></th>  <th><?php echo  '+90天';?></th> </tr></thead>  <tbody id="daydata"> </tbody></table> </div> <footer> <div id="daypage" class="submit_link"></div></footer>'); 
    }

       

        dispalyOrHideCurTimeSelect();  //判断是否显示任意时间段筛选
        opdetailed = option;  
        if(timephase == "any")
        {
            getfirstchartdata();   //如果选择any就不进行查询数据 
        }else
        {
            getfirstchartdata();   //如果选择any就不进行查询数据 
        }
    }




    function getfirstchartdata()
    {
            
        //判断 选择的日期 查询方式
        var myurl="";
        if(timephase=='any')
        {        
            myurl="<?php echo site_url()?>/report/roleofretained/Roleofretainedlist/"+timephase+"/"+fromCurTime+"/"+toCurTime+"/"+server+"/"+opdetailed;
        }
        else
        {
            myurl = "<?php echo site_url()?>/report/roleofretained/Roleofretainedlist/"+timephase+"/"+server+"/"+opdetailed;
        }
       renderUserData(myurl);      
    }



</script>



<script type="text/javascript">


    
    var dayobj;
    function renderUserData(myurl)
    {    
        var chart_canvas = $('#contents');
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
        jQuery.getJSON( myurl, null, function(data){

            dayobj=data.userremainday;  

            //alert( data.opdetailed );
            
            //alert( dayobj.length );
            
            if(data.opdetailed == "briefly")
            {
                var daytr="";
                dayuserdata=eval(dayobj);
                dayinitPagination();
                pageselectdayCallback(0,null);
            }

            if(data.opdetailed  == 'detailed')
            {

                var daytr="";
                dayuserdata=eval(dayobj);
                dayserverinitPagination();
                pageserverCallback(0,null);

            }


            chart_canvas.unblock();  

        });
    }





</script>




<script type="text/javascript">



    function pageselectdayCallback(page_index, jq)
    {        
        page_index = arguments[0] ? arguments[0] : "0";
        jq = arguments[1] ? arguments[1] : "0";   
        var index = page_index*<?php echo PAGE_NUMS?>;
        var pagenum = <?php echo PAGE_NUMS?>;   
        var daytr = "";
        if (index+pagenum >= dayuserdata.length)
        {
            pagenum = dayuserdata.length % pagenum;
        }
        for(i=0;i<pagenum && (index+i)<dayuserdata.length ;i++)      
        { 
            var start = dayuserdata[i+index].startdate;
            var end   = dayuserdata[i+index].enddate;
            var showtime = start;
            daytr = daytr+"<tr><td>";
            daytr = daytr + showtime;
            daytr = daytr + "</td><td>";
            daytr = daytr + dayuserdata[i+index].usercount;            
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day1 + '</strong>  (' +  ( isNaN(dayuserdata[i+index].day1 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day1 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day2 + '</strong> (' + ( isNaN(dayuserdata[i+index].day2 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day2 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day3 + '</strong> (' + ( isNaN(dayuserdata[i+index].day3 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day3 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day4 + '</strong> (' + ( isNaN(dayuserdata[i+index].day4 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day4 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day5 + '</strong> (' + ( isNaN(dayuserdata[i+index].day5 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day5 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day6 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day6 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day6 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day7 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day7 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day7 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day15 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day15 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day15 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day30 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day30 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day30 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day90 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day90 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day90 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';

            daytr = daytr + "</td></tr>";
        }
        $('#daydata').html(daytr);               
        return false;
    }
  
    function dayinitPagination() 
    {
        var num_entries = (dayuserdata.length)/10;
        $("#daypage").pagination(num_entries, {
            num_edge_entries: 2,
            prev_text: '<?php echo  lang('g_previousPage')?>',
            next_text: '<?php echo  lang('g_nextPage')?>',
            num_display_entries: 4,
            callback: pageselectdayCallback,
            items_per_page:1
        });
    }





    function dayserverinitPagination() 
    {
        var num_entries = (dayuserdata.length)/10;
        $("#daypage").pagination(num_entries, {
            num_edge_entries: 2,
            prev_text: '<?php echo  lang('g_previousPage')?>',
            next_text: '<?php echo  lang('g_nextPage')?>',
            num_display_entries: 4,
            callback: pageserverCallback,
            items_per_page:1
        });
    }
    
    //详细查询
    function pageserverCallback(page_index, jq)
    {        
        page_index = arguments[0] ? arguments[0] : "0";
        jq = arguments[1] ? arguments[1] : "0";   
        var index = page_index*<?php echo PAGE_NUMS?>;
        var pagenum = <?php echo PAGE_NUMS?>;   
        var daytr = "";
        if (index+pagenum >= dayuserdata.length)
        {
            pagenum = dayuserdata.length % pagenum;
        }
        for(i=0;i<pagenum && (index+i)<dayuserdata.length ;i++)      
        { 
            var start = dayuserdata[i+index].startdate;
            var end   = dayuserdata[i+index].enddate;
            var showtime = start;
            daytr = daytr+"<tr><td>";
            daytr = daytr + showtime;
            daytr = daytr + "</td><td>";
            daytr = daytr + dayuserdata[i+index].usercount;            
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day1 + '</strong>  (' +  ( isNaN(dayuserdata[i+index].day1 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day1 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day2 + '</strong> (' + ( isNaN(dayuserdata[i+index].day2 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day2 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day3 + '</strong> (' + ( isNaN(dayuserdata[i+index].day3 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day3 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day4 + '</strong> (' + ( isNaN(dayuserdata[i+index].day4 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day4 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day5 + '</strong> (' + ( isNaN(dayuserdata[i+index].day5 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day5 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day6 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day6 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day6 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day7 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day7 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day7 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day8 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day8 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day8 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day9 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day9 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day9 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day10 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day10 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day10 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';            
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day11 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day11 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day11 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day12 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day12 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day12 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day13 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day13 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day13 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day14 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day14 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day14 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day15 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day15 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day15 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
          
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day16 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day16 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day16 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day17 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day17 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day17 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
             daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day18 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day18 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day18 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
                                
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day19 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day19 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day19 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
           
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day20 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day20 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day20 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
           
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day21 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day21 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day21 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
           
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day22 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day22 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day22 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
           
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day23 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day23 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day23 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
           
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day24 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day24 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day24 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
           

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day25 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day25 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day25 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
           

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day26 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day26 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day26/ dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
           

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day27 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day27 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day27 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
           
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day28 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day28 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day28 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
        
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day29 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day29 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day29 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day30 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day30 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day30 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day60 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day60 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day60 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day90 + '</strong> (' +  ( isNaN(dayuserdata[i+index].day90 / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index].day90 / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
            daytr = daytr + "</td></tr>";
        }
        $('#daydata').html(daytr);               
        return false;
    }


</script>
