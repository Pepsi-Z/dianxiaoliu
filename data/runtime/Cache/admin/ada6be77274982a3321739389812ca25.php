<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link href="__STATIC__/css/admin/style.css" rel="stylesheet"/>
    <link href="__STATIC__/css/admin/WdatePicker.css" rel="stylesheet"/>
    <title><?php echo L('website_manage');?></title>
    <script>
        var URL = '__URL__';
        var SELF = '__SELF__';
        var ROOT_PATH = '__ROOT__';
        var APP	 =	 '__APP__';
        //语言项目
        var lang = new Object();
        <?php $_result=L('js_lang');if(is_array($_result)): $i = 0; $__LIST__ = $_result;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?>lang.<?php echo ($key); ?> = "<?php echo ($val); ?>";<?php endforeach; endif; else: echo "" ;endif; ?>
    </script>
    <script charset="utf-8" src="__STATIC__/js/jquery-1.9.1.min.js" type="text/javascript"></script>
    <script charset="utf-8" src="__STATIC__/js/layer/layer.js"></script>
    <script charset="utf-8" src="__STATIC__/js/My97DatePicker/WDatePicker.js" type="text/javascript"></script>
</head>

<body>
<div id="J_ajax_loading" class="ajax_loading"><?php echo L('ajax_loading');?></div>
<?php if(($sub_menu != '') OR ($big_menu != '')): ?><div class="subnav">
        <div class="content_menu ib_a blue line_x">
            <?php if(!empty($big_menu)): ?><a class="add fb J_showdialog" href="javascript:void(0);" data-uri="<?php echo ($big_menu["iframe"]); ?>" data-title="<?php echo ($big_menu["title"]); ?>" data-id="<?php echo ($big_menu["id"]); ?>" data-width="<?php echo ($big_menu["width"]); ?>" data-height="<?php echo ($big_menu["height"]); ?>"><em><?php echo ($big_menu["title"]); ?></em></a>　<?php endif; ?>
            <?php if(!empty($sub_menu)): if(is_array($sub_menu)): $key = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($key % 2 );++$key; if($key != 1): ?><span>|</span><?php endif; ?>
                    <a href="<?php echo U($val['module_name'].'/'.$val['action_name'],array('menuid'=>$menuid)); echo ($val["data"]); ?>" class="<?php echo ($val["class"]); ?>"><em><?php echo ($val['name']); ?></em></a><?php endforeach; endif; else: echo "" ;endif; endif; ?>
        </div>
    </div><?php endif; ?>
<style>
    .bg{background: #A5BCF4}
</style>
<!--商品列表-->
<div class="pad_10" >
    <form name="searchform" method="get" >
    <table width="100%" cellspacing="0" class="search_form">
        <tbody>
            <tr>
                <td>
                <div class="explain_col">
                    <input type="hidden" name="g" value="admin" />
                    <input type="hidden" name="m" value="return_integral" />
                    <input type="hidden" name="a" value="index" />
                    <input type="hidden" name="menuid" value="<?php echo ($menuid); ?>" />
                    &nbsp;&nbsp;用户名 :
                    <input type="text" name="username" class="input-text" size="15" value="<?php echo ($search["uname"]); ?>" />

                    &nbsp;&nbsp;商家 :
                    <input type="text" name="sname" class="input-text" size="15" value="<?php echo ($search["sname"]); ?>" />

                    下单时间 :
                    <input type="text" name="time_start" id="J_time_start" class="date" size="12" value="<?php echo ($search["time_start"]); ?>">
                    -
                    <input type="text" name="time_end" id="J_time_end" class="date" size="12" value="<?php echo ($search["time_end"]); ?>">
                    <input type="submit" name="search" class="btn" value="搜索" />
                    <div class="bk8"></div>                
                </div>
                </td>
            </tr>
        </tbody>
    </table>
    </form>
    <div class="J_tablelist table_list" data-acturi="<?php echo U('return_integral/ajax_edit');?>">
    <table width="100%" cellspacing="0">
        <thead>
            <tr>
                <th width=25><input type="checkbox" id="checkall_t" class="J_checkall"></th>
                <th width="40"><span data-tdtype="order_by" data-field="id">ID</span></th>
                <th align="100" align="left"><span data-tdtype="order_by" data-field="username">会员名</span></th>
                <th width="200"><span data-tdtype="order_by" data-field="card_number">会员卡号</span></th>
                <th><span data-tdtype="order_by" data-field="money">消费金额</span></th>
                <th><span data-tdtype="order_by" data-field="action">明细</span></th>
                <th width="60"><span data-tdtype="order_by" data-field="sname">商家</span></th>
                <th width="60"><span data-tdtype="order_by" data-field="score">积分</span></th>
                <th width="100">兑换规则</th>
                <th width="120"><span data-tdtype="order_by" data-field="add_time">下单时间</span></th>
                <th width="60"><span data-tdtype="order_by" data-field="status"><?php echo L('status');?></span></th>
                <th width="80"><?php echo L('operations_manage');?></th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr>
                <td align="center"><input type="checkbox" class="J_checkitem" value="<?php echo ($val["id"]); ?>"></td>
                <td align="center"><?php echo ($val["id"]); ?></td>
                <td align="center"><?php echo ($val["username"]); ?></td>
                <td align="center"><?php echo ($val["card_number"]); ?></td>
                <td align="center"><?php echo ($val["money"]); ?></td>
                <td align="center"><?php echo ($val["action"]); ?></td>
                <td align="center"><?php echo ($val["sname"]); ?></td>
                <td align="center"><?php echo ($val["score"]); ?></td>
                <td align="center"><?php echo ($val["fname"]["name"]); ?></td>
                <td align="center"><?php echo (date('Y-m-d H:i:s',$val["add_time"])); ?></td>
                <td align="center">
                    <?php if($val["status"] == 0): ?><span style="color: green">未返利</span>
                        <?php else: ?>
                        <span style="color: red">已返利</span><?php endif; ?>
                </td>
                <td align="center"><a href="javascript:void(0);" class="J_confirmurl" data-uri="<?php echo u('return_integral/delete', array('id'=>$val['id']));?>" data-acttype="ajax" data-msg="<?php echo sprintf(L('confirm_delete_one'),$val['id']);?>"><?php echo L('delete');?></a></td>
            </tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
    </table>
    </div>
    <div class="btn_wrap_fixed">
        <label class="select_all mr10"><input type="checkbox" name="checkall" class="J_checkall"><?php echo L('select_all');?>/<?php echo L('cancel');?></label>
        <input type="button" class="btn" data-tdtype="batch_action" data-acttype="ajax" data-uri="<?php echo U('return_integral/delete');?>" data-name="id" data-msg="<?php echo L('confirm_delete');?>" value="<?php echo L('delete');?>" />
        <div id="pages"><?php echo ($page); ?></div>
    </div>
</div>

<script src="__STATIC__/js/jquery/jquery.js"></script>
<script src="__STATIC__/js/jquery/plugins/jquery.tools.min.js"></script>
<script src="__STATIC__/js/jquery/plugins/formvalidator.js"></script>
<script src="__STATIC__/js/pinphp.js"></script>
<script src="__STATIC__/js/admin.js"></script>
<script>
//初始化弹窗
(function (d) {
    d['okValue'] = lang.dialog_ok;
    d['cancelValue'] = lang.dialog_cancel;
    d['title'] = lang.dialog_title;
})($.dialog.defaults);
</script>

<?php if(isset($list_table)): ?><script src="__STATIC__/js/jquery/plugins/listTable.js"></script>
<script>
$(function(){
	$('.J_tablelist').listTable();
});
</script><?php endif; ?>
<script type="text/javascript" src="../Style/js/tc.all.js"></script>
<script type="text/javascript"> 
	<?php if(C('pin_s_waring') == 1): ?>window.setInterval(function(){

       showalert();

	}, 10000);<?php endif; ?>

	function showalert()
	{ 
		var url  = "<?php echo U('ticket_order/order_warning');?>";
    	$.post(url,function(data){
    		if(data.state==1) {
    			popWin("detail");
    			//play_click();
				$('#count_html').html('你有'+data.count+'个订票订单，是否马上处理？');
    		}

    	},'json');
	} 
	function push_qd(){
		window.location.href = "<?php echo U('ticket_order/index');?>";
	}
	function push_qx(){
		$('#push_count').css('display','none');
	}
	
	function play_click(){
		var url = "__STATIC__/newOrder.mp3";
	    var div = document.getElementById('div1');
	    div.innerHTML = '<embed src="'+url+'" loop="0" autostart="true" type="audio/mpeg" hidden="true"></embed>';
	    var emb = document.getElementsByTagName('EMBED')[0];
	    if (emb) {
	        setTimeout(function(){div.innerHTML='';},1000);
	    }
	}
</script>
<style type="text/css">
body {
	margin: 0;
	padding: 0;
	font-size: 12px;
}
dt {
	padding: 10px;
}
p {
	height: 100px;
	line-height: 100px;
	border: 1px solid #eee;
	margin: 10px;
}
i {
	font-style: normal;
}
#detail {
	position: absolute;
	width: 400px;
	height: 200px;
	border: 1px solid #ccc;
	background: #fff;
	display: none;
}
#detail .tit {

	background: #ddd;
	display: block;
	height: 33px;
	cursor: move;
}
#detail .tit i {
	float: right;
	line-height: 33px;
	padding: 0 8px;
	cursor: default;
}
#detail .button_down {
	position: relative;
	bottom:-100px; 
	margin-left: 30%;
}
#detail span {
	position: relative;
	bottom:-50px; 
	font-size:14px;
	margin-left: 20%;
}
</style>
<div id="detail">
  <div class="tit"><i class="close">关闭</i></div>
  		
  		<span id="count_html"></span>
  	<div class="button_down">
      <input type="button" class="subbtu btn" onclick="push_qd();" value="确定" /> 　　
      <input type="button" class="subbtu close btn"  value="取消" />
      </div>
</div>


<div id="div1"></div>
<div id="div2"></div>

<link rel="stylesheet" href="__STATIC__/js/calendar/calendar-blue.css"/>
<script src="__STATIC__/js/calendar/calendar.js"></script>
<script>
Calendar.setup({
    inputField : "J_time_start",
    ifFormat   : "%Y-%m-%d",
    showsTime  : false,
    timeFormat : "24"
});
Calendar.setup({
    inputField : "J_time_end",
    ifFormat   : "%Y-%m-%d",
    showsTime  : false,
    timeFormat : "24"
});
</script>
</body>
</html>