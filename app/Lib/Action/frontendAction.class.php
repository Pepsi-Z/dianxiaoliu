<?php
/**
 * 前台控制器基类
 *
 * @author andery
 */
include_once './app/Common/MessageUtil.php';
class frontendAction extends baseAction
{
    public $item_cate = '';
    public $user = '';
    public $auth = '';
    public $openid = '';
    protected $visitor = null;

    public function _initialize()
    {

        parent::_initialize();
        //网站状态
        if (!C('pin_site_status')) {
            header('Content-Type:text/html; charset=utf-8');
            exit(C('pin_closed_reason'));
        }
        //商品分类信息
        $this->get_item_cate();
        $openid = $_GET['openid'] ? $_GET['openid'] : cookie('openid');
        if ($openid) {
            cookie('openid', $openid, time() + 3600 * 24);
        }
        //$this->openid = $this->get_openids();
        $this->openid = 'oM0qSwzGjZVxl2nmjRXKH3GHrQJw';
        $user = M('user')->where('openid = ' . "'$this->openid'")->find();
        if ($user) {
            $_SESSION['user'] = $user;
        }

        /*if(empty($_SESSION['user']['username'])){
            $this->redirect(U('User/wanshan',array('status'=>1,'openid'=>$openid)));
        }*/

//        $this->assign('openid',$this->openid);
//        $this->get_user_info();
        $this->assign('nav_curr', '');
    }

    public function get_user_info($type = 0)
    {
        //$openid = $this->get_openids();
        $openid = 'oM0qSwzGjZVxl2nmjRXKH3GHrQJw';
        if ($openid) {
            cookie('openid', $openid, time() + 3600 * 24);
        }
        if ($openid) {
            $this->user = M('user')->where("openid = '" . $openid . "'")->find();
//            echo M('user')->_sql();
            if (empty($this->user)) {
//                echo $type;
                if ($type) {
                    $status = I('get.status');
                    if (!$status) {
                        $this->redirect(U('Member/login', array('status' => 1, 'openid' => $openid)));
                        die();
                    }
                    if (!$this->user['username']) {
                        $this->redirect(U('User/wanshan', array('status' => 1, 'openid' => $openid)));
                    }

                } else {
                    $this->user['openid'] = $openid;
                    if (M('user')->where(array('openid' => $openid))->find() !== false) {
                        //$this->user['id'] = M('user')->add($this->user) ;
                    }

                }
            }
//           p($this->user);

            $star = array('', '★', '★★', '★★★', '★★★★', '★★★★★', '★★★★★★', '★★★★★★★', '★★★★★★★★');
            $this->user['star'] = $star[$this->user['level']];

        }

        $this->assign('user', $this->user);

    }


    public function get_manage_info()
    {
        //$openid = $this->get_openids();
        $openid = 'oM0qSwzGjZVxl2nmjRXKH3GHrQJw';
        if ($openid) {
            cookie('openid', $openid, time() + 3600 * 24);
        }
        $status = $_GET['status'];
        if ($openid) {
            $this->merchant = M('user_merchant')->where("openid = '" . $openid . "'")->find();
            $_SESSION['manage'] = $this->merchant;
            if (empty($this->merchant['id']) && $status != 1){
                $this->redirect(U('Shop/m_login',array('status' => 1)));
                die();
            }
            if(!empty($this->merchant)){
                $this->redirect(U('Shop/merchant_order'));
                die();
            }
        }
        $this->assign('manage', $this->merchant);
    }


    public function get_openid()
    {
        $user = M('user')->where('openid = ' . "'$this->openid'")->find();
        if ($user) {
            $_SESSION['user'] = $user;
        }
        return $user;

    }


//	public function get_user(){
//		$openid = $_GET['openid'];
////        $openid = '123456789';
//		if($openid){
//			$_SESSION['openid'] = $openid;
//		}
//
//		if($_SESSION['openid']){
//              $this->user = M('user')->where("openid = '".$_SESSION['openid']."'")->find();
//		      $_SESSION['user'] = $this->user;
//		}
//
//		$status = $this->_get('status', 'intval');
//		if(!$_SESSION['user']){
//			if(!$status){
//			}
//		}
//		$star = array('','★','★★','★★★','★★★★','★★★★★','★★★★★★','★★★★★★★','★★★★★★★★');
//        $this->user['star'] = $star[$this->user['level']];
//        $this->assign('user',$this->user);
//    }
    //ajax_get_address
    public function ajax_get_address()
    {
        $pid = $this->_post('id', 'trim');
        $data['data'] = M('area')->where('pid = ' . $pid)->select();
        if ($data) {
            $data['status'] = 1;
        } else {
            $data['status'] = 0;
        }
        echo json_encode($data);
    }

    public function get_address($pid, $cid, $aid)
    {
        $pname = M('area')->where("id = " . intval($pid))->getField('name');
        $cname = M('area')->where("id = " . intval($cid))->getField('name');
        $aname = M('area')->where("id = " . intval($aid))->getField('name');
        $saddress = $pname . (($cname) ? '&nbsp;' . $cname : '&nbsp;') . $aname;
        return $saddress;
    }

    public function get_hout_address($pid, $cid, $aid)
    {
        $pname = M('region')->where("id = " . intval($pid))->getField('name');
        $cname = M('region')->where("id = " . intval($cid))->getField('name');
        $aname = M('region')->where("id = " . intval($aid))->getField('name');
        $saddress = $pname . (($cname) ? '&nbsp;' . $cname : '&nbsp;') . $aname;
        return $saddress;
    }

    protected function get_item_cate()
    {
        $this->item_cate = M('item_cate')->where(array('pid' => 0))->select();
        $data = $this->item_cate;
        foreach ($data as $k => $v) {
            $data[$k]['sub'] = M('item_cate')->where(array('pid' => $v['id']))->select();
        }
        $this->item_cate = $data;
//        p($data);
        $this->assign('item_cate', $this->item_cate);
    }

    private function _index_cate()
    {
        //分类
        if (false === $index_cate_list = F('index_cate_list')) {
            $item_cate_mod = M('item_cate');
            //分类关系
            if (false === $cate_relate = F('cate_relate')) {
                $cate_relate = D('item_cate')->relate_cache();
            }
            //分类缓存
            if (false === $cate_data = F('cate_data')) {
                $cate_data = D('item_cate')->cate_data_cache();
            }
            //推荐到首页的大类
            $index_cate_list = $item_cate_mod->field('id,name,img')->where(array('pid' => '0', 'is_index' => '1', 'status' => '1'))->order('ordid')->select();
            foreach ($index_cate_list as $key => $val) {
                //推荐到首页的子类
                $where = array('status' => '1', 'is_index' => '1', 'spid' => array('like', $val['id'] . '|%'));
                $index_cate_list[$key]['index_sub'] = $item_cate_mod->field('id,name,img')->where($where)->order('ordid desc')->select();
                //普通子类
                $index_cate_list[$key]['sub'] = array();
                foreach ($cate_relate[$val['id']]['sids'] as $sid) {
                    if ($cate_data[$sid]['type'] == '0' && $cate_data[$sid]['pid'] != $val['id']) {
                        $index_cate_list[$key]['sub'][] = $cate_data[$sid];
                    }
                    if (count($index_cate_list[$key]['sub']) >= 6) {
                        break;
                    }
                }
            }
            F('index_cate_list', $index_cate_list);
        }

        // echo "<pre>";
        // var_dump($index_cate_list);
        //echo "</pre>";
        $this->assign('index_cate_list', $index_cate_list);
    }

    /**
     * 初始化访问者
     */
    private function _init_visitor()
    {
        $this->visitor = new user_visitor();
        $this->assign('visitor', $this->visitor->info);
    }

    /**
     * 第三方登陆模块
     */
    private function _assign_oauth()
    {
        if (false === $oauth_list = F('oauth_list')) {
            $oauth_list = D('oauth')->oauth_cache();
        }
        $this->assign('oauth_list', $oauth_list);
    }

    /**
     * SEO设置
     */
    protected function _config_seo($seo_info = array(), $data = array())
    {
        $page_seo = array(
            'title' => C('pin_site_title'),
            'keywords' => C('pin_site_keyword'),
            'description' => C('pin_site_description')
        );
        $page_seo = array_merge($page_seo, $seo_info);
        //开始替换
        $searchs = array('{site_name}', '{site_title}', '{site_keywords}', '{site_description}');
        $replaces = array(C('pin_site_name'), C('pin_site_title'), C('pin_site_keyword'), C('pin_site_description'));
        preg_match_all("/\{([a-z0-9_-]+?)\}/", implode(' ', array_values($page_seo)), $pageparams);
        if ($pageparams) {
            foreach ($pageparams[1] as $var) {
                $searchs[] = '{' . $var . '}';
                $replaces[] = $data[$var] ? strip_tags($data[$var]) : '';
            }
            //符号
            $searchspace = array('((\s*\-\s*)+)', '((\s*\,\s*)+)', '((\s*\|\s*)+)', '((\s*\t\s*)+)', '((\s*_\s*)+)');
            $replacespace = array('-', ',', '|', ' ', '_');
            foreach ($page_seo as $key => $val) {
                $page_seo[$key] = trim(preg_replace($searchspace, $replacespace, str_replace($searchs, $replaces, $val)), ' ,-|_');
            }
        }
        $this->assign('page_seo', $page_seo);
    }


    //微信上传头像引入
    public function get_wx_config($url)
    {
        // 分享需要的参数
        $access_token = MessageUtil::initToken();
        $access_token = MessageUtil::checkToken('', $access_token);

        $noncestr = $this->get_Rand();
        $timestamp = time();
        $jsapi_ticket = $this->get_jsapi_ticket($access_token);

        $str = "jsapi_ticket={$jsapi_ticket}&noncestr={$noncestr}&timestamp={$timestamp}&url={$url}";
        $signature = sha1($str);

        $data['timestamp'] = $timestamp;
        $data['nonceStr'] = $noncestr;
        $data['signature'] = $signature;
        return $data;
    }


    public function get_Rand()
    {
        $c = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        srand((double)microtime() * 1000000);
        for ($i = 0; $i < 16; $i++) {
            $rand .= $c[rand() % strlen($c)];
        }
        return $rand;
    }

    public function get_jsapi_ticket($access_token)
    {

        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=jsapi";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        //curl_setopt($ch,CURLOPT_POSTFIELDS,$data_string);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );

        // 查看错误
        //echo curl_errno();
        $response = curl_exec($ch);
        $result = json_decode($response, true);
        curl_close($ch);
        return $result['ticket'];
    }

    public function downImg($MEDIA_ID)
    {
        $access_token = MessageUtil::initToken();
        $access_token = MessageUtil::checkToken('', $access_token);
        $rand = $this->get_Rand();
        $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token={$access_token}&media_id={$MEDIA_ID}";
        $fileInfo = $this->downloadWexinFile($url);
        $filename = "./data/upload/wx_load/" . $rand . ".jpg";
        $this->saveWeixinFile($filename, $fileInfo['body']);
        return $filename;
    }

    public function downloadWexinFile($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);
        curl_close($ch);
        $arr = array_merge(array('header' => $httpinfo),
            array('body' => $package));
        return $arr;
    }

    public function saveWeixinFile($filename, $filecontent)
    {
        $local_file = fopen($filename, 'w');
        if (false !== $local_file) {
            if (false !== fwrite($local_file, $filecontent)) {
                fclose($local_file);
            }
        }
    }


    /**
     * 连接用户中心
     */
    protected function _user_server()
    {
        $passport = new passport(C('pin_integrate_code'));
        return $passport;
    }

    /**
     * 前台分页统一
     */
    protected function _pager($count, $pagesize)
    {
        $pager = new Page($count, $pagesize);
        $pager->rollPage = 5;
        $pager->setConfig('prev', '<');
        $pager->setConfig('theme', '%upPage% %first% %linkPage% %end% %downPage%');
        return $pager;
    }

    /**
     * 瀑布显示
     */
    public function waterfall($where = array(), $order = 'id DESC', $field = '', $page_max = '', $target = '')
    {
        $spage_size = C('pin_wall_spage_size'); //每次加载个数
        $spage_max = C('pin_wall_spage_max'); //每页加载次数
        $page_size = $spage_size * $spage_max; //每页显示个数

        $item_mod = M('item');
        $where_init = array('status' => '1');
        $where = $where ? array_merge($where_init, $where) : $where_init;
        $count = $item_mod->where($where)->count('id');
        //控制最多显示多少页
        if ($page_max && $count > $page_max * $page_size) {
            $count = $page_max * $page_size;
        }
        //查询字段
        $field == '' && $field = 'id,uid,uname,title,intro,img,price,likes,comments,comments_cache';
        //分页
        $pager = $this->_pager($count, $page_size);
        $target && $pager->path = $target;
        $item_list = $item_mod->field($field)->where($where)->order($order)->limit($pager->firstRow . ',' . $spage_size)->select();
        foreach ($item_list as $key => $val) {
            isset($val['comments_cache']) && $item_list[$key]['comment_list'] = unserialize($val['comments_cache']);
        }
        $this->assign('item_list', $item_list);
        //当前页码
        $p = $this->_get('p', 'intval', 1);
        $this->assign('p', $p);
        //当前页面总数大于单次加载数才会执行动态加载
        if (($count - ($p - 1) * $page_size) > $spage_size) {
            $this->assign('show_load', 1);
        }
        //总数大于单页数才显示分页
        $count > $page_size && $this->assign('page_bar', $pager->fshow());
        //最后一页分页处理
        if ((count($item_list) + $page_size * ($p - 1)) == $count) {
            $this->assign('show_page', 1);
        }
    }

    /**
     * 瀑布加载
     */
    public function wall_ajax($where = array(), $order = 'id DESC', $field = '')
    {
        $spage_size = C('pin_wall_spage_size'); //每次加载个数
        $spage_max = C('pin_wall_spage_max'); //加载次数
        $p = $this->_get('p', 'intval', 1); //页码
        $sp = $this->_get('sp', 'intval', 1); //子页

        //条件
        $where_init = array('status' => '1');
        $where = array_merge($where_init, $where);
        //计算开始
        $start = $spage_size * ($spage_max * ($p - 1) + $sp);
        $item_mod = M('item');
        $count = $item_mod->where($where)->count('id');
        $field == '' && $field = 'id,uid,uname,title,intro,img,price,likes,comments,comments_cache';
        $item_list = $item_mod->field($field)->where($where)->order($order)->limit($start . ',' . $spage_size)->select();
        foreach ($item_list as $key => $val) {
            //解析评论
            isset($val['comments_cache']) && $item_list[$key]['comment_list'] = unserialize($val['comments_cache']);
        }
        $this->assign('item_list', $item_list);
        $resp = $this->fetch('public:waterfall');
        $data = array(
            'isfull' => 1,
            'html' => $resp
        );
        $count <= $start + $spage_size && $data['isfull'] = 0;
        $this->ajaxReturn(1, '', $data);
    }
    public function street(){
        $jd = array('1'=>'人民北路','2'=>'人民中路','3'=>'人民南路',
            '4'=>'邮电街',
            '5'=>'珠海街',
            '6'=>'深圳街',
            '7'=>'香港街',
            '8'=>'东山路',
            '9'=>'江苏路',
            '10'=>'柳林路',
            '11'=>'上海路',
            '12'=>'源园路',
            '13'=>'献珍路',
            '14'=>'北京北路',
            '15'=>'北京中路',
            '16'=>'北京南路',
            '17'=>'京东路',
            '18'=> '浙江路',
            '19'=>'天津路',
            '20'=>'重庆路',
            '21'=>'公园路',
            '22'=>'大岭路',
            '23'=>'广西路',
            '24'=>'车城路',
            '25'=>'车城南路',
            '26'=>'车城西路',
            '27'=>'凯旋大道',
            '28'=>'车站路',
            '29'=>'朝阳北路',
            '30'=>'朝阳中路',
            '31'=>'朝阳南路',
            '32'=>'广东路',
            '33'=>'荆州路',
            '34'=>'山西路',
            '35'=>'东岳路',
            '36'=>'中岳路',
            '37'=>'南岳路',
            '38'=>'汉江南路',
            '39'=>'汉江北路',
            '40'=>'土天路',
            '41'=>'镜潭路',
            '42'=>'贵州路',
            '43'=>'方山路',
            '44'=>'建设大道',
            '45'=>'火箭路',
            '46'=>'施洋路',
            '47'=>'东风大道',
            '48'=>'武当大道',
            '49'=>'丹江路',
            '50'=>'七里路',
            '51'=>'白浪西路',
            '52'=>'白浪中路',
            '53'=>'发展大道',
            '54'=>'许白路');
         return $jd;
     }






}