<?php
class ZAction extends baseAction
{

	public function index()

	{

        $arr = require("data/config/db.php");
        $conn =mysql_connect($arr['DB_HOST'],$arr['DB_USER'],$arr['DB_PWD']) or die("连接数据库失败!");
        mysql_select_db($arr['DB_NAME'],$conn);
        mysql_query("set names utf8");
        $order_num = "20150918101831976";
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
        //给用户积分
        $array['uid'] = $uid;
        $array['uname'] = $order['userName'];
        $array['action']='consume';
        $sqlc = "select * from weixin_setting where name= 'score_rule'";
        $res =  mysql_query($sqlc, $conn);
        $consumes =  mysql_fetch_array($res);
        $cc = unserialize($consumes['data']);
        $consume = $cc['consume'];
        $array['score'] =  intval($consume * $total);
        //更改用户积分
        $uscore = $user['score'];
        $um['score'] = $array['score'] + $uscore;
        $array['total']=$um['score'];
        $sqluu = "update weixin_user set score = ".$um['score']." where id= '" . $uid . "'";
        mysql_query($sqluu, $conn);
        $sqli = "insert into weixin_score_log values(null,".$uid.",".$array['uname'].",".$array['action'].",".$array['score'].",".$time.",".$array['total'].")";
        mysql_query($sqli, $conn);
        $sqls = "select * from weixin_order_detail where orderId= '" . $order_num . "'";
        $res = mysql_query($sqls, $conn);
        if ($res !== false)
        {
            $orders = array();
            while ($row = mysql_fetch_assoc($res))
            {
                $orders[] = $row;
            }
        }
//        $orders = mysql_fetch_array($res);
        foreach ($orders as $k=>$v){
            //更改商品库存
            $sqlss = "select goods_stock,buy_num from weixin_item where id = ".$v['itemId'];
            $res = mysql_query($sqlss, $conn);
            $ku = mysql_fetch_array($res);
            $ku['goods_stock'] = $ku['goods_stock'] - $v['quantity'];
            $ku['buy_num'] = $ku['buy_num'] + $v['quantity'];
            $sqluuu = "update weixin_item set goods_stock = ".$ku['goods_stock'].",buy_num = ".$ku['buy_num']." where id= '" . $v['itemId'] . "'";
            mysql_query($sqluuu, $conn);
            $sqluuu = "update weixin_user set address = ".$sqluuu.",buy_num = ".$ku['buy_num']." where id= '" . $v['itemId'] . "'";
            mysql_query($sqluuu, $conn);
        }
        $sqlu = "update weixin_item_order set status = 2,support_time = '" . $time . "' where orderId= '" . $order_num . "'";
        mysql_query($sqlu, $conn);
	}

    public function ii(){
        $arr = require("data/config/db.php");
        $conn =mysql_connect($arr['DB_HOST'],$arr['DB_USER'],$arr['DB_PWD']) or die("连接数据库失败!");
        mysql_select_db($arr['DB_NAME'],$conn);
        mysql_query("set names utf8");
        $order_num = "139";

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
        $sql = "update weixin_user set money = '".$ye['total']."' where id = '".$uid."'";
        mysql_query($sql, $conn);
    }


}
?>