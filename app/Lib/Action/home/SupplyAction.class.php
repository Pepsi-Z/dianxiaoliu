<?php
class SupplyAction extends frontendAction {
    public function _initialize() {
        parent::_initialize();
    }
	//供求首页
	public function index(){
        $this->get_user_info(0);
        $where['status'] = 1;
        $cid = I('get.cid');
        if($cid){
            $where['cid'] = $cid;
        }
        $list = M('supply')->where($where)->select();
        foreach($list as $k=>$v){
            if($v['type'] == 1){
                $data['g'][] = $v;
            }else{
                $data['q'][] = $v;
            }
        }
        $this->assign('data',$data);
        $this->assign('list',$list);
        $cate_list = M('item_cate')->where(array('pid'=>0))->select();
        $this->assign('cate_list',$cate_list);
        $this->display();
    }
    public function ajax_index(){
        $page = I('request.page',1,'intval');
        $type = I('post.type',0,'intval');
        list($pid,$cid) = explode('#',I('post.cid','','trim'));
        $where = array();
        $where['status'] = 1;
        if($type) $where['type'] = $type;
        if($cid)  $where['cid'] = $cid;
        if($pid)  $where['pid'] = $pid;
        $list = M('supply')->where($where)->page($page,10)->order('add_time desc')->select();
        $this->assign('list',$list);
        if(count($list)>=1){
            $data = $this->fetch();
            $this->ajaxReturn(1,'数据',$data);
        }else{
            $this->ajaxReturn(0);
        }
    }
    //供求详情
    public function detail(){
        $id = I('get.id');
        $supply_img = M('supply_img')->where(array('supply_id'=>$id))->select();
        $info = M('supply')->where(array('id'=>$id))->find();
        $this->assign('info',$info);
        $this->assign('supply_img',$supply_img);
        $this->display();
    }
    //发布供求信息
    public function release(){
        $this->get_user_info(1);
        require_once 'app/Common/MessageUtil.php';
        if(IS_POST){
            $mod = M('supply');
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }

            $role = I('post.roles');
            if(!$role){
                $this->ajaxReturn(0,'请选阅读本平台用户协议');
            }
            if(!$data['pid']){
                $this->ajaxReturn(0,'请选择分类');
            }
            $data['add_time'] = time();
            if($data['id']){
                if ($mod->where(array('id'=>$data['id']))->save($data) !== false) {
                    $supply_id = $data['id'];
                    $imgid = I('post.imgid');
                    $img_mod = M('supply_img');
                    if (is_array($imgid) && !empty($imgid)) {
                        $img_mod->where(array('id' => array('in', $imgid)))->setField('supply_id', $supply_id);
                    }
                    $url = $img_mod->where(array('supply_id' => $supply_id))->getField('url');
                    $mod->where(array('id' => $supply_id))->setField('url', $url);
                    $this->ajaxReturn(1, '编辑成功', U('Supply/lists'));
                } else {
                    $this->ajaxReturn(0, '编辑失败');
                }

            }else {
                if ($mod->add($data) !== false) {
                    $supply_id = $mod->getLastInsID();
                    $imgid = I('post.imgid');
                    $img_mod = M('supply_img');
                    if (is_array($imgid) && !empty($imgid)) {
                        $img_mod->where(array('id' => array('in', $imgid)))->setField('supply_id', $supply_id);
                    }
                    $url = $img_mod->where(array('supply_id' => $supply_id))->getField('url');
                    $mod->where(array('id' => $supply_id))->setField('url', $url);
                    $type = ($data['type'] == 1) ? '供应' : '求购';
                    $str = '';
                    $str .= "您有一条新的" . $type . "发布消息";
                    $str .= "请尽快处理!";
                    $admin = M('admin')->table('weixin_admin a ')
                        ->field('w.openid')
                        ->join('weixin_wechatuser w on a.wx_id = w.id')
                        ->where("a.wx_id is not null")->select();

                    foreach ($admin as $k => $v) {
                        if ($v['openid']) {
                            MessageUtil::sendTextInfo($v['openid'], $str);
                        }
                    }
                    $this->ajaxReturn(1, '发布成功', U('Supply/index'));
                } else {
                    $this->ajaxReturn(0, '发布失败');
                }
            }
        }else{
            $openid = $_GET['openid']?$_GET['openid']:cookie('openid');
            $user = $this->user;
            if(!$user['username'] || (!$user['mobile'] && !$user['weixin'])){
                $this->error('请先完善资料',U('Supply/wanshan',array('status'=>1,'openid'=>$openid)));
            }
            $id = I('get.id');
            $info = M('supply')->where(array('id'=>$id))->find();
            $cate = M('item_cate')->where(array('pid'=>$info['pid']))->select();
            $supply_img = M('supply_img')->where(array('supply_id'=>$id))->select();
            $supply_img1 = $supply_img['１']['url'];
            $supply_img2 = $supply_img['2']['url'];
            $this->assign('cate',$cate);
            $this->assign('supply_img',$supply_img);
            $this->assign('supply_img1',$supply_img1);
            $this->assign('info',$info);
            $cate_list = M('item_cate')->where(array('pid'=>0))->select();
            $this->assign('cate_list',$cate_list);
            $this->display();
        }

    }
    //删除发布信息
    public function ajax_delete(){
        $id = I('post.id');
        if(M('supply')->where(array('id'=>$id))->delete() !==false){
            M('supply_img')->where(array('supply_id'=>$id))->delete();
            echo 1;
        }else{
            echo 0;
        }
    }
    //平台协议
    public function roles(){
        $this->display();
    }


    //ajax_get_brand
    public function ajax_get_brand(){
        $pid = I('post.pid');
        $brand = M('item_cate')->where(array('pid'=>$pid))->select();
        if($brand ){
           $data['status'] = 1;
           $data['data'] = $brand;

        }else{
            $data['status'] = 0;
            $data['data'] = $pid;
        }

        echo json_encode($data);
    }

    public function ajax_get_brand_li(){
        $pid = I('post.pid');
        $brand = M('item_cate')->where(array('pid'=>$pid))->select();
        if($brand){
            $data['status'] = 1;
            $str = "<li>";
            foreach($brand as $k=>$v){
                $url = U('Supply/index',array('cid'=>$v['id']));
                if($k%3 == 0 && $k != '0'){
                    $str .= '</li><li><a href="javascript:;" data-acturi="'.$url.'" data-id="'.$pid.'#'.$v['id'].'">'.$v['name'].'</a>';
                }else{
                    $str .= '<a href="javascript:;" data-acturi="'.$url.'" data-id="'.$pid.'#'.$v['id'].'">'.$v['name'].'</a>';
                }
            }
            $str .= "</li>";
            $str .= '<li><a href="javascript:;" data-acturi="'.$url.'" data-id="'.$pid.'">全部</a></li>';
            $data['data'] = $str;
        }else{
            $url = U('Supply/index');
            $str = '<li><a href="javascript:;" data-acturi="'.$url.'" data-id="'.$pid.'">全部</a></li>';
            $data['data'] = $str;
            $data['status'] = 1;
        }

        echo json_encode($data);
    }

    public function lists(){
        $this->get_user_info(1);
        $page = I('post.page',1,'intval');
        $uid = $this->user['id'];
        $supply_list = D('supply')->where(array('status'=>1,'uid'=>$uid))->page($page,10)->select();
        $this->assign('list',$supply_list);
        $this->display();
    }

    public function wanshan(){
        $this->get_user_info(0);
        if(IS_POST){
            $data = $_POST;
            if(!$data['username']){
                $this->error('请填写用户姓名');
            }
            if($data['mobile']){
                if (!is_mobile($data['mobile'])) {
                    $this->error( '手机号码不正确！');
                }
            }
            if(!$data['mobile'] && !$data['weixin']){
                $this->error('请填写联系方式');
            }
            $data['reg_time'] = time();
            $data['uc_uid'] =  M('user')->where("mobile ='".$data['umobile']."'")->getField('id');
            if($data['id']){
                $umobile = M('user')->where("id ='".$data['id']."'")->getField('umobile');
                if($umobile != $data['umobile']){
                    $arr = M('user')->field('id,score,username')->where("mobile ='".$data['umobile']."' and id <> ".intval($data['id']))->find();

                    if ($arr){
                        $arr['score'] = $arr['score'] + C('pin_score_rule.tuijian');
                        //积分变动
                        M('user')->where('id ='.intval($arr['id']))->setField('score',$arr['score']);
                        //积分变动记录weixin_score_log
                        $sc['add_time'] = time();
                        $sc['uid']      = $arr['id'];
                        $sc['uname']    = $arr['username'];
                        $sc['action']   = 'tuijian';
                        $sc['score']    = C('pin_score_rule.tuijian');
                        M('score_log')->add($sc);

                    }
                }
                $u = M('user')->where('id ='.intval($data['id']))->save($data);
                if($u !== false){
                    $this->success("资料完善成功",U('Supply/index',array('openid'=>$data['openid'])));
                }
            }else{
                if((M('user')->add($data)) !== flase){
                    $id = M('user')->getLastInsID();
                    $arr = M('user')->field('id,score,username')->where("mobile ='".$data['umobile']."' and id <> ".intval($id))->find();
                    if ($arr){
                        $arr['score'] = $arr['score'] + C('pin_score_rule.tuijian');
                        //积分变动
                        M('user')->where('id ='.intval($arr['id']))->setField('score',$arr['score']);
                        //积分变动记录weixin_score_log
                        $sc['add_time'] = time();
                        $sc['uid']      = $arr['id'];
                        $sc['uname']    = $arr['username'];
                        $sc['action']   = 'tuijian';
                        $sc['score']    = C('pin_score_rule.tuijian');
                        M('score_log')->add($sc);
                    }
                    $this->success("资料完善成功",U('Supply/index',array('openid'=>$data['openid'])));
                }
            }
        }else{
            $user_info = $this->user;
            if($user_info){
                $level = array('普通会员','一星会员','二星会员','三星会员','四星会员','五星会员','高级会员A','高级会员B','高级会员C');
                $user_info['lel'] = $level[$user_info['level']];
            }
            $this->assign("user_info",$user_info);
            $this->display();
        }

    }

}