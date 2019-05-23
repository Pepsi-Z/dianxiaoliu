<?php
 
/**
 * 服务器异步通知
 * @author http://www.zfweixin.com/
 * @copyright 2014
 */

include_once('DB.php');
include 'lib.php';

$sql="select * from ".$arr['DB_PREFIX']."pay WHERE ( `pay_type` = 'wxpay' )";
$result=mysql_query($sql,$conn);
$row2=mysql_fetch_array($result);
$row=unserialize($row2['config']);

$appid=strval($row['appid']);
$secret=strval($row['appsecret']);
$partnerkey=strval($row['partnerkey']);
$signkey=strval($row['signkey']);
$partner=strval($row['partnerid']);

$config = array(
    'appId' => $appid, // 公众号身份标识
    'appSecret' => $secret, // 权限获取所需密钥 Key
    'paySignKey' => $signkey, // 加密密钥 Key，也即appKey
    'partnerId' => $partner, // 财付通商户身份标识
    'partnerKey' => $partnerkey // 财付通商户权限密钥 Key
);
$wechat = new Wechat;
$array2 = $_GET;
/*$strget="get:";
foreach ($array2 as $k2 => $v2) {
	$strget.= $k2."=".$v2."&";
}
$wechat->log_result($strget);
*/

$verifyNotify =$array2['notify_id'];//验证通知
if ($verifyNotify) {
	 $array2['xml'] = $wechat->getXmlArray();//获取xml数据
    //$array += $wechat->getXmlArray(); // 再获取xml数据
    //$array2 += $wechat->getXmlArray(); // 再获取xml数据
	$wxorder=serialize($array2);

    // 结果已经获取到了 下面可以更新数据库支付状态等操作了 也可以使用订单查询接口来查询是否支付成功
    //$wechat->log_result($array);
    $out_trade_no =$array2['out_trade_no']; //商户订单号
   // echo $out_trade_no；exit；
   // $notify_id= $array['notify_id'];//通知id
    $trade_state=$array2['trade_state'];//交易状态
    if($trade_state == 0){
			$sql="update ".$arr['DB_PREFIX']."item_order set status=2,support_time=".time().",paytype='wxpay' where orderId='".$out_trade_no."'";
			mysql_query($sql,$conn);
			$sql2="update ".$arr['DB_PREFIX']."item_order set payorder='".$wxorder."' where orderId='".$out_trade_no."'";
			mysql_query($sql2,$conn);
			//减少商品
			$order_details=mysql_query("select * from ".$arr['DB_PREFIX']."order_detail where orderId='".$out_trade_no."'",$conn);
			
			foreach ($order_details as $val)
			{
				mysql_query("update ".$arr['DB_PREFIX']."item set goods_stock =goods_stock-'".$val['quantity']."' where id='".$val['itemId']."'",$conn);
			}
	}
	echo "success"; 
		eixt();	
}else{
	echo "fail";
	eixt();
}
?>