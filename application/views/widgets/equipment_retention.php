<!-- 设备留存 -->
<section class="column" style="height: 900px;" id="highchart">
        <article class="module width_full">
        <header>    
        <h3 class="h3_fontstyle">        
        <?php echo  lang('retention_rates_of_equipment'); ?></h3>
        </header>
        <div id="contents">
            <div id="tab0" class="tab_content">
                <table class="tablesorter" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php echo  lang('v_rpt_ur_firstUseDay')?></th>
                            <th><?php echo  lang('new_equipment')  ?></th>
                            <th><?php echo  lang('v_rpt_ur_one_days')?></th>
                            <th><?php echo  lang('v_rpt_ur_two_days')?></th>
                            <th><?php echo  lang('v_rpt_ur_three_days')?></th>
                            <th><?php echo  lang('v_rpt_ur_four_days')?></th>
                            <th><?php echo  lang('v_rpt_ur_five_days')?></th>
                            <th><?php echo  lang('v_rpt_ur_six_days')?></th>
                            <th><?php echo  lang('v_rpt_ur_seven_days')?></th>
                            <th><?php echo  lang('v_rpt_ur_shisi_days')?></th>

                        </tr>
                    </thead>
                    <tbody id='daydata'>
                    </tbody>
                </table>
                <footer>
                    <div id="daypage" class="submit_link"></div>
                </footer>
            </div>
        </div>
    </article>
</section>


<script type="text/javascript">

    var dayuserdata;
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

<script type="text/javascript">

    $(document).ready(function() {    
        var userurl  = "<?php echo site_url(); ?>/report/retained/getretainedweekMonthData";
        renderUserData(userurl);
    });
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