<!--显示实时数据统计-->
<section id="main" class="column" style="height:1700px">

  <article class="module width_full">
        <header>
        <h3 class="h3_fontstyle"> <?php echo  '付费排行榜' ?> </h3>
            <select style="position: relative; top: 5px;"  onchange="switchTimePhase(this.options[this.selectedIndex].value)"  id='startselect'>
                <option value=last7days><?php echo  lang('g_last7days')?></option>
                <option value=last14days><?php echo '过去14天';?></option> 
                <option value=last30days><?php echo  lang('g_last30days')?></option> 
                <option value=any><?php echo  lang('g_anytime')?></option>
            </select>
            <!-- 任意时间筛选  默认隐藏  slelect any 就会显示出来 -->
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



        </header>
        <!-- header 结束 -->

    <div id="contents">
        <table class="tablesorter" cellspacing="0"  style="text-align:center;" > 
            <thead > 
                <th> <?php echo  '序号'; ?> </th>
                <th ><?php echo  'UID'; ?></th> 
				<th ><?php echo  '服务器'; ?></th>
                <th ><?php echo  'RID'; ?></th> 
 				<th ><?php echo  '累计充值'; ?></th> 
                <th ><?php echo  '平台';?></th> 
                <th ><?php echo  'UID创建时间'; ?></th> 
                <th ><?php echo  'RID创建时间'; ?></th> 
                <th ><?php echo  '角色最后一次登录时间'; ?></th> 
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

	var timephase = 'last7days';   //默认日期  7天 
	var fromCurTime;        //选择时间
    var toCurTime;                  // 到某天    
    var server    = 'all';        //默认全服
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
        //fromCurTime =  get_unix_time(fromCurTime);  //不进行时间戳转换
        toCurTime = document.getElementById('dpTimeTo').value; 
        getfirstchartdata();       
    }     



    $(function() {
    $("#dpTimeFrom" ).datepicker({dateFormat: "yy-mm-dd","setDate":new Date()});
    });

    $(function(){
    $( "#dpTimeTo" ).datepicker({ dateFormat: "yy-mm-dd" ,"setDate":new Date()});
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
        myurl="<?php echo site_url()?>/report/topranking/gettopranking/"+timephase+"/"+fromCurTime+"/"+toCurTime+"/"+server;
        }
        else
        {
            myurl = "<?php echo site_url()?>/report/topranking/gettopranking/"+timephase+"/"+server;
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
            realtimedata    =  list.topranking;
            console.log(realtimedata);    
            newdatainitPagination(); 
            rechargedataCallback(0,null); 
            chart_canvas.unblock();
        });
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


        for(i=0;i<pagenum && (index+i)<realtimedata.length ;i++)    
        { 
            msg = msg+"<tr><td>";
            msg = msg+ realtimedata[i+index].num ; //序号
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].UID;  
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].server;               //RID
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].RID;          //角色创建时间
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].ljcz;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].imp;          //用户创建时间
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].regtime;
            msg = msg+"</td><td>";
            msg = msg+ realtimedata[i+index].roletime;
            msg = msg+"</td><td>";                            
            msg = msg+ realtimedata[i+index].lastlogin;              //最后一次登录时间
            msg = msg+ "</td></tr>";
        }
        $('#content').html(msg);
    }



</script>