  <section id="main" class="column">
  <!-- 图表 -->
          <article class="module width_full">
             <header>
            <h3 class="h3_fontstyle">
            <?php  echo '等级分布统计'; ?></h3>
            <select style="position: relative; top: 5px;"
                onchange="switchTimePhase(this.options[this.selectedIndex].value)"
                id='startselect'>
                <option value=today selected><?php echo  lang('g_today')?></option>    
                <option value=yestoday><?php echo  lang('g_yesterday')?></option>
                <option value=anythin><?php echo  lang('g_anytime')?></option>
            </select>
            <div id='selectcurTime'>
                <input type="text" id="dpTimeFrom"> 
                <input type="submit" id='timebtn'  value="<?php echo  lang('g_search')?>" class="alt_btn"  onclick="onAnyTimeClicked()">
            </div>

            <select style="position: relative; top: 5px;" onchange="switchserverPhase(this.options[this.selectedIndex].value)" id='serverselect'>
      <!--        <option value='all'><?php echo  '全服' ?></option> -->
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
        <div class="tab_container">
            <div id="tab1" class="tab_content">
                <div class="module_content">
                    <div id="container" class="module_content" style="height: 300px; margin: 10px 3% 1% 3%;"> </div>  <!-- 图表生成处 -->
                </div>
                <div class="clear"></div>
            </div>
        </div>
</article>

<!-- 表格 -->
    <article class="module width_full">
    <div style="width:100%;height:100%;">

        <table class="tablesorter" cellspacing="0"> 
          <thead> 
            <tr> 
                <th><?php echo  '等级';?></th> 
                <th><?php echo  '数量'; ?></th>
                <th><?php echo  '占比'; ?></th>  
            </tr> 
          </thead> 
          <tbody id="content1">        
          </tbody>
        </table> 
        <footer>
        <div id="pagination1"  class="submit_link">
        </div>
        </footer>
    </div>
</article>

</section>






<script>

    var realname='<?php echo "等级分布统计" ?>'; 
    var chartdata;          
    var fromCurTime;        //天数  
    var timephase = 'today';        //默认日期  今天 
    var server =  1;
    //When page loads...
    dispalyOrHideCurTimeSelect();
    //打开页面运行
    $(document).ready(function() {
        getfirstchartdata();        
    });

    //处理时间显示
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


    function switchserverPhase(serverselect)
    {

        dispalyOrHideCurTimeSelect();  //判断是否显示任意时间段筛选
        server = serverselect;  

        if(timephase == "anythin")
        {
            getfirstchartdata();   //如果选择any就不进行查询数据 
        }else
        {
            getfirstchartdata();   //如果选择any就不进行查询数据 
        }

    }



</script>



<script type="text/javascript">
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
        fromCurTimenew = document.getElementById('dpTimeFrom').value; 
        //不允许错误操作
        if( fromCurTimenew =="" ) 
            {
                alert('请选择时间');
                return false;
            }  


        fromCurTime  =  fromCurTimenew  //不经过格式化
        //fromCurTime =  get_unix_time(fromCurTimenew);
        getfirstchartdata();    
        //跳转到 父窗口方法中dealgettime  刷新表格内的其他的参数
        //window.parent.dealgettime(timephase, fromCurTime);   
    }    


    //处理日期选择
    $(function(){
        $( "#dpTimeFrom" ).datepicker({
        //    dateFormat:'yy-mm-dd',
         //   showSecond: true, //显示秒
         //   timeFormat: 'HH:mm:ss',//格式化时间
          //  stepHour: 1,//设置步长
           // stepMinute: 1,
           // stepSecond: 1
        });



    });
    //日期格式化
   $( "#dpTimeFrom" ).datepicker({ dateFormat: "yy-mm-dd" });



    //判断是否显示任意时间段 
    function switchTimePhase(time)
    {
        dispalyOrHideCurTimeSelect();  
        timephase=time;    
        if(time!="anythin")
        {
            getfirstchartdata();
           //window.parent.dealgettime(timephase, fromCurTime); 
        }
    }
    
    //判断 选择的日期 查询方式
    function getfirstchartdata()
    {
        var myurl="";
        if(timephase=='anythin')
        {        
            myurl="<?php echo site_url()?>/report/gradelist/gradedatalist/"+timephase+"/"+fromCurTime+"/"+server;
        }
        else
        {
            myurl = "<?php echo site_url()?>/report/gradelist/gradedatalist/"+timephase+"/"+server;
        }
        renderCharts(myurl);      
    }


</script>



<!-- 配置绘图选项  -->
<script type="text/javascript">

    var chart;
    var options;
    var chartdata;    

    $(document).ready(function(){
    options = {
            chart:{
                renderTo:'container',
                type: 'line',
            },
            title:{
                text:'null'
            },
            xAxis:{
                //刻度线的宽度 默认是1 
                tickWidth:1,
                //轴标签（显示在刻度上的数值或者分类名称）
                labels:{
                    rotation:0,y:20,x:0,
                    formatter:function(){
                    return this.value;
                    },
                    staggerLines: 1
                },
                overflow: 'justify',
                //X坐标轴的标题配置
                title:{
                    enabled: false, //不显示
                    style:{
                    fontWeight:'Arial'
                    },
                }
            },
            yAxis:{
                gridLineColor: '#C0C0C0',
                title:{
                    text:'数量'
                },
                labels:{
                    // 标签格式化  
                    formatter:function(){  
                    return this.value;  
                    }
                },
                min:0,//最小初始值
            },
            //提示框配置
            tooltip: {
                //自定义十字准线
                //crosshairs: true,
                //配置十字准线
                crosshairs:{
                    width: 2,
                    color: 'gray',
                    dashStyle: 'shortdot'
                },
                valueSuffix: '个',
                borderWidth: 0,//无边框
                //自定义格式
                formatter:function (){
                var s = '等级: '+this.x;
                //循环输出
                $.each(this.points, function (){
                s += '<br/>' + '数量' + ': ' +  this.y ;
                s += '<br>' + '占比'+': '+ ( isNaN ( this.y / num )*100 ? 0 : ( this.y / num )*100 ).toFixed(1) + '%';
                });
                return s;
                },
                //共享数据提示
                shared: true
            },
            //不导出
            exporting:{
                enabled:false
            },
            plotOptions: {
                spline: {
                marker: {
                radius: 1,
                lineColor: '#666666',
                lineWidth: 1
                }
                }
            },
            legend:{
            //showInLegend: true,  
            },
            credits: {
                enabled: false
            },
            series: [{                
                marker: {
                symbol: 'circle'
                }
            }]
        };
    });



    function renderCharts(myurl)
    { 
        //选择要绘制的div的id对象   
        var chart_canvas = $('#container');  
        var loading_img = $("<img src='<?php echo base_url();?>/assets/images/loader.gif'/>");
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
        //根据选择的时间进行筛选调用数据进行显示
        jQuery.getJSON(myurl, null, function(data){   
            chartdata=data.gradelist.level_info;  

            newdatainitPagination(); 
            newdatapageselectCallback(0,null);

            var categories = [];            
            var newUsers = [];
            var obj = chartdata.d;  //data
            num = chartdata.c;
            // 总数 alert(num);
            for(var i=0;i<obj.length;i++)
            {
                newUsers.push(obj[i][1]);   //各等级人数
                categories.push(obj[i][0]);     //等级分类
            }
            //数据列渲染
            options.series[0].data = newUsers;  
            options.xAxis.labels.step = parseInt(categories.length/25);   //处理每隔几个显示
            options.xAxis.categories = categories;  
            options.series[0].name = '等级分布统计';  //当前的数据列名字
            options.title.text = '等级分布统计';   //标题
            chart = new Highcharts.Chart(options);     //进行图表配置进行渲染   


            chart_canvas.unblock(); //图表释放
        });  
    }



    function exportphasetime()    //导出CSV格式
    {
        window.location.href="<?php echo site_url()?>/report/gradelist/gradelistcsv/"+timephase+"/"+fromCurTime+"/"+server;
    }


</script>





<script type="text/javascript">

    function newdatapageselectCallback(page_index, jq)
    {     
        page_index = arguments[0] ? arguments[0] : "0";
        jq = arguments[1] ? arguments[1] : "0";   
        var index = page_index*50;
        var pagenum = 50
        var msg = ""; 
        var gradelist = chartdata.d;
        var totalnum  = chartdata.c;
        for(i=0;i<pagenum && (index+i)<gradelist.length ;i++)
        { 
            msg = msg+"<tr><td>";
            msg = msg + gradelist[i+index]['0'];  
            msg = msg+"</td><td>";
            msg = msg+ gradelist[i+index]['1'];   
            msg = msg+"</td><td>";
            msg = msg+ ( isNaN (gradelist[i+index]['1'] / totalnum )*100 ? 0 : (gradelist[i+index]['1'] / totalnum)*100  ).toFixed(2) +  '%';
            msg = msg+"</td></tr>";
        }

        msg = msg+"<tr><td>";
        msg = msg + '总计';  
        msg = msg+"</td><td>";
        msg = msg + totalnum;  
        msg = msg+"</td><td>";
        msg = msg+ (totalnum ? '100.0%' :'0.0%') ;
        msg = msg+"</td></tr>";
        $('#content1').html(msg);     
        return false;
    } 

    function newdatainitPagination() 
    {
        var num_entries = (chartdata.d.length)/50;
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