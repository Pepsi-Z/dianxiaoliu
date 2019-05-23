<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "lib/WxPay.Api.php";
require_once 'lib/WxPay.Notify.php';
require_once 'unit/log.php';

//初始化日志
$logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}
    //重写回调处理函数
    public function NotifyProcess($data, &$msg)
    {
        Log::DEBUG("call back:" . json_encode($data));
        $notfiyOutput = array();

        $arrs = explode("_", $data['out_trade_no']);

        $arr = require("../../data/config/db.php");
        $conn =mysql_connect($arr['DB_HOST'],$arr['DB_USER'],$arr['DB_PWD']) or die("连接数据库失败!");
        mysql_select_db($arr['DB_NAME'],$conn);
        mysql_query("set names utf8");
        $order_num = $arrs[1];
        if($order_num) {
          /*  //改变订单状态
            $sql = " select * from  weixin_yue_log where id = ".$order_num;
            $res1 = mysql_query($sql, $conn);
            $yue =mysql_fetch_array($res1);
            $uid = $yue['uid'];
            $sqlr = "select * from weixin_user where id = ".$uid;
            $res2 = mysql_query($sqlr, $conn);
            $user  = mysql_fetch_array($res2);
            $ye['total'] = $user['money'] +$yue['money'];
            $sqlu = "update weixin_yue_log set status = 1,total = '".$ye['total']."' where id = ".$order_num ;
            mysql_query($sqlu, $conn);
            //$sql = "update weixin_user set money = '".$ye['total']."' where id = '".$uid."'";
            mysql_query($sql, $conn);*/
        }
        if(!array_key_exists("transaction_id", $data)){
            $msg = "输入参数不正确";
            return false;
        }
        //查询订单，判断订单真实性
        if(!$this->Queryorder($data["transaction_id"])){
            $msg = "订单查询失败";
            return false;
        }
        return true;
    }

	//重写回调处理函数
	public function NotifyProcess1($data, &$msg)
	{
		Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();

		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
}

Log::DEBUG("begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
