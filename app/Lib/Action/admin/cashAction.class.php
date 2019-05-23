<?php
class cashAction extends backendAction
{

    public function index()
    {
        if($_GET['state'] == '0'){
            $where['state'] = $_GET['state'];
            $list = M('member_money')->where($where)->select();
        }else{
            $list = M('member_money')->select();
        }
        $p = new Page(count($list), 15);
        $list = array_slice($list, $p->firstRow, $p->listRows);
        $show = $p->show();
        $this->assign('page', $show);
        $this->assign('list', $list);
        $this->assign('list_table', true);
        $this->display();
    }

    /**
     * 退款
     */
    public function delete()
    {
        $mod = D('member_money');
        $pk = $mod->getPk();
        $ids = trim($this->_request($pk), ',');
        $uids = trim($_GET['uid'], ',');
        $data['state'] = 1;  //已经提现
        $where['id'] = array('in',$ids);
        $uwhere['id'] = array('in',$uids);
       if ($ids) {
            if (false !== $mod->where($where)->save($data)) {
                require_once 'app/Common/MessageUtil.php';
                $info=M('user')->where($uwhere)->select();
                foreach($info as $k=>$v){
                    $openids=$info[$k]['openid'];
                    $str = "";
                    $str.= "尊敬的凡事帮用户【".$info[$k]['username']."】您申请的提现已审核,稍后客服会与您联系,请等待...";
                    MessageUtil::sendTextInfo($openids,$str);
                }
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'));
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }
        } else {
            IS_AJAX && $this->ajaxReturn(0, L('illegal_parameters'));
            $this->error(L('illegal_parameters'));
        }
    }

    public function order_warning() {
        if(true){
            $count = M('member_money')->where("state = 0")->count();
            if($count > 0){
                $arr['state'] = 1;
            }
            $arr['count'] = $count;

            echo json_encode($arr);
        }

    }

}