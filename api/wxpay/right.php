<?php
/**
 * 订单反馈
 * @author www.5cando.com
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

$data = $wechat->getXmlArray(); // 具体参数可以查看文档
switch ($data['msgtype']) {
    case 'request' : // 新增投诉单
        print_r($data);
    break;
    case 'confirm' : // 买家确认反馈已经得到解决
        print_r($data);
    break;
    case 'reject' : // 买家拒绝问题已经完善解决，需要再次协商
        print_r($data);
    break;
}

?>