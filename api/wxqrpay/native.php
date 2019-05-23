<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="format-detection" content="telephone=no" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>微信扫码支付</title>
</head>
<body>
<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);

require_once "lib/WxPay.Api.php";
require_once "unit/WxPay.NativePay.php";
require_once 'unit/log.php';

$body = $_GET['body'];
$trade_no = $_GET['trade_no'];
$Total_fee = $_GET['Total_fee'];
$xfurl= $_GET['xfurl'];
$id = $_GET['id'];
if($Total_fee <= 0){
	echo '支付金额不正确！'.$Total_fee.'元。';
	echo "<a href='".$xfurl."index.php?g=sf&m=Received&a=detail&id=".$id."'>返回</a>";
	exit;	
}
//模式一
$notify = new NativePay();

//模式二
$input = new WxPayUnifiedOrder();
$input->SetBody($body);
$input->SetAttach($body);
$input->SetOut_trade_no(time()."_".$trade_no);
$input->SetTotal_fee($Total_fee*100);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag($body);
$input->SetNotify_url($xfurl."api/wxqrpay/notify.php");
$input->SetTrade_type("NATIVE");
$input->SetProduct_id($trade_no);
$result = $notify->GetPayUrl($input);
$url2 = $result["code_url"];
?>


	
	<div style="margin-left: 10px;color:#556B2F;font-size:1em;font-weight: bolder;"><?php echo $body?></div><br/>
	<div style="margin-left: 10px;color:#556B2F;font-size:1em;font-weight: bolder;">支付金额：<?php echo $Total_fee?>元</div><br/>
	<div style="margin-left: 10px;color:#556B2F;font-size:1em;font-weight: bolder;">请用微信扫一扫扫描二维码进行支付</div><br/>
	<center>
	<img alt="请用微信扫一扫扫描二维码进行支付" src="<?php echo $xfurl?>api/wxqrpay/qrcode.php?data=<?php echo urlencode($url2);?>" style="width:15em;height:15em;"/>
	</center>
</body>
</html>