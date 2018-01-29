<!--显示在线数据统计-->
<section id="main" class="column" style="height:1700px">

    <article class="module width_full">
        <header>
        <h3 class="h3_fontstyle"> <?php echo  '分日数据' ?> </h3>
    <select style="position: relative; top: 5px;" onchange="switchTimePhase(this.options[this.selectedIndex].value)" id='startselect'>
                <option value=last7days><?php echo  lang('g_last7days')?></option>
                 <option value=last14days><?php echo '过去14天';?></option> 
                <option value=last30days><?php echo  lang('g_last30days')?></option> 
                <option value=any><?php echo  lang('g_anytime')?></option>
            </select>
            <!-- 任意时间筛选  默认隐藏  slelect any 就会显示出来 -->
          <!-- 任意时间筛选  默认隐藏  slelect any 就会显示出来 -->
          <div id='selectcurTime'>
                <input type="text" id="dpTimeFrom"> <input type="text" id="dpTimeTo">
                <input type="submit" id='timebtn'  value="<?php echo  lang('g_search')?>" class="alt_btn" onclick="onAnyTimeClicked()" >
          </div>


            <select style="position: relative; top: 5px;" onchange="switchserverPhase(this.options[this.selectedIndex].value)" id='serverselect'>
             <option value='all'><?php echo  '全服' ?></option>
            <?php  foreach($server as $k=>$v ): ?>                   
            
            <option value="<?php echo $v['sid_sk']?>"><?php echo $v['sname']  ?></option>
            
            <?php  endforeach; ?>   

            </select>




			<span class="relative r">
			<a href="javascript:void(0)" onclick="exportphasetime()"  class="bottun4 hover" >
			<font><?php echo  lang('g_exportToCSV');?></font></a>
			</span> 
        </header>
        <!-- header 结束 -->

    <div style="width:100%;height:auto;overflow-x:scroll;">  
        <table class="tablesorter1" cellspacing="0"  style="text-align: center;" > 
            <thead > 
				<th ><?php echo  lang('date');?></th> 
				<th ><?php echo  '区服'; ?></th> 
				<th ><?php echo  '日登陆账号数';?></th> 
				<th ><?php echo  '日新增账号(DNU)'; ?></th> 
				<th ><?php echo  '日活跃账号(DAU)'; ?></th> 
				<th ><?php echo  '日均使用次数'; ?></th> 
				<th ><?php echo  '平均同时在线(ACU)';?></th> 
				<th ><?php echo  '最高同时在线(PCU)'; ?></th> 
				<th ><?php echo  '平均在线时长(分)'; ?></th> 
				<th ><?php echo  '付费用户'; ?></th> 
				<th ><?php echo  '新增付费账号人数'; ?></th> 
				<th ><?php echo  '登陆付费率'; ?></th> 
				<th ><?php echo  '注册付费率'; ?></th> 
				<th ><?php echo  '付费次数'; ?></th> 
				<th ><?php echo  '付费金额'; ?></th> 
				<th ><?php echo  '付费账号ARPU'; ?></th> 
				<th ><?php echo  '登陆账号ARPU'; ?></th> 
				<th ><?php echo  '次日存留率'; ?></th> 
				<th ><?php echo  '3日存留率'; ?></th> 
				<th ><?php echo  '7日存留率'; ?></th> 
                </tr> 
            </thead> 
            <tbody id="content1">            
            </tbody>
        </table> 
      </div>
        <footer>
        <div id="pagination1"  class="submit_link"></div>
        </footer>

    </article>
</section>


<script>

    var fromCurTime;                 //从那一天  
    var toCurTime;                  // 到某天    
    var timephase = 'last7days';           //默认日期  7天 
    var server = 'all';


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





    function exportphasetime()    //导出CSV格式
    {
        window.location.href="<?php echo site_url()?>/report/daydata/daydatalistcsv/"+timephase+"/"+fromCurTime;
    }


    
    function getfirstchartdata()
    {
            
        //判断 选择的日期 查询方式
        var myurl="";
        if(timephase=='any')
        {        
            myurl="<?php echo site_url()?>/report/datedata/datedatalist/"+timephase+"/"+fromCurTime+"/"+toCurTime+"/"+server;
        }
        else
        {
            myurl = "<?php echo site_url()?>/report/datedata/datedatalist/"+timephase+"/"+server;
        }
       renderCharts(myurl);      
    }
</script>


<script type="text/javascript">

    function renderCharts(myurl)
    {   

        jQuery.getJSON(myurl, null, function(data){   
            var chart_canvas = $('#content1');  //获取对象
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

        var list  = eval(data) ;
        rechargelist	=  list.datedata;
        console.log('rechargelist',rechargelist);
        newdatainitPagination(); 
        //调用表格函数
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

        for(i=0;i<pagenum && (index+i)<rechargelist.length ;i++)    
        { 
			msg = msg+"<tr><td>";
			msg = msg+ rechargelist[i+index].date;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].sname;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].rdlyh;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].rxzyh;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].rhyyh;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].rjxycs;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].ACU;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].PCU;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].rjzxsc;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].ffyh;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].xzffyh;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].dlffl;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].zcffl;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].ffcs;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].ffje;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].ffARPU;
			msg = msg+"</td><td>";
			msg = msg+ rechargelist[i+index].dlARPU;
			msg = msg+"</td><td>";
			msg = msg+ '<strong>' + rechargelist[i+index].day1 + '</strong>  (' +  ( isNaN (rechargelist[i+index].day1 / rechargelist[i+index].rdlyh * 100) ? 0 : (rechargelist[i+index].day1 / rechargelist[i+index].rdlyh * 100) ).toFixed(1) +  '%)';
			msg = msg+"</td><td>";
			msg = msg+ '<strong>' + rechargelist[i+index].day3 + '</strong>  (' +  ( isNaN (rechargelist[i+index].day3 / rechargelist[i+index].rdlyh * 100) ? 0 : (rechargelist[i+index].day3 / rechargelist[i+index].rdlyh * 100) ).toFixed(1) +  '%)';
			msg = msg+"</td><td>";
			msg = msg+ '<strong>' + rechargelist[i+index].day7 + '</strong>  (' +  ( isNaN (rechargelist[i+index].day1 / rechargelist[i+index].rdlyh * 100) ? 0 : (rechargelist[i+index].day7 / rechargelist[i+index].rdlyh * 100) ).toFixed(1) +  '%)';
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
