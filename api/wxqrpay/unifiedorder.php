<?php 
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "lib/WxPay.Api.php";
require_once "unit/WxPay.JsApiPay.php";
require_once 'unit/log.php';

//初始化日志
//$logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'.log');
//$log = Log::Init($logHandler, 15);

$body = $_GET['body'];
$Total_fee = $_GET['Total_fee'];
$xfurl= $_GET['xfurl'];
$type = $_GET['type'];
if($type == 1){
    $trade_no = $_GET['trade_no'];
    $tel = $_GET['tel'];
    $back_url = $xfurl."index.php?m=Order&a=order_tuan&status=1";
	$success_url = $xfurl."index.php?m=Order&a=call_back&tel=".$tel;
}else if($type == 2){
    //续费
	$end_time = $_GET['end_time'];
    $money    = $_GET['money'];
    $trade_no = $_GET['trade_no'];
    $back_url = $xfurl."index.php?m=User&a=xu_price";
	$success_url = $xfurl."index.php?m=User&a=xu_call_back&end_time=".$end_time."&money=".$money;
}else if($type == 3){
    $trade_no = $_GET['trade_no'];
    $back_url = $xfurl."index.php?m=User&a=index";
    $success_url = $xfurl."index.php?m=Order&a=call_back_collect";
}else if($type == 5) {
    //会员办卡
    $id = $_GET['id'];
    $trade_no = $_GET['trade_no'];
    $back_url = $xfurl . "index.php?m=Member&a=add_member";
    $success_url = $xfurl . "index.php?m=Member&a=addmember_call_back&id=".$id;

}else if($type == 6) {
    //小五订单
    $trade_no = $_GET['trade_no'];
    $back_url = $xfurl."index.php?m=Order&a=index&status=1";
    $success_url = $xfurl."index.php?m=Order&a=xiaowu_call_back";
}
if($Total_fee <= 0){
	echo '支付金额不正确！'.$Total_fee.'元。';
	echo "<a href='".$back_url."'>返回</a>";
	exit;	
}
//获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();

//统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($body);
$input->SetAttach($body);
$input->SetOut_trade_no($trade_no);//
$input->SetTotal_fee($Total_fee*100);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag($body);
if($type == 2){
    $input->SetNotify_url($xfurl."api/wxqrpay/notify3.php");
}else if($type == 3) {
    $input->SetNotify_url($xfurl ."api/wxqrpay/notify4.php");
}else if($type == 5){
    $input->SetNotify_url($xfurl."api/wxqrpay/notify5.php");
}else if($type == 6){
    $input->SetNotify_url($xfurl."api/wxqrpay/notify6.php");
}else{
    $input->SetNotify_url($xfurl."api/wxqrpay/notify2.php");
}



$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
//echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
//printf_info($order);
$jsApiParameters = $tools->GetJsApiParameters($order);
//echo $jsApiParameters;
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>微信支付</title>
    <script src="../../static/js/jquery.js"></script>
    <script type="text/javascript">
		//调用微信JS api 支付
		function jsApiCall()
		{
			WeixinJSBridge.invoke(
				'getBrandWCPayRequest',
				<?php echo $jsApiParameters; ?>,
				function(res){
					if(res.err_msg == "get_brand_wcpay_request:ok" ) {
						$("#trade-form").submit();
					}else{
						alert("支付失败");
						window.location.href="<?php echo $back_url?>";
					}
				}
			);
		}

		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
		}

		function obj2str(o){
			   var r = [];
			   if(typeof o == "string" || o == null) {
			     return o;
			   }
			   if(typeof o == "object"){
			     if(!o.sort){
			       r[0]="{"
			       for(var i in o){
			         r[r.length]=i;
			         r[r.length]=":";
			         r[r.length]=obj2str(o[i]);
			         r[r.length]=",";
			       }
			       r[r.length-1]="}"
			     }else{
			       r[0]="["
			       for(var i =0;i<o.length;i++){
			         r[r.length]=obj2str(o[i]);
			         r[r.length]=",";
			       }
			       r[r.length-1]="]"
			     }
			     return r.join("");
			   }
			   return o.toString();
			}

		callpay();
	</script>
</head>
<body>
    <form action="<?php echo $success_url?>" method="post" id="trade-form">
		<input type="hidden" name="trade_no" value="<?php echo $trade_no?>" >
		<input type="hidden" name="Total_fee" value="<?php echo $Total_fee?>" >
		<input type="hidden" name="yuerid" value="<?php echo $yuerid?>" >
    </form>
</body>
</html>