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
            //改变订单状态
            $time = time();
            $sql = "select * from weixin_item_order  where orderId= '" . $order_num . "'";
            $res = mysql_query($sql, $conn);
            $order = mysql_fetch_array($res);
            $uid = $order['userId'];
            $sqlr = "select * from weixin_user where id = ".$uid;
            $res = mysql_query($sqlr, $conn);
            $user = mysql_fetch_array($res);
            $total = $order['order_sumPrice'];
            /*//给用户积分
            $array['uid'] = $uid;
            $array['uname'] = $order['userName'];
            $array['action']='消费';
            $sqlc = "select * from weixin_setting where name= 'reward'";
            $res =  mysql_query($sqlc, $conn);
            $consumes =  mysql_fetch_array($res);
            $cc = $consumes['data'];
            //$consume = $cc['consume'];
            $array['score'] =  intval($cc * $total);
            //更改用户积分
            $uscore = $user['score'];
            $um['score'] =  $uscore;*/
            //$array['total']=$um['score'];
            //$sqluu = "update weixin_user set score = ".$um['score']." where id= '" . $uid . "'";
            //mysql_query($sqluu, $conn);
            //$sqli = "insert into weixin_score_log values(null,".$uid.",".$array['uname'].",".$array['action'].",".$time.",".$array['total'].")";
            //mysql_query($sqli, $conn);
            $sqls = "select * from weixin_order_detail where orderId= '" . $order_num . "'";
            $res = mysql_query($sqls, $conn);
//            $orders = mysql_fetch_array($res);
            if ($res !== false)
            {
                $orders = array();
                while ($row = mysql_fetch_assoc($res))
                {
                    $orders[] = $row;
                }
            }
            foreach ($orders as $k=>$v){
                //更改商品库存
                $sqlss = "select goods_stock,buy_num from weixin_item where id = ".$v['itemId'];
                $res = mysql_query($sqlss, $conn);
                $ku = mysql_fetch_array($res);
                //$ku['goods_stock'] = $ku['goods_stock'] - $v['quantity'];
                $ku['buy_num'] = $ku['buy_num'] + $v['quantity'];
                $sqluuu = "update weixin_item set goods_stock = ".$ku['goods_stock'].",buy_num = ".$ku['buy_num']." where id= '" . $v['itemId'] . "'";
                mysql_query($sqluuu, $conn);
                //$sqluuu = "update weixin_user set address = ".$sqluuu.",buy_num = ".$ku['buy_num']." where id= '" . $v['itemId'] . "'";
                //mysql_query($sqluuu, $conn);
            }
            $sqlu = "update weixin_item_order set status = 2,support_time = '" . $time . "' where orderId= '" . $order_num . "'";
            mysql_query($sqlu, $conn);


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
