<style>
/*this is the style of helpview*/
#main {
	margin: 20px 40px 450px;
	padding: 0;
}

#main h3 {
	margin: 8px 0;
}

#main ul {
	padding: 6px;
}

#main li {
	margin-bottom: 9px;
}

.content {
	background-color: #FFFFFF;
	border: 1px solid #B3B4BD;
	font-family: font-family : Lucida Grande, Verdana, Geneva, Sans-serif;
	margin-top: 10px;
	padding: 6px;
	font-size:13px;
}

.rmborder {
	border-left: 0px;
	border-right: 0px;
}
.left {
	width: 150px;
	text-align: left;
	padding-left: 5px;
}

.right {
	text-align: left;
	line-height: 24px;
	margin-left:10px;	
}
</style>
<div id="main">
	<!-- <div class="title">
		<h3><?php echo lang('t_helpItem')?></h3>
	</div> -->
	<!-- <div class="content rmborder" style="background: none;">
		<ul>
			<li><a href="#OverviewRecently"><?php echo lang('v_rpt_pb_overviewRecently');?></a></li>
			<li><a href="#GeneralSituation"><?php echo lang('v_rpt_pb_generalSituation');?></a></li>
			<li><a href="#PageViewDetails"><?php echo lang('v_rpt_pv_details');?></a></li>
			<li><a href="#Retention"><?php echo lang('v_rpt_ur_retention');?></a></li>
		</ul>
	</div> -->





<h3><?php echo '新增数据'; ?></h3>
<div class="content">
<h3>
1、新增统计的数据是基于角色新增来计算的,对于未创角的设备是不统计的，设备和账号数据都是角色对应的账号，角色对应的设备，唯一绑定<br/>
2、(基于创角)按区分服、统计每日登陆设备、新增设备、新增账号、新增角色。<br/>
3、激活设备、新建账号、创角角色 时间不在同一天、则会按角色时间统计,只累加当日各新增数。


</h3>
</div>


<h3><?php echo '留存统计'; ?></h3>
<div class="content">
<h3>
1、设备留存率: 根据首次使用日、按照设备激活时间统计当日设备总数<br/>
2、用户留存率: 根据首次使用日、按照创建账号统计当日账号总数<br/>
3、角色留存率: 根据首次使用日、按照创建角色统计当日角色总数<br/>
</h3>
</div>



<h3><?php echo '充值数据'; ?></h3>
<div class="content">
<h3>
1、查询订单：按日期查询订单基本信息<br/>
2、充值数据统计：按日期分类统计每日充值数据统计<br/>
</h3>
</div>


<h3><?php echo '用户统计'; ?></h3>
<div class="content">
<h3>
1、在线数据统计：按日期查询每日登陆信息与用户在线时长<br/>
2、在线用户分布：每半小时统计一次当前在线人数<br/>
</h3>
</div>


<h3><?php echo '等级分布'; ?></h3>
<div class="content">
<h3>
1、等级分布统计：统计当前游戏玩家用户等级分布信息<br/>
</h3>
</div>