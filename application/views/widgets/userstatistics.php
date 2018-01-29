<section class="section_maeginstyle" id="highchart"

<?php if (!isset($delete)) {
    ?>
    style="background: url(<?php echo base_url(); ?>assets/images/sidebar_shadow.png) repeat-y left top;"<?php
}?>>
    <article class="module width_full">
        <header>
            <div style="float: left; margin-left: 2%; margin-top: 7px;">
<!-- 添加到控制面板中 -->
    <?php

if (!isset($add)) {
    ?>
    <a href="#" onclick="addreport()"> <img
                    src="<?php echo base_url();?>assets/images/addreport.png"
                    title="<?php echo lang('s_suspend_title')?>" style="border: 0" /></a>
    <?php
} if (isset($delete)) {?>
    <a href="#" onclick="deletereport()"> <img
                    src="<?php echo base_url();?>assets/images/delreport.png"
                    title="<?php echo lang('s_suspend_deltitle')?>" style="border: 0" /></a>
    <?php
}?>     
<!-- 从列表删除  -->
  </div>


  <!-- 日期时间筛选 -->
            <h3 class="h3_fontstyle">
    <?php  echo lang('Distribution_of_online_user'); ?></h3>
            <select style="position: relative; top: 5px;"
                onchange="switchTimePhase(this.options[this.selectedIndex].value)"
                id='startselect'>
                <option value=today selected><?php echo  lang('g_today')?></option>    
                <option value=yestoday><?php echo  lang('g_yesterday')?></option>
                <option value=last7days><?php echo  lang('g_last7days')?></option>
                <option value=last30days><?php echo  lang('g_last30days')?></option>
                <option value=any><?php echo  lang('g_anytime')?></option>
            </select>

              <!-- 任意时间筛选  默认隐藏  slelect any 就会显示出来 -->

            <div id='selectcurTime'>
                <input type="text" id="dpTimeFrom"> <input type="text" id="dpTimeTo">
                <input type="submit" id='timebtn'
                    value="<?php echo  lang('g_search')?>" class="alt_btn"
                    onclick="onAnyTimeClicked()">
            </div>


    
          <!-- tab 选项 切换 -->
<!--             <div style="position: relative; top: -22px">
                <ul class="tabs2">
                    <li><a ct="newUser"
                        href="javascript:changefirstchartName('startuser')"><?php echo  lang('t_activeUsers')?></a></li>
                    <li><a ct="totalUser"
                        href="javascript:changefirstchartName('newuser')"><?php echo  lang('t_newUsers')?></a></li>
                </ul>
            </div>
 -->
        </header>

          <!-- header 结束 -->



        <div class="tab_container">
            <div id="tab1" class="tab_content">
                <div class="module_content">

                    <div id="container" class="module_content" style="height: 300px; margin: 10px 3% 1% 3%;"> </div>      <!-- 图表生成处 -->

                </div>
                <div class="clear"></div>
            </div>
        </div>




    </article>
</section>




<script>


var realname='<?php echo "在线人数" ?>';   //默认选择   活跃用户
var chartdata;          
var fromCurTime;        //从那一天  
var toCurTime;          // 到某天    
var chartname = 'startuser';  //活跃用户
var timephase = 'today';           //默认日期  今天 

console.log('-realname-',realname);



//When page loads...
dispalyOrHideCurTimeSelect();
$(".tab_content").hide(); //Hide all content
$("ul.tabs2 li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content





$(document).ready(function() {
    getfirstchartdata();                                  //最先执行  getfirstchartdata  方法
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
//On Click Event
$("ul.tabs2 li").click(function() {
    $("ul.tabs2 li").removeClass("active"); //Remove any "active" class
    $(this).addClass("active"); //Add "active" class to selected tab
    var activeTab = $(this).find("a").attr("id"); //Find the href attribute value to identify the active tab + content
    $(activeTab).fadeIn(); //Fade in the active ID content
    return true;
});
</script>
<script type="text/javascript">

//任意时间段选择查询  走这里
function onAnyTimeClicked(){    
    fromCurTime = document.getElementById('dpTimeFrom').value;   
    toCurTime = document.getElementById('dpTimeTo').value;    
    getfirstchartdata();    
    var posttime="<?php if (!isset($delete)) {
        echo "postime";
} else {
    echo "";
}?>";    
if (posttime=="postime") {
    //    window.parent.dealgettime('any', fromCurTime, toCurTime);   //跳转到  父窗口 方法中 dealgettime 刷新表格内的其他的参数
}    
}                         
</script>

<script type="text/javascript">
$(function() {
    $( "#dpTimeFrom" ).datepicker();
});
$( "#dpTimeFrom" ).datepicker({ dateFormat: "yy-mm-dd" });
$(function() {
    $( "#dpTimeTo" ).datepicker();
});
$( "#dpTimeTo" ).datepicker({ dateFormat: "yy-mm-dd" });
</script>

<script type="text/javascript">
function changefirstchartName(changename)
{        // changeChartTitleName(timephase,changename);
    // if (changename=="startuser") {        
    //     realname="<?php echo  lang('t_activeUsers')?>";
    // } else {    
    //     realname="<?php echo  lang('t_newUsers')?>";
    // }

    chartname = changename;
    var data = chartdata;
    if (typeof(data.type)!='undefined'&&data.type.name=='compare') {//mean copare
        $.each(data.content, function(index, item) {
            var categories = [];
            var newUsers = [];
            var obj = item.data;
            var realhour;
            for(var i=0;i<obj.length;i++)
            {
                if(chartname=="startuser")
                    newUsers.push(parseInt(obj[i].startusers,10));
                if(chartname=="newuser")
                    newUsers.push(parseInt(obj[i].newusers,10));
                realhour=obj[i].hour+":00";                        
                categories.push(realhour);
            }
            options.series[index]={};
            options.series[index].data = newUsers;
            options.series[index].name=realname;
            options.title.text = titlename;        
                if (index==0) {
                options.xAxis.labels.step = parseInt(categories.length/10);
                options.xAxis.categories = categories;
                }  
            });
    } else {
        var categories = [];
        var newUsers = [];
        var obj = data.content;
        for(var i=0;i<obj.length;i++)
        {
            if(chartname=="startuser")
                newUsers.push(parseInt(obj[i].startusers,10));
            if(chartname=="newuser")
                newUsers.push(parseInt(obj[i].newusers,10));
            realhour=obj[i].hour+":00";                        
            categories.push(realhour);
        }
        options.series[0].name =realname;       //当前的数据列名字
        options.series[0].data = newUsers;  
        options.xAxis.labels.step = parseInt(categories.length/10);
        options.xAxis.categories = categories;  
        options.title.text = titlename;
    }
    chart = new Highcharts.Chart(options);          
    //getfirstchartdata(); 
}
function switchTimePhase(time)
{
    dispalyOrHideCurTimeSelect();  //判断是否 显示 任意时间段 筛选
    timephase=time;    
    if(time!="any")
    {
        getfirstchartdata();   //如果选择 any  就不进行查询数据 图表渲染 
    }
    var posttime="<?php if (!isset($delete)) {
        echo "postime";
} else {
    echo "";
}?>";    
    if(posttime=="postime")                         //当进行日期选择时   把当前选择的日期 的参数 传送到 其他父窗口中去
    {        
   ///    window.parent.dealgettime(timephase,"","");  //刷新其他表格的 数据
    }
}
function getfirstchartdata()
{
  //  changeChartTitleName(timephase,chartname);          //调用  changeChartTitleName 方法 

    //调用完毕  开始ajax 请求数据
    
    //判断 选择的日期 查询方式

    var myurl="";

    if(timephase=='any')
    {        
        myurl="<?php echo site_url()?>/report/userstatistics/getuserData/"+timephase+"/"+fromCurTime+"/"+toCurTime;
    }
    else
    {
        myurl = "<?php echo site_url()?>/report/userstatistics/getuserData/"+timephase+"?date="+new Date().getTime();
    }

    renderCharts(myurl);        //调用 绘图函数
}


</script>


<!-- 配置绘图选项  -->

<script type="text/javascript">
var chart;
var options;
var chartdata;    
var titlename="<?php echo lang('t_activeUsersT') ?>" ;

$(document).ready(function() {
    options = {
            chart: {
                renderTo: 'container',
                type: 'spline',
                zoomType :'xy'
            },
            title: {
                text: '在线人数统计'
            },
            subtitle: {
                text: ' '
            },
            xAxis: {
               //  tickWidth: 1,
                labels:{rotation:0,y:10,x:0},
                 //  title:{ text:'在线人数' },
                   formatter:function(){
                         return this.value;},
             labels: {y: 20,x:30 }
            },
            yAxis: {
                   gridLineColor: '#197F07',
                title: {
                    text: '人数'
                },
                labels: {
                    formatter: function() {
                        return Highcharts.numberFormat(this.value, 0);
                    },

           
                },min:0
            },
            tooltip: {
                crosshairs: {
                width: 2,
                color: 'gray',
                dashStyle: 'shortdot'
            },
                shared: true,
                 valueSuffix: '个',
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
                enabled:true,
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
     var chart_canvas = $('#container');   //选择要绘制的div的id对象
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
       
        //根据选择的时间  进行筛选调用  数据 进行显示
      
        jQuery.getJSON(myurl, null, function(data) {   

            chartdata=data;          
            console.log('--chartdata--',chartdata);   

            if(typeof(data.type)!='undefined'&&data.type.name=='compare'){
                console.log('--typeof(data.type)--',typeof(data.type));
                $.each(data.content,function(index,item){
                    var categories = [];
                    var newUsers = [];
                    var obj=item.data;
                    var realhour
                    for(var i=0;i<obj.length;i++)   //进行数据渲染
                    {
                        if(chartname=="startuser")
                            newUsers.push(parseInt(obj[i].startusers,10));        
                        if(chartname=="newuser")
                            newUsers.push(parseInt(obj[i].newusers,10));
                         realhour=obj[i].hour+":00";                        
                        categories.push(realhour);
                    }
                    options.series[index]={};
                    options.series[index].data = newUsers;
                    options.series[index].name = item.name;
                    if(index==0)
                    options.xAxis.labels.step = parseInt(categories.length/10);
                    options.xAxis.categories = categories;  
                    });
                }else{

                        //走这里 
                        
                   console.log('这个判断是啥意思啊');

                    var categories = [];            
                    var newUsers = [];
                    var obj = data.userdatalist;


                    for(var i=0;i<obj.length;i++)
                    {
                      //  if(chartname=="startuser")  //活跃用户

                            newUsers.push(parseInt(obj[i].user,10));  

                      //  if(chartname=="newuser")        //新增用户

                       //     newUsers.push(parseInt(obj[i].newusers,10));

                        
                     //   realhour=obj[i].hour+":00"; 

                            categories.push(obj[i].time);
                    }
                    

                      console.log('--categories-',categories);
                        console.log('--newUsers-',newUsers);


                    options.series[0].data = newUsers;  //数据列渲染

                    options.xAxis.labels.step = parseInt(categories.length/12);  

                    options.xAxis.categories = categories;  
                    options.series[0].name = realname;  //当前的数据列名字
           
                    }  

        options.title.text = '在线人数统计';   //标题
        chart = new Highcharts.Chart(options);     //进行图表配置进行渲染   
        chart_canvas.unblock(); //图表释放
        });  
}
</script>


<!-- 添加到控制板里 -->

<script type="text/javascript">
function addreport()
{    
    if(confirm( "<?php echo  lang('w_isaddreport')?>"))
    {
        var reportname="phaseusetime";
        var reportcontroller="productbasic";
        var data={ 
                 reportname:reportname,
                     controller:reportcontroller,
                     height    :380,
                     type      :1,
                     position  :0
                   };
        jQuery.ajax({
                        type :  "post",
                        url  :  "<?php echo site_url()?>/report/dashboard/addshowreport",    
                        data :  data,            
                        success : function(msg) {
                            if(msg=="")
                            {
                                alert("<?php echo lang('w_addreportrepeat') ?>");
                            }
                            else if(msg>=8)
                            {
                                alert("<?php echo  lang('w_overmaxnum');?>");
                            }
                            else
                            {
                                 alert("<?php echo lang('w_addreportsuccess') ?>");    
                            }
                                     
                            },
                            error : function(XmlHttpRequest, textStatus, errorThrown) {
                                alert(<?php echo lang('t_error'); ?>);
                            }
                    });
        
    }
}

function deletereport()
{     
    if(confirm( "<?php echo  lang('v_deletreport')?>"))
    {
     //   window.parent.deletereport("phaseusetime");      //删除后 调回父窗口           
    }
    return false;
    
}
</script>