<section id="main" class="column">
  <article class="module width_full">
    <header>
      <h3 class="tabs_involved"><?php echo '设备流失统计'; ?></h3>
             <select style="position: relative; top: 5px;"
                onchange="switchTimePhase(this.options[this.selectedIndex].value)"
                id='startselect'>
                <option value=today selected><?php echo  lang('g_today')?></option>    
                <option value=yestoday><?php echo  lang('g_yesterday')?></option>
                <option value=last7days><?php echo  lang('g_last7days')?></option>
                <option value=last30days><?php echo  lang('g_last30days')?></option>
                <option value=anythin><?php echo  lang('g_anytime')?></option>
            </select>
            <div id='selectcurTime'>
                <input type="text" id="dpTimeFrom"> <input type="text" id="dpTimeTo">
                <input type="submit" id='timebtn'
                    value="<?php echo  lang('g_search')?>" class="alt_btn"
                    onclick="onAnyTimeClicked()">
            </div>   
    </header>
    <table class="tablesorter" cellspacing="0"> 
      <thead> 
        <tr> 
            <th><?php echo  '节点';?></th> 
            <th><?php echo  '达成设备';?></th>
            <th><?php echo  '流失设备'; ?></th>  
            <th><?php echo  '流失率'; ?></th>  
   
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


var  timephase = 'today';


    $(document).ready(function(){  
      getfirstchartdata();
    });

    function newdatapageselectCallback(page_index, jq){     
      page_index = arguments[0] ? arguments[0] : "0";
      jq = arguments[1] ? arguments[1] : "0";   
      var index = page_index*<?php echo PAGE_NUMS?>;
      var pagenum = <?php echo PAGE_NUMS?>; 
      var msg = ""; 
      for(i=0;i<pagenum && (index+i)<datanewlist.length ;i++)
      { 
        msg = msg+"<tr><td>";
        msg = msg +datanewlist[i+index].jhsb ; 
        msg = msg+"</td><td>";
        msg = msg+ datanewlist[i+index].dcsb; 
        msg = msg+"</td><td>";
        msg = msg+ datanewlist[i+index].lossnum;   
        msg = msg+"</td><td>";
        msg = msg+ datanewlist[i+index].loss;   
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
            myurl="<?php echo site_url()?>/report/devicedrain/getdevicedrain/"+timephase+"/"+fromTime+"/"+toTime;
        }
        else
        {
            myurl = "<?php echo site_url()?>/report/devicedrain/getdevicedrain/"+timephase+"?date="+new Date().getTime();
        }
        renderdata(myurl); 
    }


    function  renderdata(myurl)
    {
        //选择要锁住的对象
        var chart_canvas = $('#content');  
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

        var  list = data
        datanewlist  = eval(list.devicedrain);
        console.log('datanewlist',datanewlist);
        newdatainitPagination(); 
        newdatapageselectCallback(0,null);

        });


        
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
        toCurTime = document.getElementById('dpTimeTo').value;    
        fromTime =  get_unix_time(fromCurTime);
        toTime =  get_unix_time(toCurTime);
        getfirstchartdata();    
    }    

</script>



<script type="text/javascript">

    $(function(){
        $( "#dpTimeFrom" ).datetimepicker({
            changeMonth: true,
            dateFormat: "yy-mm-dd", 
            onClose: function( selectedDate ){
            $( "#dpTimeTo" ).datepicker( "option", "minDate", selectedDate );
        }
        });
    });

    $(function() {
        $( "#dpTimeTo" ).datetimepicker({
            changeMonth: true,
            dateFormat: "yy-mm-dd", 
            onClose: function( selectedDate ){
            $( "#dpTimeFrom" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });

</script>

