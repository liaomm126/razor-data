<!-- 用户留存 -->
<section id="main" class="column">
        <article class="module width_full">
        <header>    
        <h3 class="h3_fontstyle">        
        <?php echo  'LTV'; ?></h3>

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
    

<!--         <select style="position: relative; top: 5px;" onchange="switchserverPhase(this.options[this.selectedIndex].value)" id='serverselect'>
        <option value='all'><?php echo '全服'; ?></option>
        <?php  foreach($server as $k=>$v ): ?>                   
        <option value="<?php echo $v['sid_sk']?>"><?php echo $v['sname'];  ?></option>
        <?php  endforeach; ?>   
        </select> -->
             

        <select style="position: relative; top: 5px;" onchange="switchdetailedPhase(this.options[this.selectedIndex].value)" id='detailedselect'>
        <option value='briefly'><?php echo  '概略表'; ?></option>
        <option value="detailed"><?php echo '详细表';?></option>
        </select>
        
        </header>

        <div id="contents">
   
                <table class="tablesorter" cellspacing="0" style="text-align:center;">
                    <thead >
                        <tr style="text-align:center;">
                            <th><?php echo  '日期'?></th>
                            <th><?php echo  '新增角色'?></th>
                            <th><?php echo  '日付费金额'?></th>
                            <th><?php echo  '2日付费金额'?></th>
                            <th><?php echo  '3日付费金额'?></th>
                            <th><?php echo  '4日付费金额'?></th>
                            <th><?php echo  '5日付费金额'?></th>
                            <th><?php echo  '6日付费金额'?></th>
                            <th><?php echo  '7日付费金额'?></th>
                            <th><?php echo  '15日付费金额'?></th>
                            <th><?php echo  '30日付费金额';?></th>
                            <th><?php echo '60日付费金额';?></th> 
                            <th><?php echo  '90日付费金额';?></th>
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

        $("#contents").html('<table class="tablesorter" cellspacing="0" style="text-align:center;"> <thead > <tr style="text-align:center;"><th><?php echo  '日期'?></th> <th><?php echo  '新增角色'?></th>  <th><?php echo  '首日付费金额'?></th>  <th><?php echo  '2日付费金额'?></th>  <th><?php echo  '3日付费金额'?></th>  <th><?php echo  '4日付费金额'?></th>   <th><?php echo  '5日付费金额'?></th> <th><?php echo  '6日付费金额'?></th><th><?php echo  '7日付费金额'?></th>  <th><?php echo  '15日付费金额'?></th><th><?php echo  '30日付费金额';?></th> <th><?php echo '60日付费金额';?></th> <th><?php echo  '90日付费金额';?></th> </tr> </thead> <tbody id="daydata">  </tbody>  </table><footer> <div id="daypage" class="submit_link"></div></footer>');
    }
    else
    {

        $("#contents").html('<div style="width:100%;height:auto;overflow-x:scroll;">  <table class="tablesorter1" style="text-align: center;" cellspacing="0"><thead> <tr><th><?php echo '日期'?></th><th><?php echo '新增角色' ?></th> <th><?php echo '首日付费金额'?></th><th><?php echo '2日付费金额'?></th><th><?php echo '3日付费金额'  ?></th><th><?php echo '4日付费金额' ?></th>  <th><?php echo '5日付费金额'  ?></th><th><?php echo '6日付费金额'?></th> <th><?php echo '7日付费金额'?></th><th><?php echo '8日付费金额'?></th> <th><?php echo '9日付费金额'?></th><th><?php echo '10日付费金额'?></th> <th><?php echo '11日付费金额'?></th><th><?php echo '12日付费金额'?></th><th><?php echo '13日付费金额' ?></th> <th><?php echo '14日付费金额'?></th> <th><?php echo '15日付费金额'?></th> <th><?php echo '16日付费金额'?></th> <th><?php echo '17日付费金额'?></th><th><?php echo '18日付费金额'?></th><th><?php echo '19日付费金额'?></th><th><?php echo '20日付费金额'?></th><th><?php echo '21日付费金额'?></th><th><?php echo '22日付费金额'?></th><th><?php echo '23日付费金额'?></th><th><?php echo '24日付费金额'?></th><th><?php echo '25日付费金额'?></th><th><?php echo '26日付费金额'?></th><th><?php echo '27日付费金额'?></th><th><?php echo '28日付费金额'?></th><th><?php echo '29日付费金额'?></th><th><?php echo '30日付费金额';?></th>  <th><?php echo '60日付费金额';?></th>  <th><?php echo '90日付费金额';?></th> </tr></thead>  <tbody id="daydata"> </tbody></table> </div> <footer> <div id="daypage" class="submit_link"></div></footer>'); 
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
            myurl="<?php echo site_url()?>/report/lifetimevalue/getlifetimevalue/"+timephase+"/"+fromCurTime+"/"+toCurTime+"/"+opdetailed;
        }
        else
        {
            myurl = "<?php echo site_url()?>/report/lifetimevalue/getlifetimevalue/"+timephase+"/"+opdetailed;
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
            daytr = daytr + '<strong>' + dayuserdata[i+index].day1 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day2 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day3 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day4 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day5 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day6 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day7 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day15 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day30 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day60 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day90 + '</strong>';

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
            daytr = daytr + '<strong>' + dayuserdata[i+index].day1 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day2 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day3 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day4 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day5 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day6 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day7 + '</strong>';

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day8 + '</strong>';

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day9 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day10 + '</strong>';            
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day11 + '</strong>';

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day12 + '</strong>';

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day13 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day14 + '</strong>';

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day15 + '</strong>';
          
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day16 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day17 + '</strong>';
             daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day18 + '</strong>';
                                
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day19 + '</strong>';
           
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day20 + '</strong>';
           
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day21 + '</strong>';
           
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day22 + '</strong>';
           
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day23 + '</strong>';
           
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day24 + '</strong>';
           

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day25 + '</strong>';
           

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day26 + '</strong>';
           

            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day27 + '</strong>';
           
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day28 + '</strong>';
        
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day29 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day30 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day60 + '</strong>';
            daytr = daytr + "</td><td>";
            daytr = daytr + '<strong>' + dayuserdata[i+index].day90 + '</strong>';
            daytr = daytr + "</td></tr>";
        }
        $('#daydata').html(daytr);               
        return false;
    }


</script>
