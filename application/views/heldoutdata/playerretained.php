<!-- 设备留存 -->
<section id="main" class="column">
        <article class="module width_full">
        <header>    
        <h3 class="h3_fontstyle">        
        <?php echo  '付费账号留存数据'; ?></h3>

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
    

        </header>

        <div id="contents">
       <div style="width:100%;height:auto;overflow-x:scroll;">  
                <table class="tablesorter1" cellspacing="0"  style="text-align: center;">
                    <thead>
                        <tr>
                            <th><?php echo  lang('v_rpt_ur_firstUseDay')?></th>
                            <th><?php echo '新增付费账号';  ?></th>
                            <?php 
                            for($i = 1; $i<=31;$i++)
                            {
                                echo '<th>+'.$i.'天</th>';
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody id='daydata'>
                    </tbody>
                </table>
                </div>
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




    function getfirstchartdata()
    {
            
        //判断选择的日期查询方式
        var myurl="";
        if(timephase=='any')
        {        
            myurl="<?php echo site_url()?>/report/playerretained/playerretainedlist/"+timephase+"/"+fromCurTime+"/"+toCurTime;
        }
        else
        {
            myurl = "<?php echo site_url()?>/report/playerretained/playerretainedlist/"+timephase;
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
            var daytr="";
            dayuserdata=eval(dayobj);
            dayinitPagination();
            pageselectdayCallback(0,null);
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
            daytr = daytr + "</td>";

            for(j=1; j <= 31; j++)
            {
                daytr = daytr + '<td><strong>' + dayuserdata[i+index]['day'+j]+ '</strong>  (' +  ( isNaN(dayuserdata[i+index]['day'+j]  / dayuserdata[i+index].usercount * 100) ? 0 : (dayuserdata[i+index]['day'+j]  / dayuserdata[i+index].usercount * 100) ).toFixed(1) +  '%)';
                daytr = daytr + "</td>";
            }

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





</script>
