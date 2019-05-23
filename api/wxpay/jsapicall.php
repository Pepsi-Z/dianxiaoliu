<?php
include_once("WxPayHelper.php");

$commonUtil = new CommonUtil();
$wxPayHelper = new WxPayHelper();
$body = $_GET['body'];//订单详情
$out_trade_no=$_GET['out_trade_no'];//订单号
$total_fee=intval($_GET['total_fee']*100);//价格3880.00

$wxPayHelper->setParameter("bank_type", "WX");
$wxPayHelper->setParameter("body", $body);
$wxPayHelper->setParameter("partner", $_GET['partner']);
$wxPayHelper->setParameter("out_trade_no", $out_trade_no);
$wxPayHelper->setParameter("total_fee", $total_fee);
$wxPayHelper->setParameter("fee_type", "1");
$wxPayHelper->setParameter("notify_url", $_GET['notify_url']);
$wxPayHelper->setParameter("spbill_create_ip", $_GET['ip']);
$wxPayHelper->setParameter("input_charset", "UTF-8");

//var_dump($wxPayHelper);


?>
<html>
<script language="javascript">

document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
WeixinJSBridge.invoke('getBrandWCPayRequest',<?php echo $wxPayHelper->create_biz_package(); ?>,function(res){
	WeixinJSBridge.log(res.err_msg);
	//alert(res.err_code+res.err_desc+res.err_msg);
	if(res.err_msg == "get_brand_wcpay_request:ok" ) {
			alert('成功');
			history.go(-1);
	}else{
		history.go(-1);
	}
	});
WeixinJSBridge.call('hideToolbar');
});

</script>
<head><meta name="viewport" content="width=device-width;minimum-scale=1.0; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<meta charset="utf-8" />
<title>订单支付</title>
<style>
	button{
		border: 1px;color: block;
	}
</style>
</head>
<body>
</body>
</html>
