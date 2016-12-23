<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<link href='/Public/fullcalendar/fullcalendar.css' rel='stylesheet' />
<link href='/Public/fullcalendar/fancybox.css' rel='stylesheet' />
<link href='/Public/fullcalendar/fullcalendar.print.css' rel='stylesheet' media='print' />
<script src='/Public/lib/jquery.min.js'></script>
<script src='/Public/lib/jquery-ui.custom.min.js'></script>
<script src='/Public/fullcalendar/fullcalendar.min.js'></script>
<script src='/Public/fullcalendar/jquery.fancybox-1.3.1.pack.js'></script>
<script src='/Public/DatePicker/WdatePicker.js'></script>
<style type="text/css">
	#calendar{width:960px; margin:20px auto 10px auto}
	body {margin-top: 40px;font-size: 14px;font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;color:#333333;}
	#loading {position: absolute;top: 5px;right: 5px;}
	.fc-widget-header{background:#f7f7f7;}
	.fancy{width:450px; height:auto}
	.fancy h3{height:30px; line-height:30px; border-bottom:1px solid #d3d3d3; font-size:14px;}
	.fancy form{padding:10px;}
	.fancy p{height:28px; line-height:28px; padding:4px; color:#999;}
	.input{height:20px; line-height:20px; padding:2px; border:1px solid #d3d3d3; width:100px;}
	.btn{padding:5px 12px; cursor:pointer;}
	.btn_ok{background: #360;border: 1px solid #390;color:#fff}
	.btn_cancel{background:#f0f0f0;border: 1px solid #d3d3d3; color:#666 }
	.btn_del{background:#f90;border: 1px solid #f80; color:#fff }
	.sub_btn{height:32px; line-height:32px; padding-top:6px; border-top:1px solid #f0f0f0; text-align:right; position:relative}
	.sub_btn .del{position:absolute; left:2px}
</style>
<script>
	$(document).ready(function() {
		$('#calendar').fullCalendar({
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			buttonText: {
		        today: '今天',
		        month: '月视图',
		        week: '周视图',
		        day: '日视图'
		    },
		    allDayText: '全天',
		    columnFormat: {
		        month: 'dddd',
		        week: 'dddd M-d',
		        day: 'dddd M-d'
		    },
		    axisFormat:'h(:mm)tt',
		    titleFormat: {
		        month: 'yyyy年 MMMM月',
		        week: "[yyyy年] MMMM月d日 { '&#8212;' [yyyy年] MMMM月d日}",
		        day: 'yyyy年 MMMM月d日 dddd'
		    },
		    monthNames: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'],
		    dayNames: ['星期天', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
			editable: true,
			events: '<?php echo U('Index/json');?>',
			//拖拽事件
			eventDrop: function(event,dayDelta,minuteDelta,allDay,revertFunc) {
				var start =$.fullCalendar.formatDate(event.start,'yyyy-MM-dd HH:mm:ss');
				if(event.end!='null'){
				var end =$.fullCalendar.formatDate(event.end,'yyyy-MM-dd HH:mm:ss');
				}
				$.ajax({
					url:'<?php echo U('Index/drop');?>?id='+event.id+'&start='+start+'&end='+end,
					type:'get',
					dataType:"json",
					success:function(data){
						if(data==1){
							return
						}else{
							alert(data);
							revertFunc();
						}
					}
				})
			},
			//拉伸事件
			eventResize: function(event,dayDelta,minuteDelta,revertFunc) { 
				var start =$.fullCalendar.formatDate(event.start,'yyyy-MM-dd HH:mm:ss');
				if(event.end!='null'){
				var end =$.fullCalendar.formatDate(event.end,'yyyy-MM-dd HH:mm:ss');
				}
				$.ajax({
					url:'<?php echo U('Index/drop');?>?id='+event.id+'&start='+start+'&end='+end,
					type:'get',
					dataType:'json',
					success:function(data){
						if(data==1){
							return
						}else{
							alert(data);
							revertFunc();
						}
					}
				})
			},
			//loading
			loading: function(bool) {
				if (bool) $('#loading').show();
				else $('#loading').hide();
			},
			//点击日历空白处   Click blank 
 			dayClick: function(date, allDay, jsEvent, view) {
 				var selDate =$.fullCalendar.formatDate(date,'yyyy-MM-dd');
					$.fancybox({
						'type':'ajax',
						'href':'<?php echo U('Index/window');?>?action=add&date='+selDate
					});
			},
			//点击日历中事件   Click event
			eventClick: function(calEvent, jsEvent, view) {
				$.fancybox({
					'type':'ajax',
					'href':'<?php echo U('Index/window');?>?action=edit&id='+calEvent.id
				});
	    	}
		});
		
	});
</script>
</head>
<body>
<div id='loading' style='display:none'>loading...</div>
<div id='calendar'></div>
</body>
</html>