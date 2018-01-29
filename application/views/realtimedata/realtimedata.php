<!--显示实时数据统计-->
<section id="main" class="column" style="height:1700px">

  <article class="module width_full">
        <header>
        <h3 class="h3_fontstyle"> <?php echo  '实时数据' ?> </h3>
            <select style="position: relative; top: 5px;"  onchange="switchTimePhase(this.options[this.selectedIndex].value)"  id='startselect'>
                <option value=today selected><?php echo  lang('g_today')?></option>    
                <option value=yestoday><?php echo  lang('g_yesterday')?></option>
                <option value=any><?php echo  lang('g_anytime')?></option>

            </select>
            <!-- 任意时间筛选  默认隐藏  slelect any 就会显示出来 -->
            <div id='selectcurTime'>
                <input type="text" id="dpTimeFrom">
                <input type="submit" id='timebtn'
                    value="<?php echo  lang('g_search')?>" class="alt_btn"
                    onclick="onAnyTimeClicked()">
            </div>
			
		<select style="position: relative; top: 5px;" onchange="switchdetailedPhase(this.options[this.selectedIndex].value)" id='detailedselect'>
            <option value='briefly'><?php echo  '概略表'; ?></option>
            <option value="detailed"><?php echo '详细表';?></option>
        </select>


        </header>
        <!-- header 结束 -->

    <div id="contents">
        <table class="tablesorter" cellspacing="0"  style="text-align:center;" > 
            <thead > 
				<th ><?php echo  '服务器'; ?></th>  
				<th ><?php echo  '日新增设备';?></th> 
				<th ><?php echo  '日登录账号数'; ?></th> 
				<th ><?php echo  '日新增账号'; ?></th> 
				<th ><?php echo  '付费账号'; ?></th> 
				<th ><?php echo  '新增付费人数';?></th> 
				<th ><?php echo  '付费次数'; ?></th> 
 				<th ><?php echo  '付费金额'; ?></th> 
                </tr> 
            </thead> 
            <tbody id="content">            
            </tbody>
        </table> 
      
        <footer>
        <div id="pagination"  class="submit_link"></div>
        </footer>
    </div>

    </article>

</section>


<script type="text/javascript">

	var timephase = 'today';  
	var fromCurTime;        //选择时间
    var opdetailed = 'briefly' ;
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





    //判断概略表
    function switchdetailedPhase(option)
    {

        if(option == 'briefly')
        {
            $("#contents").html(
                    '<table class="tablesorter" cellspacing="0"  style="text-align: center;" > <thead > <th ><?php echo  '服务器'; ?></th>   <th ><?php echo  '日新增设备';?></th>  <th ><?php echo  '日登录用户数'; ?></th>  <th ><?php echo  '日新增用户'; ?></th>  <th ><?php echo  '付费用户'; ?></th> <th ><?php echo  '新增付费人数';?></th>  <th ><?php echo  '付费次数'; ?></th>  <th ><?php echo  '付费金额'; ?></th> </tr>   </thead>  <tbody id="content">             </tbody></table>  <footer> <div id="pagination"  class="submit_link"></div></footer>'
                    );
        }
        else
        {
            $("#contents").html(
                    '<table class="tablesorter" cellspacing="0"  style="text-align: center;" > <thead > <th ><?php echo  '服务器'; ?></th>   <th ><?php echo  '日新增设备';?></th> <th>  <?php echo  '总新增设备(全部）';?></th>   <th ><?php echo  '日登录用户数'; ?></th>  <th ><?php echo  '日新增用户'; ?></th> <th ><?php echo  '总新增用户(全部）'; ?></th> <th ><?php echo  '付费用户'; ?></th> <th ><?php echo  '总付费用户(全部）'; ?></th>  <th ><?php echo  '新增付费人数';?></th>  <th ><?php echo  '付费次数'; ?></th>  <th ><?php echo  '付费金额'; ?></th> <th ><?php echo  '总付费金额(全部）'; ?></th> </tr>   </thead>  <tbody id="content">             </tbody></table>  <footer> <div id="pagination"  class="submit_link"></div></footer>'
                    ); 
        }

        dispalyOrHideCurTimeSelect();  //判断是否显示任意时间段筛选
        opdetailed = option;  
        if(timephase == "any")
        {
            getfirstchartdata();   
        }else
        {
            getfirstchartdata();  
        }
    }




    //格式化日期  转为时间戳
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
       // fromCurTime =  get_unix_time(fromCurTime);  //不进行时间戳转换
        getfirstchartdata();       
    }     



    $(function() {
    $("#dpTimeFrom" ).datepicker({dateFormat: "yy-mm-dd","setDate":new Date()});
    });

    //带时分秒选择
    //  $(function(){
    //     $( "#dpTimeFrom" ).datetimepicker({
    //         changeMonth: true,
    //         dateFormat: "yy-mm-dd"
    //     });
    // });

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
            
        //判断 选择的日期 查询方式
        var myurl="";
        if(timephase=='any')
        {        
            myurl="<?php echo site_url()?>/report/realtimedata/realtimedatalist/"+timephase+"/"+fromCurTime+"/"+opdetailed;
        }
        else
        {
            myurl = "<?php echo site_url()?>/report/realtimedata/realtimedatalist/"+timephase+"/"+opdetailed;
        }
       renderCharts(myurl);      
    }

</script>


<script type="text/javascript">

    function renderCharts(myurl)
    {   
        jQuery.getJSON(myurl, null, function(data){   
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
            var list = data;
            list  = eval(list); //调用 分页   
            realtimedata    =  list.realtimedata;
            realtimedatatotal = list.realtimedatatotal;

            if( data.opdetailed  ==  "briefly" )
            {
                //console.log(realtimedata);    
                newdatainitPagination(); 
                rechargedataCallback(0,null); 
            }


            if( data.opdetailed  ==  "detailed" )
            {
                //console.log(realtimedata);    
                detailedinitPagination(); 
                detailedCallback(0,null); 
            }
            chart_canvas.unblock();
        });
    }

    //详细表分页
    function detailedinitPagination()
    {
        var num_entries = (realtimedata.length)/10;
        $("#pagination").pagination(num_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo  lang('g_previousPage')?>',
        next_text: '<?php echo  lang('g_nextPage')?>',           
        num_display_entries: 4,
        callback: detailedCallback,
        items_per_page:1               
           });
    }



    //详细表数据
    function detailedCallback(page_index, jq)
    {    

        page_index = arguments[0] ? arguments[0] : "0";
        jq = arguments[1] ? arguments[1] : "0";   
        var index = page_index*10;
        var pagenum = 10;    
        var msg = ""; 
        msg = msg+"<tr><td>";
        msg = msg+ '总计(去重)';
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.rxzsb;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.zrxzsb;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.rdlyh;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.rxzyh;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.zrxzyh;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.ffyh;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.zffyh;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.xzffyh;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.ffcs;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.ffje;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.zffje;
        msg = msg+ "</td></tr>";

        for(i=0;i<pagenum && (index+i)<realtimedata.length ;i++)    
        { 
            msg = msg+"<tr><td>";
            msg = msg+ realtimedata[i+index].server;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].rxzsb;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].zrxzsb;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].rdlyh;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].rxzyh;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].zrxzyh;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].ffyh;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].zffyh;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].xzffyh;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].ffcs;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].ffje;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].zffje;
            msg = msg+ "</td></tr>";
        }
        $('#content').html(msg);
    }



    //概略表数据分页
    function newdatainitPagination()
    {
        var num_entries = (realtimedata.length)/10;
        $("#pagination").pagination(num_entries, {
        num_edge_entries: 2,
        prev_text: '<?php echo  lang('g_previousPage')?>',
        next_text: '<?php echo  lang('g_nextPage')?>',           
        num_display_entries: 4,
        callback: rechargedataCallback,
        items_per_page:1               
           });
    }


    //概略表数据
    function rechargedataCallback(page_index, jq)
    {    

        page_index = arguments[0] ? arguments[0] : "0";
        jq = arguments[1] ? arguments[1] : "0";   
        var index = page_index*10;
        var pagenum = 10;    
        var msg = ""; 
        //总计
        msg = msg+"<tr><td>";
        msg = msg+ '总计(去重)';
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.rxzsb;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.rdlyh;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.rxzyh;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.ffyh;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.xzffyh;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.ffcs;
        msg = msg+"</td><td>";
        msg = msg+ realtimedatatotal.ffje;
        msg = msg+ "</td></tr>";

        for(i=0;i<pagenum && (index+i)<realtimedata.length ;i++)    
        { 
            msg = msg+"<tr><td>";
            msg = msg+ realtimedata[i+index].server;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].rxzsb;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].rdlyh;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].rxzyh;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].ffyh;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].xzffyh;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].ffcs;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].ffje;
            msg = msg+ "</td></tr>";
        }
        $('#content').html(msg);
    }



</script>