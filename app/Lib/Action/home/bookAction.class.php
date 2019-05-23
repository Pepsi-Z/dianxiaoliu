<?php

/**
 * 逛宝贝页面
 */
class bookAction extends frontendAction {

    public function _initialize() {
        parent::_initialize();
        $this->assign('nav_curr', 'book');
        $this->get_user_info(0);
    }
    
	public function wx_index(){
    	$_SESSION['openid'] = $_GET['openid'];
    	$this->redirect('index');
    }

    public function index(){
    	require_once 'app/Common/MessageUtil.php';
        if(IS_POST) {
            $this->get_user_info(1);
            $data = $_POST;
            $data['yuyue_time'] = strtotime($data['yuyue_time']);
            if (!is_mobile($data['mobile'])) {
                $this->error( '手机号码不正确！');
            }
            if($data['cate_id'] == ''){
                $this->error( '请选择预约项目！');
            }

            if($data['yuyue_time'] == '' || $data['yuyue_time'] < time()){
                $this->error( '请至少提前一天预约!');
            }
            $cname = M('activity')->where('id = '.$data['cate_id'])->getField("title");
            $str = '';
            $data['add_time'] = time();
            $data['uid'] = $this->user['id'];
            $data['num'] = 'YY'.time().rand(100,9999);
            $y = M('yuyue')->add($data);
            if($y !== FALSE){
            	$str .= "您有一条新的预约消息：\n";
            	$str .= "预约者姓名：".$data['name']."\n";
            	$str .= "联系电话：".$data['mobile']."\n";
            	$str .= "预约项目：".$cname."\n";
            	$str .= "预约时间：".date('Y-m-d',$data['yuyue_time'])."\n";
            	$str .= "请尽快处理!";
            	$admin = M('admin')->table('weixin_admin a ')
            	->field('w.openid')
            	->join('weixin_wechatuser w on a.wx_id = w.id')
            	->where("a.wx_id is not null")->select();
            	
            	foreach ($admin as $k=>$v){
            		if($v['openid']){            		
            			MessageUtil::sendTextInfo($v['openid'],$str);
            		}
            	}
                $string .= "您有一条新的预约消息：\n";
                $string .= "预约者姓名：".$data['name']."\n";
                $string .= "联系电话：".$data['mobile']."\n";
                $string .= "预约项目：".$cname."\n";
                $string .= "预约时间：".date('Y-m-d',$data['yuyue_time'])."\n";
                $string .= "您的预约已成功^_^";
                MessageUtil::sendTextInfo($this->user['openid'],$string);
                $this->success("恭喜您预约成功");
            }
        }else{
			$_SESSION['foot_id'] = $_GET['foot_id'];
            $cate = M('activity')->field('id,title name')->select();
            $this->assign('cate',$cate);
            $this->display();
        }

    }




    /**
     * 逛宝贝首页
     */
    public function index111() {
      /*  $hot_tags = explode(',', C('pin_hot_tags')); //热门标签
        $page_max = C('pin_book_page_max'); //发现页面最多显示页数
        $sort = $this->_get('sort', 'trim', 'hot'); //排序
        $tag = $this->_get('tag', 'trim'); //当前标签

        $where = array();
        /*
          if ($tag) {
          $item_tag_table = C('DB_PREFIX').'item_tag';
          $tag_id = M('tag')->where(array('name'=>$tag))->getField('id');
          $where = array($item_tag_table.'.tag_id'=>$tag_id);
          //排序：最热(hot)，最新(new)
          switch ($sort) {
          case 'hot':
          $order = 'i.hits DESC,i.id DESC';
          break;
          case 'new':
          $order = 'i.id DESC';
          break;
          }
          $this->tcate_waterfall($where, $order);
          } else {
         */
       /* $tag && $where['intro'] = array('like', '%' . $tag . '%');
        //排序：最热(hot)，最新(new)
        switch ($sort) {
            case 'hot':
                $order = 'hits DESC,id DESC';
                break;
            case 'new':
                $order = 'id DESC';
                break;
        }
        $this->waterfall($where, $order, '', $page_max);
        //}

        $this->assign('hot_tags', $hot_tags);
        $this->assign('tag', $tag);
        $this->assign('sort', $sort);
        $this->_config_seo(C('pin_seo_config.book'), array('tag_name' => $tag)); //SEO*/
		$item_cates = M('item_cate')->where(array('pid'=>0))->order('ordid asc')->select();
		foreach($item_cates as $key=>$val){
			$item_cates[$key]['sub_cates']=M('item_cate')->where(array('pid'=>$val['id']))->order('ordid asc')->select();
			//$item_cates['sub_cates']=$sub_cates;
		}
		$this->assign('item_cates', $item_cates);
        $this->display();
    }

    public function index_ajax() {
        $tag = $this->_get('tag', 'trim'); //标签
        $sort = $this->_get('sort', 'trim', 'hot'); //排序
        switch ($sort) {
            case 'hot':
                $order = 'hits DESC,id DESC';
                break;
            case 'new':
                $order = 'id DESC';
                break;
        }
        $where = array();
        $tag && $where['intro'] = array('like', '%' . $tag . '%');
        $this->wall_ajax($where, $order);
    }

    /**
     * 按分类查看
     */
    public function cate() {
        $cid = $this->_get('cid', 'intval');
        !$cid && $this->_404();
        //分类数据
        if (false === $cate_data = F('cate_data')) {
            $cate_data = D('item_cate')->cate_data_cache();
        }
        //当前分类信息
        if (isset($cate_data[$cid])) {
            $cate_info = $cate_data[$cid];
        } else {
            $this->_404();
        }
        //分类列表
        if (false === $cate_list = F('cate_list')) {
            $cate_list = D('item_cate')->cate_cache();
        }
        //分类关系
        if (false === $cate_relate = F('cate_relate')) {
            $cate_relate = D('item_cate')->relate_cache();
        }
        //获取当前分类的顶级分类
        $tid = $cate_relate[$cid]['tid'];

        //商品
        $sort = $this->_get('sort', 'trim', 'pop');
        $min_price = $this->_get('min_price', 'intval');
        $max_price = $this->_get('max_price', 'intval');
        //条件
        $where = array();
        //排序：潮流(pop)，最热(hot)，最新(new)
        switch ($sort) {
            case 'pop':
                $order = 'likes DESC';
                break;
            case 'hot':
                $order = 'hits DESC';
                break;
            case 'new':
                $order = 'id DESC';
                break;
        }
        //分类
        if ($cate_info['type'] == 0) {
            $min_price && $where['price'][] = array('egt', $min_price);
            $max_price && $where['price'][] = array('elt', $max_price); //价格
            //实物分类
            $cate_relate[$cid]['sids'][] = $cid;
            $where['cate_id'] = array('in', $cate_relate[$cid]['sids']);
         // var_dump($cate_relate[$cid]['sids']);exit;
           // $this->waterfall($where, $order);
        } else {
            //标签组
            $min_price && $where['i.price'][] = array('egt', $min_price);
            $max_price && $where['i.price'][] = array('elt', $max_price); //价格

            $db_pre = C('DB_PREFIX');
            $item_tag_table = $db_pre . 'item_tag';
            $tag_ids = M('item_cate_tag')->where(array('cate_id' => $cid))->getField('tag_id', true);
            if ($tag_ids) {
                $where[$item_tag_table . '.tag_id'] = array('IN', $tag_ids);

                $pentity_id = D('item_cate')->get_pentity_id($cid); //向上找实体分类
                $cate_relate[$pentity_id]['sids'][] = $pentity_id;
                $where['i.cate_id'] = array('IN', $cate_relate[$pentity_id]['sids']); //分类条件

               // $this->tcate_waterfall($where, 'i.' . $order);
            }
        }

      //商品信息
          $cid = $this->_get('cid', 'intval');
          $item=M('item');
          $where['cate_id']=array('eq',$cid);
          $where['status']=array('eq',1);
          $where['cate_id'] = array('in', $cate_relate[$cid]['sids']);
         $items=  $item->field('id,title,img,price')->order('ordid asc,id desc')->where($where)->select();

		 $count=count($items);//商品总数
         $page_size =12; //每页显示个数
		// $pager = new Page($count, $pagesize);
        $pager = $this->_pager($count, $page_size);
		//echo $pager;exit;
		$item_list =  $item->field('id,title,img,price')->where($where)->order('ordid asc,id desc')->limit($pager->firstRow . ',' . $page_size)->select();

		$this->assign('item_list',$item_list);
		 //当前页码
        $p = $this->_get('p', 'intval', 1);
        $this->assign('p', $p);
        //当前页面总数大于单次加载数才会执行动态加载
        if (($count - ($p-1) * $page_size) > $spage_size) {
            $this->assign('show_load', 1);
        }
        //总数大于单页数才显示分页
        $count > $page_size && $this->assign('page_bar', $pager->fshow());
        //最后一页分页处理
        if ((count($item_list) + $page_size * ($p-1)) == $count) {
            $this->assign('show_page', 1);
        }
		//echo M()->getLastSql();exit;
		//dump($item_list);exit;
		 //var_dump($items);exit;
		$cateimg = D('item_cate')->field('img')->where(array('id'=>$cid))->find();
        $cate_info['img'] =$cateimg['img'];//分类图片
        $this->assign('cate_list', $cate_list); //分类
        $this->assign('tid', $tid); //顶级分类ID
        $this->assign('cate_info', $cate_info); //当前分类信息
        $this->assign('sort', $sort); //排序
        $this->assign('min_price', $min_price); //最低价格
        $this->assign('max_price', $max_price); //最高价格
        $this->assign('nav_curr', 'cate'); //导航设置
        //SEO
        $this->_config_seo(C('pin_seo_config.cate'), array(
            'cate_name' => $cate_info['name'],
            'seo_title' => $cate_info['seo_title'],
            'seo_keywords' => $cate_info['seo_keys'],
            'seo_description' => $cate_info['seo_desc'],
        ));
        $this->display();
    }

    /**
     * 标签分类瀑布
     */
    public function tcate_waterfall($where, $order = 'i.id DESC', $field = '') {
        $db_pre = C('DB_PREFIX');
        $item_tag_table = $db_pre . 'item_tag';
        $item_tag_mod = M('item_tag');
        $where_init = array('i.status' => '1');
        $where = array_merge($where_init, $where);
        $count = $item_tag_mod->where($where)->join($db_pre . 'item i ON i.id = ' . $item_tag_table . '.item_id')->count();
        $spage_size = C('pin_wall_spage_size'); //每次加载个数
        $spage_max = C('pin_wall_spage_max'); //每页加载次数
        $page_size = $spage_size * $spage_max; //每页显示个数
        $pager = $this->_pager($count, $page_size);
        !$field && $field = 'i.id,i.uid,i.uname,i.title,i.intro,i.img,i.price,i.likes,i.comments,i.comments_cache';
        $item_list = $item_tag_mod->field($field)->where($where)->join($db_pre . 'item i ON i.id = ' . $item_tag_table . '.item_id')->order($order)->limit($pager->firstRow . ',' . $spage_size)->select();
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

    public function cate_ajax() {
        $cid = $this->_get('cid', 'intval');
        //$sort = $this->_get('sort', 'trim', 'pop');
       // $min_price = $this->_get('min_price', 'intval');
        //$max_price = $this->_get('max_price', 'intval');

        //分类数据
        if (false === $cate_data = F('cate_data')) {
            $cate_data = D('item_cate')->cate_data_cache();
        }
        //分类关系
        if (false === $cate_relate = F('cate_relate')) {
            $cate_relate = D('item_cate')->relate_cache();
        }

        //条件
        $where = array();
        //排序：潮流(pop)，最热(hot)，最新(new)
      /*  switch ($sort) {
            case 'pop':
                $order = 'likes DESC';
                break;
            case 'hot':
                $order = 'hits DESC';
                break;
            case 'new':
                $order = 'id DESC';
                break;
        }*/
        if ($cate_data[$cid]['type'] == 0) {
            //实物分类
          //  $min_price && $where['price'][] = array('egt', $min_price);
          //  $max_price && $where['price'][] = array('elt', $max_price); //价格

            array_push($cate_relate[$cid]['sids'], $cid);
            $where['cate_id'] = array('in', $cate_relate[$cid]['sids']); //分类

            $this->wall_ajax($where, $order);
        } else {
            //标签组
         //   $min_price && $where['i.price'][] = array('egt', $min_price);
          //  $max_price && $where['i.price'][] = array('elt', $max_price); //价格

            $db_pre = C('DB_PREFIX');
            $item_tag_table = $db_pre . 'item_tag';
            $tag_ids = M('item_cate_tag')->where(array('cate_id' => $cid))->getField('tag_id', true);
            if ($tag_ids) {
                $where[$item_tag_table . '.tag_id'] = array('IN', $tag_ids);
                $pentity_id = D('item_cate')->get_pentity_id($cid); //向上找实体分类
                array_push($cate_relate[$pentity_id]['sids'], $pentity_id);
                $where['i.cate_id'] = array('IN', $cate_relate[$pentity_id]['sids']); //分类条件
                $this->tcate_wall_ajax($where, 'i.' . $order);
            }
        }
    }

    public function tcate_wall_ajax($where, $order = 'i.id DESC', $field = '') {
        $db_pre = C('DB_PREFIX');
        $item_tag_table = $db_pre . 'item_tag';
        $item_tag_mod = M('item_tag');

        $spage_size = C('pin_wall_spage_size'); //每次加载个数
        $spage_max = C('pin_wall_spage_max'); //加载次数
        $p = $this->_get('p', 'intval', 1); //页码
        $sp = $this->_get('sp', 'intval', 1); //子页
        //条件
        $where_init = array('i.status' => '1');
        $where = array_merge($where_init, $where);
        //计算开始
        $start = $spage_size * ($spage_max * ($p - 1) + $sp);
        $item_mod = M('item');
        $count = $item_tag_mod->where($where)->join($db_pre . 'item i ON i.id = ' . $item_tag_table . '.item_id')->count();
        !$field && $field = 'i.id,i.uid,i.uname,i.title,i.intro,i.img,i.price,i.likes,i.comments,i.comments_cache';
        $item_list = $item_tag_mod->field($field)->where($where)->join($db_pre . 'item i ON i.id = ' . $item_tag_table . '.item_id')->order($order)->limit($start . ',' . $spage_size)->select();
        foreach ($item_list as $key => $val) {
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

}