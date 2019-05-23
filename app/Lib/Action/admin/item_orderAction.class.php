<?php
class item_orderAction extends backendAction {
    public function _initialize() {
        parent::_initialize();
        $this->_mod = D('item_order');
        $order_status=array(1=>'待付款',2=>'待配送',3=>'已配送',4=>'完成',5=>'关闭');
        $this->assign('order_status',$order_status);
    }

    public function order_warning() {
        if(true){
            $count = M('item_order')->where("warning = 1 and type = 1 and status = 2 and delete = 0 ")->count();
            if($count > 0){
                $arr['state'] = 1;
            }
            $arr['count'] = $count;

            echo json_encode($arr);
        }
      
    }

    public function status()
    {
      $orderId= $this->_get('orderId', 'trim');
      !$orderId && $this->_404();
      $status= $this->_get('status', 'intval');
      !$status && $this->_404();
      if($status == 4)
      {
      	$data['status']=$status;
      if($this->_mod->where(array('orderId'=>$orderId))->save($data))
      	{
      		$order_detail=M('order_detail');
      		$item=M('item');
	     	$order_details = $order_detail->where(array('orderId'=>$orderId))->select();
	    	foreach ($order_details as $val)
	     	{
	     	//$item->where('id='.$val['itemId'])->setInc('buy_num',$val['quantity']);
	        }
      		$this->success('修改成功!');
      	}else{
      		$this->error('修改失败!');
      	}
      	
      }else{
        $data['support_time'] = time();
      	$data['status']=$status;
      	if($this->_mod->where(array('orderId'=>$orderId))->save($data))
      	{
      		$this->success('修改成功!');
      	}else
      	{
      		$this->error('修改失败!');
      	}
      }
      
      
    }
    
    

    protected function _search() {
        $map = array();
        $map['type'] = 1;
        $map['delete'] = 0;
        ($time_start = $this->_request('time_start', 'trim')) && $map['add_time'][] = array('egt', strtotime($time_start));
        ($time_end = $this->_request('time_end', 'trim')) && $map['add_time'][] = array('elt', strtotime($time_end)+(24*60*60-1));
        
        ($time_start_support = $this->_request('start_support_time', 'trim')) && $map['support_time'][] = array('egt', strtotime($$time_start_support));
        ($time_end_support = $this->_request('end_support_time', 'trim')) && $map['support_time'][] = array('elt', strtotime($time_end_support)+(24*60*60-1));
        
        ($price_min = $this->_request('price_min', 'trim')) && $map['order_sumPrice'][] = array('egt', $price_min);
        ($price_max = $this->_request('price_max', 'trim')) && $map['order_sumPrice'][] = array('elt', $price_max);
      //  ($rates_min = $this->_request('rates_min', 'trim')) && $map['rates'][] = array('egt', $rates_min);
       // ($rates_max = $this->_request('rates_max', 'trim')) && $map['rates'][] = array('elt', $rates_max);
        ($userName = $this->_request('userName', 'trim')) && $map['userName'] = array('like', '%'.$userName.'%');
        ($userfree = $this->_request('userfree', 'trim')) && $map['userfree'] = array('like', '%'.$userfree.'%');
          ($status = $this->_request('status', 'trim')) && $map['status'] = array('eq', $status);
        ($orderId = $this->_request('orderId', 'trim')) && $map['orderId'] = array('like', '%'.$orderId.'%');
        $this->assign('search', array(
            'time_start' => $time_start,
            'time_end' => $time_end,
            'price_min' => $price_min,
           'price_max' => $price_max,
            'start_support_time' => $time_start_support,
           'end_support_time' => $time_end_support,
            'userName' => $userName,
            'status' =>$status,
            'selected_ids' => $spid,
            'orderId' => $orderId,
            'userfree'=>$userfree
        ));
        return $map;
    }

    public function add() {
    	
        if (IS_POST) {
            //获取数据
            if (false === $data = $this->_mod->create()) {
                $this->error($this->_mod->getError());
            }
            if( !$data['cate_id']||!trim($data['cate_id']) ){
                $this->error('请选择商品分类');
            }
           
            if($_POST['brand']==''){
            	
                $this->error('请选择品牌');
            }
         
            
            //必须上传图片
            if (empty($_FILES['img']['name'])) {
                $this->error('请上传商品图片');
            }
           if(isset($_POST['news']))
            {
            	$data['news']=1;
            }else {
            	$data['news']=0;
            }
             if(isset($_POST['tuijian']))
            {
            	$data['tuijian']=1;
            }else {
            	$data['tuijian']=0;
            }

            if($_POST['free']==1)
            {
            	$data['free']=1;
            }else if($_POST['free']==2)
            {
            $data['free']=2;
            $data['pingyou']=$this->_post('pingyou');
            $data['kuaidi']=$this->_post('kuaidi');
            $data['ems']=$this->_post('ems');
            }
            
          

            //上传图片
            $date_dir = date('ym/d/'); //上传目录
            $item_imgs = array(); //相册
            $result = $this->_upload($_FILES['img'], 'item/'.$date_dir, array(
                'width'=>C('pin_item_bimg.width').','.C('pin_item_img.width').','.C('pin_item_simg.width'), 
                'height'=>C('pin_item_bimg.height').','.C('pin_item_img.height').','.C('pin_item_simg.height'),
                'suffix' => '_b,_m,_s',
                //'remove_origin'=>true 
            ));
            if ($result['error']) {
                $this->error($result['info']);
            } else {
                $data['img'] = $date_dir . $result['info'][0]['savename'];
                //保存一份到相册
                $item_imgs[] = array(
                    'url'     => $data['img'],
                );
            }
            //上传相册
            $file_imgs = array();
            foreach( $_FILES['imgs']['name'] as $key=>$val ){
                if( $val ){
                    $file_imgs['name'][] = $val;
                    $file_imgs['type'][] = $_FILES['imgs']['type'][$key];
                    $file_imgs['tmp_name'][] = $_FILES['imgs']['tmp_name'][$key];
                    $file_imgs['error'][] = $_FILES['imgs']['error'][$key];
                    $file_imgs['size'][] = $_FILES['imgs']['size'][$key];
                }
            }
            if( $file_imgs ){
                $result = $this->_upload($file_imgs, 'item/'.$date_dir, array(
                    'width'=>C('pin_item_bimg.width').','.C('pin_item_simg.width'),
                    'height'=>C('pin_item_bimg.height').','.C('pin_item_simg.height'),
                    'suffix' => '_b,_s',
                ));
                if ($result['error']) {
                    $this->error($result['info']);
                } else {
                    foreach( $result['info'] as $key=>$val ){
                        $item_imgs[] = array(
                            'url'    => $date_dir . $val['savename'],
                            'order'  => $key + 1,
                        );
                    }
                }
            }
            $data['imgs'] = $item_imgs;
            $item_id = $this->_mod->publish($data);
            !$item_id && $this->error(L('operation_failure'));
            $this->success(L('operation_success'));
        } else {
       
            $this->display();
        }
    }

    protected function _list($model, $map = array(), $sort_by='', $order_by='', $field_list='*', $pagesize=20)
    {
        //排序
        $mod_pk = $model->getPk();

        if ($this->_request("sort", 'trim')) {
            $sort = $this->_request("sort", 'trim');
        } else if (!empty($sort_by)) {
            $sort = $sort_by;
        } else if ($this->sort) {
            $sort = $this->sort;
        } else {
            $sort = $mod_pk;
        }
        if ($this->_request("order", 'trim')) {
            $order = $this->_request("order", 'trim');
        } else if (!empty($order_by)) {
            $order = $order_by;
        } else if ($this->order) {
            $order = $this->order;
        } else {
            $order = 'DESC';
        }

        //如果需要分页
        if ($pagesize) {
            $count = $model->where($map)->count($mod_pk);
            $pager = new Page($count, $pagesize);
        }
        $select = $model->field($field_list)->where($map)->order($sort . ' ' . $order);
        $this->list_relation && $select->relation(true);
        if ($pagesize) {
            $select->limit($pager->firstRow.','.$pager->listRows);
            $page = $pager->show();
            $this->assign("page", $page);
        }
        $list = $select->select();
        $this->assign('list', $list);
        $this->assign('list_table', true);
    }

    public function edit() {
        if (IS_POST) {
           

          
        } else {
            $id = $this->_get('id','intval');
            $item = M('item_order')->where(array('id'=>$id))->find();
            
            $order_detail=M('order_detail')->where(array('orderId'=>$item['orderId']))->select();
            $p = '';
            foreach ($order_detail as $k=>$val)
            {
                $items_attr=$val['item_attr'];//商品属性
                $attr_arr=array_filter(explode(";",$items_attr));
                $attr_list=array();
                    foreach($attr_arr as $ke=>$va){
                        $attr_arr2=array_filter(explode("|",$va));
                        $attr_list[]=array('name'=>$attr_arr2[0],'value'=>$attr_arr2[1]);
                    }
                    //$items['attr']=$attr_list;//赋值items
                //$items= array('title'=>$val['title'],'img'=>$val['img'],'price'=>$val['price'],'quantity'=>$val['quantity'],'itemId'=>$val['itemId'],'attr'=>$attr_list);
                $order_detail[$k]['attr']=$attr_list;//赋值attr;
                $p += ($val['price']*$val['quantity']);
            }
            $fahuoaddress=M('address')->find($item['fahuoaddress']);
            $this->assign('fahuoaddress',$fahuoaddress);//发货地址
            $this->assign('order_detail',$order_detail);//订单商品信息
            $this->assign('info', $item); // 订单详细信息
            $this->assign('f_price', ($item['goods_sumPrice']-$p)); // 服务费
            $this->display();
        }
    }
    public function updateRemark(){
        $txtSellerRemark= $_POST['txtSellerRemark'];//用客服备注
        $id=$_POST['id'];//订单ID
        $data['sellerRemark']=$txtSellerRemark;
        if(M('item_order')->where('id='.$id)->save($data)!==false)
        {
            $this->success('修改成功！');
        }else 
        {
            $this->error('修改失败！');
        }
        
    
    }

    function delete_album() {
        $album_mod = M('item_img');
        $album_id = $this->_get('album_id','intval');
        $album_img = $album_mod->where('id='.$album_id)->getField('url');
        if( $album_img ){
            $ext = array_pop(explode('.', $album_img));
            $album_min_img = C('pin_attach_path') . 'item/' . str_replace('.' . $ext, '_s.' . $ext, $album_img);
            is_file($album_min_img) && @unlink($album_min_img);
            $album_img = C('pin_attach_path') . 'item/' . $album_img;
            is_file($album_img) && @unlink($album_img);
            $album_mod->delete($album_id);
        }
        echo '1';
        exit;
    }

    function delete_attr() {
        $attr_mod = M('item_attr');
        $attr_id = $this->_get('attr_id','intval');
        $attr_mod->delete($attr_id);
        echo '1';
        exit;
    }




    /**
     * ajax获取标签
     */
    public function ajax_gettags() {
        $title = $this->_get('title', 'trim');
        $tag_list = D('tag')->get_tags_by_title($title);
        $tags = implode(' ', $tag_list);
        $this->ajaxReturn(1, L('operation_success'), $tags);
    }
  public function wuxiao(){
    	$id = $this->_get('id','intval');
    	$data['status']=5;
    	$a = M('item_order')->where('id = '.$id)->save($data);
    
    	if( $a ){
            IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'add');
            $this->success(L('operation_success'));
        } else {
            IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
            $this->error(L('operation_failure'));
        }
    	
    }
    public function fahuo()
    {
    	 $mod = D($this->_name);
        if (IS_POST&&$this->_post('orderId','trim')) {
            $orderId= $this->_post('orderId','trim');//订单号ID
            if (false === $data = $mod->create()) {
                IS_AJAX && $this->ajaxReturn(0, $mod->getError());
                $this->error($mod->getError());
            }
            if (method_exists($this, '_before_insert')) {
                $data = $this->_before_insert($data);
            }
            if($_POST['delivery']=='0')
            {
            	$date['userfree']=0;
            }else 
            {
            	$date['userfree']=$_POST['delivery'];
            	$date['freecode']=$_POST['deliverycode'];
            	$date['fahuoaddress']=$data['address'];
            }
            $date['fahuo_time']=time();
            $date['status']=3;
            $date['warning'] = 0; //已经处理
            if($mod->where(array('orderId'=>$orderId))->data($date)->save()){
               
        		IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'add');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
              $this->error(L('operation_failure'));
          }
            
            
           /* if( $addid=$mod->add($data) ){
            
                IS_AJAX && $this->ajaxReturn(1, L('operation_success'), '', 'fahuo');
                $this->success(L('operation_success'));
            } else {
                IS_AJAX && $this->ajaxReturn(0, L('operation_failure'));
                $this->error(L('operation_failure'));
            }*/
        } else {
            $this->assign('open_validator', true);
            if (IS_AJAX) {
            if(count(M('address')->where('status=1')->find())==0)
            	{
            	$this->ajaxReturn(1, '', '请先添加默认收货地址！');
            	}
             $id= $this->_get('id','trim');//订单号ID
              $info= $this->_mod->find($id);
              $this->assign('info',$info);
             $deliveryList= M('delivery')->where('status=1')->order('ordid asc,id asc')->select();//快递方式
              $this->assign('deliveryList',$deliveryList);
              $addressList=M('address')->where('status=1')->order('ordid asc,id asc')->select();//快递方式
              $this->assign('addressList',$addressList);
                $response = $this->fetch();
                $this->ajaxReturn(1, '', $response);
            } else {
                
                $this->display();
            }
        }
    }



    public function peisong()
    {
            $mod = D($this->_name);
            $id  =  $_POST['id'];
            $date['fahuo_time']=time();
            $date['status']=3;
            $date['warning'] = 0; //已经处理
            if($mod->where(array('id'=>$id))->data($date)->save()){
                echo 1;
            } else {
                echo 0;
            }



    }


    public function delete_search() {
        $items_mod = D('item');
        $items_cate_mod = D('item_cate');
        $items_likes_mod = D('item_like');
        $items_pics_mod = D('item_img');
        $items_tags_mod = D('item_tag');
        $items_comments_mod = D('item_comment');

        if (isset($_REQUEST['dosubmit'])) {
            if ($_REQUEST['isok'] == "1") {
                //搜索
                $where = '1=1';
                $keyword = trim($_POST['keyword']);
                $cate_id = trim($_POST['cate_id']);
                $cate_id = trim($_POST['cate_id']);
                $time_start = trim($_POST['time_start']);
                $time_end = trim($_POST['time_end']);
                $status = trim($_POST['status']);
                $min_price = trim($_POST['min_price']);
                $max_price = trim($_POST['max_price']);
                $min_rates = trim($_POST['min_rates']);
                $max_rates = trim($_POST['max_rates']);

                if ($keyword != '') {
                    $where .= " AND title LIKE '%" . $keyword . "%'";
                }
                if ($cate_id != ''&&$cate_id!=0) {
                    $where .= " AND cate_id=" . $cate_id;
                }
                if ($time_start != '') {
                    $time_start_int = strtotime($time_start);
                    $where .= " AND add_time>='" . $time_start_int . "'";
                }
                if ($time_end != '') {
                    $time_end_int = strtotime($time_end);
                    $where .= " AND add_time<='" . $time_end_int . "'";
                }
                if ($status != '') {
                    $where .= " AND status=" . $status;
                }
                if ($min_price != '') {
                    $where .= " AND price>=" . $min_price;
                }
                if ($max_price != '') {
                    $where .= " AND price<=" . $max_price;
                }
                if ($min_rates != '') {
                    $where .= " AND rates>=" . $min_rates;
                }
                if ($max_rates != '') {
                    $where .= " AND rates<=" . $max_rates;
                }
                $ids_list = $items_mod->where($where)->select();
                $ids = "";
                foreach ($ids_list as $val) {
                    $ids .= $val['id'] . ",";
                }
                if ($ids != "") {
                    $ids = substr($ids, 0, -1);
                    $items_likes_mod->where("item_id in(" . $ids . ")")->delete();
                    $items_pics_mod->where("item_id in(" . $ids . ")")->delete();
                    $items_tags_mod->where("item_id in(" . $ids . ")")->delete();
                    $items_comments_mod->where("item_id in(" . $ids . ")")->delete();
                    M('album_item')->where("item_id in(" . $ids . ")")->delete();
                    M('item_attr')->where("item_id in(" . $ids . ")")->delete();

                }
                $items_mod->where($where)->delete();

                //更新商品分类的数量
                $items_nums = $items_mod->field('cate_id,count(id) as items')->group('cate_id')->select();
                foreach ($items_nums as $val) {
                    $items_cate_mod->save(array('id' => $val['cate_id'], 'items' => $val['items']));
                    M('album')->save(array('cate_id' => $val['cate_id'], 'items' => $val['items']));
                }

                $this->success('删除成功', U('item/delete_search'));
            } else {
                $this->success('确认是否要删除？', U('item/delete_search'));
            }
        } else {
            $res = $this->_cate_mod->field('id,name')->select();

            $cate_list = array();
            foreach ($res as $val) {
                $cate_list[$val['id']] = $val['name'];
            }
            //$this->assign('cate_list', $cate_list);
            $this->display();
        }
    }
    
    /**
     * Wechat::buildSign()
     * 生成sign值
     * @param array $array
     * @param array $config
     * @return string
     */
    public function buildSign($array, $config) {
        foreach ($array as $k => $v){
             $bizParameters[strtolower($k)] = $v;
         }
        $bizParameters["appkey"] =  $config['signkey'];
        ksort($bizParameters);
        //var_dump($bizParameters);
        $buff = "";
        foreach ($bizParameters as $k => $v){
                $buff .= strtolower($k) . "=" . $v . "&";
        }
        $reqPar;
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        $bizString = $reqPar;
        //var_dump($bizString);
        return sha1($bizString);

    }

    /**
     * wechat::getAccessToken()
     * 获取access_token
     * @return string
     */
    public function getAccessToken($config)
    {
    $ch = curl_init(); 
     curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$config['appid']."&secret=".$config['appsecret']); 
     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
     curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
     curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
     curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
     $tmpInfo = curl_exec($ch); 
     if (curl_errno($ch)) {  
        echo 'Errno'.curl_error($ch);
     }
     curl_close($ch); 
     $arr= json_decode($tmpInfo,true);
     return $arr['access_token'];
    }


    /**
     * Wechat::delivernotify()
     * 发货通知
     * @param array $config
     * @param array $parameter
     * @return array
     */
    public function delivernotify($config, $parameter) {
        $url = 'https://api.weixin.qq.com/pay/delivernotify?access_token=' . $this->getAccessToken($config);
        $parameter += array(
            'app_signature' => $this->buildSign($parameter, $config),
            'sign_method' => 'sha1'
        );
        //$this->log_result($parameter);
        $result = $this->doPost($url, json_encode($parameter));
        return json_decode($result, true);
    }
    /**
     * Wechat::doPost()
     * post请求
     * @param string $url
     * @param array $data
     * @return
     */
    public function doPost($url, $data) {
        $context = array('http' => array('method' => "POST", 'header' => "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) \r\n Accept: */*", 'content' => $data));
        $stream_context = stream_context_create($context);
        $request = @file_get_contents($url, FALSE, $stream_context);
        return $request;
    }
     /**
     * Wechat::log()
     * log日志
     * @param $word 日志信息
     * 
     */
    public function log_result($word) {
        $fp = fopen("log.txt","a");
        //flock($fp, LOCK_EX) ;
        fwrite($fp,"执行日期：".strftime("%Y%m%d%H%M%S",time())."\n".$word."\n\n");
        //flock($fp, LOCK_UN);
        fclose($fp);
    }
    
}