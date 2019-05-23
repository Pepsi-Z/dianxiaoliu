<?php
class keywordAction extends backendAction {

    public function _initialize() {
        parent::_initialize();
		$this->_mod = D('keyword');
    
    }


    
    public function addkeyword()//关键词
    {
	
    	$op = $_GET['op'];
    	if(!empty($op)){
    	$keyword = array();
    	$keyword['kename']    = trim($_POST['kename']);
    	$keyword['kyword']    = trim($_POST['keword']);
    	$keyword['type']      = intval($_POST['ketype']);
    	$linkinfo=$_POST['linkinfo'];
    	$titles=$_POST['titles'];
    	$imageinfo=$_POST['imageinfo'];
    	if($op == 'add') {
    		if($keyword['type']==1){
    			$keyword['kecontent'] = trim($_POST['kecontent']);
    			$keyword['linkinfo']  = '';
    			$keyword['titles']    = '';
    			$keyword['imageinfo'] = '';
    		}else{
    			$keyword['kecontent'] ='';
    			$keyword['linkinfo']  = serialize($linkinfo);
    			$keyword['titles']    = serialize($titles);
    			$keyword['imageinfo'] = serialize($imageinfo);
    		}
    		$keyword['iskey'] = 1;
	        M('keyword')->data($keyword)->add();
    	
    	}

    	if($op=='update'){
    		$kid = trim($_POST['kid']);
    		if($keyword['type']==1){
    			$keyword['kecontent'] = trim($_POST['kecontent']);
    		}else{
    			$keyword['linkinfo']  = serialize($linkinfo);
    			$keyword['titles']    = serialize($titles);
    			$keyword['imageinfo'] = serialize($imageinfo);
    		}
    		$where = array('kid' => $kid);
    		M('keyword')->where($where)->data($keyword)->save();
    		
    	}

    	if($op=='del'){
    		$kid = trim($_POST['kid']);
    		M('keyword')->where(array('kid'=>$kid))->delete();
    	}
    	}else 
    	{   $keyinfo=M('keyword')->where('iskey=1')->order('kid desc')->select();
    		$this->assign('keyinfo',$keyinfo);
    		$this->display();
    	}
    }
    public function addkeyword_ajax()
    {
    	$kid = $_GET['kid'];
    	 $keyinfo=M('keyword')->where("iskey=1 AND kid=".$kid)->select();
    	//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE iskey=1 AND kid='.$kid);

    	foreach($keyinfo as $k=>$v) {
    		$titles                   = unserialize($v['titles']);
    		$imageinfo                = unserialize($v['imageinfo']);
    		$linkinfo                 = unserialize($v['linkinfo']);

    		$keyinfo[$k]['titles']    = $titles;
    		$keyinfo[$k]['imageinfo'] = $imageinfo;
    		$keyinfo[$k]['linkinfo']  = $linkinfo;
    	}

    	echo json_encode($keyinfo);

    }
    public function  allimages(){
    	$imagesinfo=M('images')->order('iid desc')->select();
    	echo json_encode($imagesinfo);
    }
    
    
    public function ajaxupload(){//上传图片
    	if($_POST['sub'] == 'submit') {
	   if($_FILES['image']['error']['image']==0){   
		    $date_dir = date('ym/d/'); //上传目录
            if( $_FILES['image'] ){
    		 $result =  $this->_upload($_FILES['image'], 'keyword/'.$date_dir);
             if ($result['error']) {
             	$item_url=$result['info'];
                   // $this->error($result['info']);
                } else {
                	$item_url=C('pin_attach_path') ."keyword/".$date_dir.$result['info'][0]['savename'];
                   // foreach( $result['info'] as $key=>$val ){
                  //      $item_imgs[] = array(
                   //         'url'    => $date_dir . $val['savename'],
                  //      );
                  //  }
                }
            }
	   }
	   $image = array (
	   	'imgurl'   =>  $item_url
	   );
	  M('images')->data($image)->add();
	  
	
	  }
    }
    

    public function ajaxkeyword(){
    	
    	$keyinfo=M('keyword')->where(' iskey=1 AND type=2')->select();
    
    	foreach($keyinfo as $k=>$v) {
    		$titles                = unserialize($v['titles']);
    		$imageinfo            = unserialize($v['imageinfo']);
    		$linkinfo                = unserialize($v['linkinfo']);
    		$keyinfo[$k]['titles']   = $titles;
    		$keyinfo[$k]['imageinfo'] = $imageinfo;
    		$keyinfo[$k]['linkinfo']  = $linkinfo;

    	}
    	echo json_encode($keyinfo);
    	exit;
    }
   
  
    
	
	/*修改文本表单内容**/
	public function ajax_form() {
	   
	   $data['kid'] = $this->_post('id','intval');
	   $kename = $this->_post('kename','trim');
	   $data['kecontent'] = $this->_post('kecontent','trim');
	   $kyword = $this->_post('kyword','trim');
	   
	   if(!empty($kename)) {
	   	    $data['kename'] = $kename;
	   }
	   if(!empty($kyword)) {
	   	
	   	   $data['kyword'] = $kyword;
	   }
	   $res = M('keyword')->save($data); 
	   if($res!==false) {
		    $data = array('res' => 'succ','msg' => '修改成功');   
	   }else{
		    $data = array('res' => 'error','msg' => '修改失败');   
	   }
	   
	   echo json_encode($data);
	   exit;
	   
	}
	
	
	/************
	 * 
	 消息自动回复
	 ***************/
	public  function addmess()
	{
		
$op = $_GET['op'];
if($op=='add'){
	//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE ismess=1');
	$keyinfo=M('keyword')->where('ismess=1')->select();
	$keyword = array();
	//$keyword['kename']    = trim($_POST['kename']);
	//$keyword['kyword']    = trim($_POST['keword']);
	$keyword['type']      = intval($_POST['ketype']);
		
	     $linkinfo=$_POST['linkinfo'];
    	$titles=$_POST['titles'];
    	$imageinfo=$_POST['imageinfo'];
	if(empty($keyinfo)){
		if($keyword['type']==1){
		   $keyword['kecontent'] = trim($_POST['kecontent']);
		}else{
		   $keyword['linkinfo']  = serialize($linkinfo);
		   $keyword['titles']    = serialize($titles);
		   $keyword['imageinfo'] = serialize($imageinfo);
		}
		$keyword['ismess'] = 1;
		M('keyword')->data($keyword)->add();
	//	$db->inserttable("keyword",$keyword);
	}else{
		if($keyword['type']==1){
			$keyword['linkinfo']  = NULL;
			$keyword['titles']    = NULL;
			$keyword['imageinfo'] = NULL;
			$keyword['kecontent'] = trim($_POST['kecontent']);
	    }else{
			$keyword['kecontent'] = NULL;
			$keyword['linkinfo']  = serialize($linkinfo);
			$keyword['titles']    = serialize($titles);
			$keyword['imageinfo'] = serialize($imageinfo);
	    }
		$where = array('ismess' => 1);
		M('keyword')->data($keyword)->where($where)->save();
	//	$db->updatetable("keyword",$keyword,$where);
	}
}
  $this->display();
	}
	
	public function showmess()
	{
		//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE ismess=1');
		$keyinfo=M('keyword')->where('ismess=1')->select();
	if(!empty($keyinfo)){
		foreach($keyinfo as $k=>$v) {
			$titles                   = unserialize($v['titles']);
			$imageinfo                = unserialize($v['imageinfo']);
			$linkinfo                 = unserialize($v['linkinfo']);
			$keyinfo[$k]['titles']    = $titles;
			$keyinfo[$k]['imageinfo'] = $imageinfo;
			$keyinfo[$k]['linkinfo']  = $linkinfo;
		}
		echo json_encode($keyinfo);
	}else{
		echo json_encode($keyinfo);
	}
	}
	
	
	public function addfollow(){
		echo $op = $_GET['op'];
		if($op=='add'){
			$keyinfo=M('keyword')->where('isfollow=1')->select();
			
			$keyword = array();
		//	$keyword['kename']    = trim($_POST['kename']);
		//	$keyword['kyword']    = trim($_POST['keword']);
			$keyword['type']      = intval($_POST['ketype']);
			$linkinfo=$_POST['linkinfo'];
			$titles=$_POST['titles'];
			$imageinfo=$_POST['imageinfo'];
			if(empty($keyinfo)){
				if($keyword['type']==1){
					$keyword['kecontent'] = trim($_POST['kecontent']);
				}else{
					$keyword['linkinfo']  = serialize($linkinfo);
					$keyword['titles']    = serialize($titles);
					$keyword['imageinfo'] = serialize($imageinfo);
				}
				$keyword['isfollow'] = 1;
				
					M('keyword')->data($keyword)->add();
			
			}else{
				if($keyword['type']==1){
					$keyword['linkinfo']  = NULL;
					$keyword['titles']    = NULL;
					$keyword['imageinfo'] = NULL;
					$keyword['kecontent'] = trim($_POST['kecontent']);
				}else{
					$keyword['kecontent'] = NULL;
					$keyword['linkinfo']  = serialize($linkinfo);
					$keyword['titles']    = serialize($titles);
					$keyword['imageinfo'] = serialize($imageinfo);
				}
				$where = array('isfollow' => 1);
				M('keyword')->data($keyword)->where($where)->save();
				
			}
		}
	 $this->display();
		
	}
	
	public function showfollow()
	{
		//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE isfollow=1');
		$keyinfo=M('keyword')->where('isfollow=1')->select();
	if(!empty($keyinfo)){
		foreach($keyinfo as $k=>$v) {
			$titles                   = unserialize($v['titles']);
			$imageinfo                = unserialize($v['imageinfo']);
			$linkinfo                 = unserialize($v['linkinfo']);
			
			$keyinfo[$k]['titles']    = $titles;
			$keyinfo[$k]['imageinfo'] = $imageinfo;
			$keyinfo[$k]['linkinfo']  = $linkinfo;
		}
		echo json_encode($keyinfo);
	}else{
		echo json_encode($keyinfo);
	}
	}
	
	
	public  function delimg()
	{
		if(isset($_POST['iid']))
		{
		    $url=M('images')->field('imgurl')->find($_POST['iid']);
			M('images')->delete($_POST['iid']);
			unlink($url['imgurl']);
		}
	}
	
	/************
	 * 
	 课程体系   ismess=11
	 ***************/
	public  function kctx()
	{
		$op = $_GET['op'];
		$keyinfo=M('keyword')->where('ismess=11')->select();
		$this->assign('info', $keyinfo[0]);
		if($op=='add'){
			
			$_POST = str_replace("&amp;", "&", $_POST);
			$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
			//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE ismess=1');
			//$keyinfo=M('keyword')->where('ismess=11')->select();
			$keyword = array();
			//$keyword['kename']    = trim($_POST['kename']);
			//$keyword['kyword']    = trim($_POST['keword']);
			$keyword['type']      = intval($_POST['ketype']);
				
			if(empty($keyinfo)){
				if($keyword['type']==1){
				   $keyword['kecontent'] = trim($_POST['kecontent']);
				   
				   $keyword['ismess'] = 11;
				   M('keyword')->data($keyword)->add();
				}else{
				   $titles=explode(",",$_POST['titles']);
    			   $imageinfo=explode(",",$_POST['imageinfo']);
				   $keyword['titles']    = serialize($titles);
				   $keyword['imageinfo'] = serialize($imageinfo);
				   
				   $keyword['content1']  = trim($_POST['content1']);
				   $keyword['content2']    = trim($_POST['content2']);
				   $keyword['content3']    = trim($_POST['content3']);
				   $keyword['content4']    = trim($_POST['content4']);
				   $keyword['content5']    = trim($_POST['content5']);
				   $keyword['content6']    = trim($_POST['content6']);
				   
				   $keyword['ismess'] =11;
				   
				   $kid = M('keyword')->data($keyword)->add();
				   
				   $where = array('kid' => $kid);
				   
				   $linkinfo = str_replace("AAA", $kid, $_POST['linkinfo']);
				   $linkinfos=explode(",",$linkinfo);
		           $keywords['linkinfo']  = serialize($linkinfos);
		           M('keyword')->data($keywords)->where($where)->save();
		           echo "<script>window.location.href='index.php?g=admin&m=keyword&a=kctx';</script>";
				}
				
			//	$db->inserttable("keyword",$keyword);
			}else{
				$_POST = str_replace("&amp;", "&", $_POST);
				$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
				if($keyword['type']==1){
					$keyword['linkinfo']  = NULL;
					$keyword['titles']    = NULL;
					$keyword['imageinfo'] = NULL;
					$keyword['kecontent'] = trim($_POST['kecontent']);
			    }else{
					$keyword['kecontent'] = NULL;
					
					$titles=explode(",",$_POST['titles']);
    			    $imageinfo=explode(",",$_POST['imageinfo']);
    			    
				    $keyword['titles']    = serialize($titles);
				    $keyword['imageinfo'] = serialize($imageinfo);
				    
				    $linkinfo = str_replace("AAA", $keyinfo[0]['kid'], $_POST['linkinfo']);
				    $linkinfos=explode(",",$linkinfo);
				    
		            $keyword['linkinfo']  = serialize($linkinfos);
					$keyword['content1']    = trim($_POST['content1']);
				   	$keyword['content2']    = trim($_POST['content2']);
				   	$keyword['content3']    = trim($_POST['content3']);
				   	$keyword['content4']    = trim($_POST['content4']);
				   	$keyword['content5']    = trim($_POST['content5']);
				   	$keyword['content6']    = trim($_POST['content6']);
			    }
				$where = array('ismess' => 11);
				M('keyword')->data($keyword)->where($where)->save();
				echo "<script>window.location.href='index.php?g=admin&m=keyword&a=kctx';</script>";
			//	$db->updatetable("keyword",$keyword,$where);
			}
		}
		
  		$this->display();
	}
	// 课程体系数据展示
	public function showkctx()
		{
			//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE ismess=1');
			$keyinfo=M('keyword')->where('ismess=11')->select();
		if(!empty($keyinfo)){
			foreach($keyinfo as $k=>$v) {
				$titles                   = unserialize($v['titles']);
				$imageinfo                = unserialize($v['imageinfo']);
				$linkinfo                 = unserialize($v['linkinfo']);
				$keyinfo[$k]['titles']    = $titles;
				$keyinfo[$k]['imageinfo'] = $imageinfo;
				$keyinfo[$k]['linkinfo']  = $linkinfo;
			}
			echo json_encode($keyinfo);
		}else{
			echo json_encode($keyinfo);
		}
	}
/************
	 * 
	 精彩视频   ismess=12
	 ***************/
	public  function jcsp()
	{
		
		$op = $_GET['op'];
		$keyinfo=M('keyword')->where('ismess=12')->select();
		$this->assign('info', $keyinfo[0]);
		if($op=='add'){
			
			$_POST = str_replace("&amp;", "&", $_POST);
			$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
			//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE ismess=1');
			//$keyinfo=M('keyword')->where('ismess=11')->select();
			$keyword = array();
			//$keyword['kename']    = trim($_POST['kename']);
			//$keyword['kyword']    = trim($_POST['keword']);
			$keyword['type']      = intval($_POST['ketype']);
				
			if(empty($keyinfo)){
				if($keyword['type']==1){
				   $keyword['kecontent'] = trim($_POST['kecontent']);
				   
				   $keyword['ismess'] = 12;
				   M('keyword')->data($keyword)->add();
				}else{
				   $titles=explode(",",$_POST['titles']);
    			   $imageinfo=explode(",",$_POST['imageinfo']);
				   $keyword['titles']    = serialize($titles);
				   $keyword['imageinfo'] = serialize($imageinfo);
				   
				   $keyword['content1']  = trim($_POST['content1']);
				   $keyword['content2']    = trim($_POST['content2']);
				   $keyword['content3']    = trim($_POST['content3']);
				   $keyword['content4']    = trim($_POST['content4']);
				   $keyword['content5']    = trim($_POST['content5']);
				   $keyword['content6']    = trim($_POST['content6']);
				   
				   $keyword['ismess'] =12;
				   
				   $kid = M('keyword')->data($keyword)->add();
				   
				   $where = array('kid' => $kid);
				   
				   $linkinfo = str_replace("AAA", $kid, $_POST['linkinfo']);
				   $linkinfos=explode(",",$linkinfo);
		           $keywords['linkinfo']  = serialize($linkinfos);
		           M('keyword')->data($keywords)->where($where)->save();
		           echo "<script>window.location.href='index.php?g=admin&m=keyword&a=jcsp';</script>";
				}
				
			//	$db->inserttable("keyword",$keyword);
			}else{
				$_POST = str_replace("&amp;", "&", $_POST);
				$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
				if($keyword['type']==1){
					$keyword['linkinfo']  = NULL;
					$keyword['titles']    = NULL;
					$keyword['imageinfo'] = NULL;
					$keyword['kecontent'] = trim($_POST['kecontent']);
			    }else{
					$keyword['kecontent'] = NULL;
					
					$titles=explode(",",$_POST['titles']);
    			    $imageinfo=explode(",",$_POST['imageinfo']);
    			    
				    $keyword['titles']    = serialize($titles);
				    $keyword['imageinfo'] = serialize($imageinfo);
				    
				    $linkinfo = str_replace("AAA", $keyinfo[0]['kid'], $_POST['linkinfo']);
				    $linkinfos=explode(",",$linkinfo);
				    
		            $keyword['linkinfo']  = serialize($linkinfos);
					$keyword['content1']    = trim($_POST['content1']);
				   	$keyword['content2']    = trim($_POST['content2']);
				   	$keyword['content3']    = trim($_POST['content3']);
				   	$keyword['content4']    = trim($_POST['content4']);
				   	$keyword['content5']    = trim($_POST['content5']);
				   	$keyword['content6']    = trim($_POST['content6']);
			    }
				$where = array('ismess' => 12);
				M('keyword')->data($keyword)->where($where)->save();
				echo "<script>window.location.href='index.php?g=admin&m=keyword&a=jcsp';</script>";
			//	$db->updatetable("keyword",$keyword,$where);
			}
		}
		
  		$this->display();
	}
	// 精彩视频数据展示
	public function showjcsp()
		{
			//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE ismess=1');
			$keyinfo=M('keyword')->where('ismess=12')->select();
		if(!empty($keyinfo)){
			foreach($keyinfo as $k=>$v) {
				$titles                   = unserialize($v['titles']);
				$imageinfo                = unserialize($v['imageinfo']);
				$linkinfo                 = unserialize($v['linkinfo']);
				$keyinfo[$k]['titles']    = $titles;
				$keyinfo[$k]['imageinfo'] = $imageinfo;
				$keyinfo[$k]['linkinfo']  = $linkinfo;
			}
			echo json_encode($keyinfo);
		}else{
			echo json_encode($keyinfo);
		}
	}
/************
	 * 
	 品牌介绍   ismess=13
	 ***************/
	public  function ppjs()
	{
		
		$op = $_GET['op'];
		$keyinfo=M('keyword')->where('ismess=13')->select();
		$this->assign('info', $keyinfo[0]);
		if($op=='add'){
			
			$_POST = str_replace("&amp;", "&", $_POST);
			$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
			//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE ismess=1');
			//$keyinfo=M('keyword')->where('ismess=11')->select();
			$keyword = array();
			//$keyword['kename']    = trim($_POST['kename']);
			//$keyword['kyword']    = trim($_POST['keword']);
			$keyword['type']      = intval($_POST['ketype']);
				
			if(empty($keyinfo)){
				if($keyword['type']==1){
				   $keyword['kecontent'] = trim($_POST['kecontent']);
				   
				   $keyword['ismess'] = 13;
				   M('keyword')->data($keyword)->add();
				}else{
				   $titles=explode(",",$_POST['titles']);
    			   $imageinfo=explode(",",$_POST['imageinfo']);
				   $keyword['titles']    = serialize($titles);
				   $keyword['imageinfo'] = serialize($imageinfo);
				   
				   $keyword['content1']  = trim($_POST['content1']);
				   $keyword['content2']    = trim($_POST['content2']);
				   $keyword['content3']    = trim($_POST['content3']);
				   $keyword['content4']    = trim($_POST['content4']);
				   $keyword['content5']    = trim($_POST['content5']);
				   $keyword['content6']    = trim($_POST['content6']);
				   
				   $keyword['ismess'] =13;
				   
				   $kid = M('keyword')->data($keyword)->add();
				   
				   $where = array('kid' => $kid);
				   
				   $linkinfo = str_replace("AAA", $kid, $_POST['linkinfo']);
				   $linkinfos=explode(",",$linkinfo);
		           $keywords['linkinfo']  = serialize($linkinfos);
		           M('keyword')->data($keywords)->where($where)->save();
		           echo "<script>window.location.href='index.php?g=admin&m=keyword&a=ppjs';</script>";
				}
				
			//	$db->inserttable("keyword",$keyword);
			}else{
				$_POST = str_replace("&amp;", "&", $_POST);
				$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
				if($keyword['type']==1){
					$keyword['linkinfo']  = NULL;
					$keyword['titles']    = NULL;
					$keyword['imageinfo'] = NULL;
					$keyword['kecontent'] = trim($_POST['kecontent']);
			    }else{
					$keyword['kecontent'] = NULL;
					
					$titles=explode(",",$_POST['titles']);
    			    $imageinfo=explode(",",$_POST['imageinfo']);
    			    
				    $keyword['titles']    = serialize($titles);
				    $keyword['imageinfo'] = serialize($imageinfo);
				    
				    $linkinfo = str_replace("AAA", $keyinfo[0]['kid'], $_POST['linkinfo']);
				    $linkinfos=explode(",",$linkinfo);
				    
		            $keyword['linkinfo']  = serialize($linkinfos);
					$keyword['content1']    = trim($_POST['content1']);
				   	$keyword['content2']    = trim($_POST['content2']);
				   	$keyword['content3']    = trim($_POST['content3']);
				   	$keyword['content4']    = trim($_POST['content4']);
				   	$keyword['content5']    = trim($_POST['content5']);
				   	$keyword['content6']    = trim($_POST['content6']);
			    }
				$where = array('ismess' => 13);
				M('keyword')->data($keyword)->where($where)->save();
				echo "<script>window.location.href='index.php?g=admin&m=keyword&a=ppjs';</script>";
			//	$db->updatetable("keyword",$keyword,$where);
			}
		}
		
  		$this->display();
	}
	// 品牌介绍数据展示
	public function showppjs()
		{
			$keyinfo=M('keyword')->where('ismess=13')->select();
		if(!empty($keyinfo)){
			foreach($keyinfo as $k=>$v) {
				$titles                   = unserialize($v['titles']);
				$imageinfo                = unserialize($v['imageinfo']);
				$linkinfo                 = unserialize($v['linkinfo']);
				$keyinfo[$k]['titles']    = $titles;
				$keyinfo[$k]['imageinfo'] = $imageinfo;
				$keyinfo[$k]['linkinfo']  = $linkinfo;
			}
			echo json_encode($keyinfo);
		}else{
			echo json_encode($keyinfo);
		}
	}
/************
	 * 
	 活动预告   ismess=14
	 ***************/
	public  function hdyg()
	{
		
		$op = $_GET['op'];
		$keyinfo=M('keyword')->where('ismess=14')->select();
		$this->assign('info', $keyinfo[0]);
		if($op=='add'){
			
			$_POST = str_replace("&amp;", "&", $_POST);
			$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
			//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE ismess=1');
			//$keyinfo=M('keyword')->where('ismess=11')->select();
			$keyword = array();
			//$keyword['kename']    = trim($_POST['kename']);
			//$keyword['kyword']    = trim($_POST['keword']);
			$keyword['type']      = intval($_POST['ketype']);
				
			if(empty($keyinfo)){
				if($keyword['type']==1){
				   $keyword['kecontent'] = trim($_POST['kecontent']);
				   
				   $keyword['ismess'] = 14;
				   M('keyword')->data($keyword)->add();
				}else{
				   $titles=explode(",",$_POST['titles']);
    			   $imageinfo=explode(",",$_POST['imageinfo']);
				   $keyword['titles']    = serialize($titles);
				   $keyword['imageinfo'] = serialize($imageinfo);
				   
				   $keyword['content1']  = trim($_POST['content1']);
				   $keyword['content2']    = trim($_POST['content2']);
				   $keyword['content3']    = trim($_POST['content3']);
				   $keyword['content4']    = trim($_POST['content4']);
				   $keyword['content5']    = trim($_POST['content5']);
				   $keyword['content6']    = trim($_POST['content6']);
				   
				   $keyword['ismess'] =14;
				   
				   $kid = M('keyword')->data($keyword)->add();
				   
				   $where = array('kid' => $kid);
				   
				   $linkinfo = str_replace("AAA", $keyinfo[0]['kid'], $_POST['linkinfo']);
				   $linkinfos=explode(",",$linkinfo);
		           $keywords['linkinfo']  = serialize($linkinfos);
		           M('keyword')->data($keywords)->where($where)->save();
		           echo "<script>window.location.href='index.php?g=admin&m=keyword&a=hdyg';</script>";
				}
				
			//	$db->inserttable("keyword",$keyword);
			}else{
				$_POST = str_replace("&amp;", "&", $_POST);
				$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
				if($keyword['type']==1){
					$keyword['linkinfo']  = NULL;
					$keyword['titles']    = NULL;
					$keyword['imageinfo'] = NULL;
					$keyword['kecontent'] = trim($_POST['kecontent']);
			    }else{
					$keyword['kecontent'] = NULL;
					
					$titles=explode(",",$_POST['titles']);
    			    $imageinfo=explode(",",$_POST['imageinfo']);
    			    
				    $keyword['titles']    = serialize($titles);
				    $keyword['imageinfo'] = serialize($imageinfo);
				    
				    $linkinfo = str_replace("AAA", $kid, $_POST['linkinfo']);
				    $linkinfos=explode(",",$linkinfo);
				    
		            $keyword['linkinfo']  = serialize($linkinfos);
					$keyword['content1']    = trim($_POST['content1']);
				   	$keyword['content2']    = trim($_POST['content2']);
				   	$keyword['content3']    = trim($_POST['content3']);
				   	$keyword['content4']    = trim($_POST['content4']);
				   	$keyword['content5']    = trim($_POST['content5']);
				   	$keyword['content6']    = trim($_POST['content6']);
			    }
				$where = array('ismess' => 14);
				M('keyword')->data($keyword)->where($where)->save();
				echo "<script>window.location.href='index.php?g=admin&m=keyword&a=hdyg';</script>";
			//	$db->updatetable("keyword",$keyword,$where);
			}
		}
		
  		$this->display();
	}
	// 活动预告数据展示
	public function showhdyg()
		{
			$keyinfo=M('keyword')->where('ismess=14')->select();
		if(!empty($keyinfo)){
			foreach($keyinfo as $k=>$v) {
				$titles                   = unserialize($v['titles']);
				$imageinfo                = unserialize($v['imageinfo']);
				$linkinfo                 = unserialize($v['linkinfo']);
				$keyinfo[$k]['titles']    = $titles;
				$keyinfo[$k]['imageinfo'] = $imageinfo;
				$keyinfo[$k]['linkinfo']  = $linkinfo;
			}
			echo json_encode($keyinfo);
		}else{
			echo json_encode($keyinfo);
		}
	}
	
/************
	 * 
	 活动回顾   ismess=15
	 ***************/
	public  function hdhg()
	{
		
		$op = $_GET['op'];
		$keyinfo=M('keyword')->where('ismess=15')->select();
		$this->assign('info', $keyinfo[0]);
		if($op=='add'){
			
			$_POST = str_replace("&amp;", "&", $_POST);
			$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
			//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE ismess=1');
			//$keyinfo=M('keyword')->where('ismess=11')->select();
			$keyword = array();
			//$keyword['kename']    = trim($_POST['kename']);
			//$keyword['kyword']    = trim($_POST['keword']);
			$keyword['type']      = intval($_POST['ketype']);
				
			if(empty($keyinfo)){
				if($keyword['type']==1){
				   $keyword['kecontent'] = trim($_POST['kecontent']);
				   
				   $keyword['ismess'] = 15;
				   M('keyword')->data($keyword)->add();
				}else{
				   $titles=explode(",",$_POST['titles']);
    			   $imageinfo=explode(",",$_POST['imageinfo']);
				   $keyword['titles']    = serialize($titles);
				   $keyword['imageinfo'] = serialize($imageinfo);
				   
				   $keyword['content1']  = trim($_POST['content1']);
				   $keyword['content2']    = trim($_POST['content2']);
				   $keyword['content3']    = trim($_POST['content3']);
				   $keyword['content4']    = trim($_POST['content4']);
				   $keyword['content5']    = trim($_POST['content5']);
				   $keyword['content6']    = trim($_POST['content6']);
				   
				   $keyword['ismess'] =15;
				   
				   $kid = M('keyword')->data($keyword)->add();
				   
				   $where = array('kid' => $kid);
				   
				   $linkinfo = str_replace("AAA", $kid, $_POST['linkinfo']);
				   $linkinfos=explode(",",$linkinfo);
		           $keywords['linkinfo']  = serialize($linkinfos);
		           M('keyword')->data($keywords)->where($where)->save();
		           echo "<script>window.location.href='index.php?g=admin&m=keyword&a=hdhg';</script>";
				}
				
			//	$db->inserttable("keyword",$keyword);
			}else{
				$_POST = str_replace("&amp;", "&", $_POST);
				$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
				if($keyword['type']==1){
					$keyword['linkinfo']  = NULL;
					$keyword['titles']    = NULL;
					$keyword['imageinfo'] = NULL;
					$keyword['kecontent'] = trim($_POST['kecontent']);
			    }else{
					$keyword['kecontent'] = NULL;
					
					$titles=explode(",",$_POST['titles']);
    			    $imageinfo=explode(",",$_POST['imageinfo']);
    			    
				    $keyword['titles']    = serialize($titles);
				    $keyword['imageinfo'] = serialize($imageinfo);
				    
				    $linkinfo = str_replace("AAA", $keyinfo[0]['kid'], $_POST['linkinfo']);
				    $linkinfos=explode(",",$linkinfo);
				    
		            $keyword['linkinfo']  = serialize($linkinfos);
					$keyword['content1']    = trim($_POST['content1']);
				   	$keyword['content2']    = trim($_POST['content2']);
				   	$keyword['content3']    = trim($_POST['content3']);
				   	$keyword['content4']    = trim($_POST['content4']);
				   	$keyword['content5']    = trim($_POST['content5']);
				   	$keyword['content6']    = trim($_POST['content6']);
			    }
				$where = array('ismess' => 15);
				M('keyword')->data($keyword)->where($where)->save();
				echo "<script>window.location.href='index.php?g=admin&m=keyword&a=hdhg';</script>";
			//	$db->updatetable("keyword",$keyword,$where);
			}
		}
		
  		$this->display();
	}
	// 活动回顾数据展示
	public function showhdhg()
		{
			$keyinfo=M('keyword')->where('ismess=15')->select();
		if(!empty($keyinfo)){
			foreach($keyinfo as $k=>$v) {
				$titles                   = unserialize($v['titles']);
				$imageinfo                = unserialize($v['imageinfo']);
				$linkinfo                 = unserialize($v['linkinfo']);
				$keyinfo[$k]['titles']    = $titles;
				$keyinfo[$k]['imageinfo'] = $imageinfo;
				$keyinfo[$k]['linkinfo']  = $linkinfo;
			}
			echo json_encode($keyinfo);
		}else{
			echo json_encode($keyinfo);
		}
	}
	
/************
	 * 
	 联系我们   ismess=16
	 ***************/
	public  function lxwm()
	{
		
		$op = $_GET['op'];
		$keyinfo=M('keyword')->where('ismess=16')->select();
		$this->assign('info', $keyinfo[0]);
		if($op=='add'){
			
			$_POST = str_replace("&amp;", "&", $_POST);
			$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
			//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE ismess=1');
			//$keyinfo=M('keyword')->where('ismess=11')->select();
			$keyword = array();
			//$keyword['kename']    = trim($_POST['kename']);
			//$keyword['kyword']    = trim($_POST['keword']);
			$keyword['type']      = intval($_POST['ketype']);
				
			if(empty($keyinfo)){
				if($keyword['type']==1){
				   $keyword['kecontent'] = trim($_POST['kecontent']);
				   
				   $keyword['ismess'] = 16;
				   M('keyword')->data($keyword)->add();
				}else{
				   $titles=explode(",",$_POST['titles']);
    			   $imageinfo=explode(",",$_POST['imageinfo']);
				   $keyword['titles']    = serialize($titles);
				   $keyword['imageinfo'] = serialize($imageinfo);
				   
				   $keyword['content1']  = trim($_POST['content1']);
				   $keyword['content2']    = trim($_POST['content2']);
				   $keyword['content3']    = trim($_POST['content3']);
				   $keyword['content4']    = trim($_POST['content4']);
				   $keyword['content5']    = trim($_POST['content5']);
				   $keyword['content6']    = trim($_POST['content6']);
				   
				   $keyword['ismess'] =16;
				   
				   $kid = M('keyword')->data($keyword)->add();
				   
				   $where = array('kid' => $kid);
				   
				   $linkinfo = str_replace("AAA", $kid, $_POST['linkinfo']);
				   $linkinfos=explode(",",$linkinfo);
		           $keywords['linkinfo']  = serialize($linkinfos);
		           M('keyword')->data($keywords)->where($where)->save();
		           echo "<script>window.location.href='index.php?g=admin&m=keyword&a=lxwm';</script>";
				}
				
			//	$db->inserttable("keyword",$keyword);
			}else{
				$_POST = str_replace("&amp;", "&", $_POST);
				$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
				if($keyword['type']==1){
					$keyword['linkinfo']  = NULL;
					$keyword['titles']    = NULL;
					$keyword['imageinfo'] = NULL;
					$keyword['kecontent'] = trim($_POST['kecontent']);
			    }else{
					$keyword['kecontent'] = NULL;
					
					$titles=explode(",",$_POST['titles']);
    			    $imageinfo=explode(",",$_POST['imageinfo']);
    			    
				    $keyword['titles']    = serialize($titles);
				    $keyword['imageinfo'] = serialize($imageinfo);
				    
				    $linkinfo = str_replace("AAA", $keyinfo[0]['kid'], $_POST['linkinfo']);
				    $linkinfos=explode(",",$linkinfo);
				    
		            $keyword['linkinfo']  = serialize($linkinfos);
					$keyword['content1']    = trim($_POST['content1']);
				   	$keyword['content2']    = trim($_POST['content2']);
				   	$keyword['content3']    = trim($_POST['content3']);
				   	$keyword['content4']    = trim($_POST['content4']);
				   	$keyword['content5']    = trim($_POST['content5']);
				   	$keyword['content6']    = trim($_POST['content6']);
			    }
				$where = array('ismess' => 16);
				M('keyword')->data($keyword)->where($where)->save();
				echo "<script>window.location.href='index.php?g=admin&m=keyword&a=lxwm';</script>";
			//	$db->updatetable("keyword",$keyword,$where);
			}
		}
		
  		$this->display();
	}
	// 联系我们数据展示
	public function showlxwm()
		{
			$keyinfo=M('keyword')->where('ismess=16')->select();
		if(!empty($keyinfo)){
			foreach($keyinfo as $k=>$v) {
				$titles                   = unserialize($v['titles']);
				$imageinfo                = unserialize($v['imageinfo']);
				$linkinfo                 = unserialize($v['linkinfo']);
				$keyinfo[$k]['titles']    = $titles;
				$keyinfo[$k]['imageinfo'] = $imageinfo;
				$keyinfo[$k]['linkinfo']  = $linkinfo;
			}
			echo json_encode($keyinfo);
		}else{
			echo json_encode($keyinfo);
		}
	}
	
/************
	 * 
	 新闻中心   ismess=17
	 ***************/
	public  function xwzx()
	{
		
		$op = $_GET['op'];
		$keyinfo=M('keyword')->where('ismess=17')->select();
		$this->assign('info', $keyinfo[0]);
		if($op=='add'){
			
			$_POST = str_replace("&amp;", "&", $_POST);
			$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
			//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE ismess=1');
			//$keyinfo=M('keyword')->where('ismess=11')->select();
			$keyword = array();
			//$keyword['kename']    = trim($_POST['kename']);
			//$keyword['kyword']    = trim($_POST['keword']);
			$keyword['type']      = intval($_POST['ketype']);
				
			if(empty($keyinfo)){
				if($keyword['type']==1){
				   $keyword['kecontent'] = trim($_POST['kecontent']);
				   
				   $keyword['ismess'] = 17;
				   M('keyword')->data($keyword)->add();
				}else{
				   $titles=explode(",",$_POST['titles']);
    			   $imageinfo=explode(",",$_POST['imageinfo']);
				   $keyword['titles']    = serialize($titles);
				   $keyword['imageinfo'] = serialize($imageinfo);
				   
				   $keyword['content1']  = trim($_POST['content1']);
				   $keyword['content2']    = trim($_POST['content2']);
				   $keyword['content3']    = trim($_POST['content3']);
				   $keyword['content4']    = trim($_POST['content4']);
				   $keyword['content5']    = trim($_POST['content5']);
				   $keyword['content6']    = trim($_POST['content6']);
				   
				   $keyword['ismess'] =17;
				   
				   $kid = M('keyword')->data($keyword)->add();
				   
				   $where = array('kid' => $kid);
				   
				   $linkinfo = str_replace("AAA", $kid, $_POST['linkinfo']);
				   $linkinfos=explode(",",$linkinfo);
		           $keywords['linkinfo']  = serialize($linkinfos);
		           M('keyword')->data($keywords)->where($where)->save();
		           echo "<script>window.location.href='index.php?g=admin&m=keyword&a=xwzx';</script>";
				}
				
			//	$db->inserttable("keyword",$keyword);
			}else{
				$_POST = str_replace("&amp;", "&", $_POST);
				$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
				if($keyword['type']==1){
					$keyword['linkinfo']  = NULL;
					$keyword['titles']    = NULL;
					$keyword['imageinfo'] = NULL;
					$keyword['kecontent'] = trim($_POST['kecontent']);
			    }else{
					$keyword['kecontent'] = NULL;
					
					$titles=explode(",",$_POST['titles']);
    			    $imageinfo=explode(",",$_POST['imageinfo']);
    			    
				    $keyword['titles']    = serialize($titles);
				    $keyword['imageinfo'] = serialize($imageinfo);
				    
				    $linkinfo = str_replace("AAA", $keyinfo[0]['kid'], $_POST['linkinfo']);
				    $linkinfos=explode(",",$linkinfo);
				    
		            $keyword['linkinfo']  = serialize($linkinfos);
					$keyword['content1']    = trim($_POST['content1']);
				   	$keyword['content2']    = trim($_POST['content2']);
				   	$keyword['content3']    = trim($_POST['content3']);
				   	$keyword['content4']    = trim($_POST['content4']);
				   	$keyword['content5']    = trim($_POST['content5']);
				   	$keyword['content6']    = trim($_POST['content6']);
			    }
				$where = array('ismess' => 17);
				M('keyword')->data($keyword)->where($where)->save();
				echo "<script>window.location.href='index.php?g=admin&m=keyword&a=xwzx';</script>";
			//	$db->updatetable("keyword",$keyword,$where);
			}
		}
		
  		$this->display();
	}
	// 新闻中心数据展示
	public function showxwzx()
		{
			$keyinfo=M('keyword')->where('ismess=17')->select();
		if(!empty($keyinfo)){
			foreach($keyinfo as $k=>$v) {
				$titles                   = unserialize($v['titles']);
				$imageinfo                = unserialize($v['imageinfo']);
				$linkinfo                 = unserialize($v['linkinfo']);
				$keyinfo[$k]['titles']    = $titles;
				$keyinfo[$k]['imageinfo'] = $imageinfo;
				$keyinfo[$k]['linkinfo']  = $linkinfo;
			}
			echo json_encode($keyinfo);
		}else{
			echo json_encode($keyinfo);
		}
	}
	
/************
	 * 
	 中心活动   ismess=18
	 ***************/
	public  function zxhd()
	{
		
		$op = $_GET['op'];
		$keyinfo=M('keyword')->where('ismess=18')->select();
		$this->assign('info', $keyinfo[0]);
		if($op=='add'){
			
			$_POST = str_replace("&amp;", "&", $_POST);
			$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
			//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE ismess=1');
			//$keyinfo=M('keyword')->where('ismess=11')->select();
			$keyword = array();
			//$keyword['kename']    = trim($_POST['kename']);
			//$keyword['kyword']    = trim($_POST['keword']);
			$keyword['type']      = intval($_POST['ketype']);
				
			if(empty($keyinfo)){
				if($keyword['type']==1){
				   $keyword['kecontent'] = trim($_POST['kecontent']);
				   
				   $keyword['ismess'] = 18;
				   M('keyword')->data($keyword)->add();
				}else{
				   $titles=explode(",",$_POST['titles']);
    			   $imageinfo=explode(",",$_POST['imageinfo']);
				   $keyword['titles']    = serialize($titles);
				   $keyword['imageinfo'] = serialize($imageinfo);
				   
				   $keyword['content1']  = trim($_POST['content1']);
				   $keyword['content2']    = trim($_POST['content2']);
				   $keyword['content3']    = trim($_POST['content3']);
				   $keyword['content4']    = trim($_POST['content4']);
				   $keyword['content5']    = trim($_POST['content5']);
				   $keyword['content6']    = trim($_POST['content6']);
				   
				   $keyword['ismess'] =18;
				   
				   $kid = M('keyword')->data($keyword)->add();
				   
				   $where = array('kid' => $kid);
				   
				   $linkinfo = str_replace("AAA", $kid, $_POST['linkinfo']);
				   $linkinfos=explode(",",$linkinfo);
		           $keywords['linkinfo']  = serialize($linkinfos);
		           M('keyword')->data($keywords)->where($where)->save();
		           echo "<script>window.location.href='index.php?g=admin&m=keyword&a=zxhd';</script>";
				}
				
			//	$db->inserttable("keyword",$keyword);
			}else{
				$_POST = str_replace("&amp;", "&", $_POST);
				$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
				if($keyword['type']==1){
					$keyword['linkinfo']  = NULL;
					$keyword['titles']    = NULL;
					$keyword['imageinfo'] = NULL;
					$keyword['kecontent'] = trim($_POST['kecontent']);
			    }else{
					$keyword['kecontent'] = NULL;
					
					$titles=explode(",",$_POST['titles']);
    			    $imageinfo=explode(",",$_POST['imageinfo']);
    			    
				    $keyword['titles']    = serialize($titles);
				    $keyword['imageinfo'] = serialize($imageinfo);
				    
				    $linkinfo = str_replace("AAA", $keyinfo[0]['kid'], $_POST['linkinfo']);
				    $linkinfos=explode(",",$linkinfo);
				    
		            $keyword['linkinfo']  = serialize($linkinfos);
					$keyword['content1']    = trim($_POST['content1']);
				   	$keyword['content2']    = trim($_POST['content2']);
				   	$keyword['content3']    = trim($_POST['content3']);
				   	$keyword['content4']    = trim($_POST['content4']);
				   	$keyword['content5']    = trim($_POST['content5']);
				   	$keyword['content6']    = trim($_POST['content6']);
			    }
				$where = array('ismess' => 18);
				M('keyword')->data($keyword)->where($where)->save();
				echo "<script>window.location.href='index.php?g=admin&m=keyword&a=zxhd';</script>";
			//	$db->updatetable("keyword",$keyword,$where);
			}
		}
		
  		$this->display();
	}
	// 中心活动数据展示
	public function showzxhd()
		{
			$keyinfo=M('keyword')->where('ismess=18')->select();
		if(!empty($keyinfo)){
			foreach($keyinfo as $k=>$v) {
				$titles                   = unserialize($v['titles']);
				$imageinfo                = unserialize($v['imageinfo']);
				$linkinfo                 = unserialize($v['linkinfo']);
				$keyinfo[$k]['titles']    = $titles;
				$keyinfo[$k]['imageinfo'] = $imageinfo;
				$keyinfo[$k]['linkinfo']  = $linkinfo;
			}
			echo json_encode($keyinfo);
		}else{
			echo json_encode($keyinfo);
		}
	}
	
	/************
	 * 
	预约试听   ismess=19
	 ***************/
	public  function yyst()
	{
		
		$op = $_GET['op'];
		$keyinfo=M('keyword')->where('ismess=19')->select();
		$this->assign('info', $keyinfo[0]);
		if($op=='add'){
			
			$_POST = str_replace("&amp;", "&", $_POST);
			$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
			//$keyinfo = $db->getAll('SELECT * FROM '.tname('keyword').' WHERE ismess=1');
			//$keyinfo=M('keyword')->where('ismess=11')->select();
			$keyword = array();
			//$keyword['kename']    = trim($_POST['kename']);
			//$keyword['kyword']    = trim($_POST['keword']);
			$keyword['type']      = intval($_POST['ketype']);
				
			if(empty($keyinfo)){
				if($keyword['type']==1){
				   $keyword['kecontent'] = trim($_POST['kecontent']);
				   
				   $keyword['ismess'] = 19;
				   M('keyword')->data($keyword)->add();
				}else{
				   $titles=explode(",",$_POST['titles']);
    			   $imageinfo=explode(",",$_POST['imageinfo']);
				   $keyword['titles']    = serialize($titles);
				   $keyword['imageinfo'] = serialize($imageinfo);
				   
				   $keyword['content1']  = trim($_POST['content1']);
				   $keyword['content2']    = trim($_POST['content2']);
				   $keyword['content3']    = trim($_POST['content3']);
				   $keyword['content4']    = trim($_POST['content4']);
				   $keyword['content5']    = trim($_POST['content5']);
				   $keyword['content6']    = trim($_POST['content6']);
				   
				   $keyword['ismess'] =19;
				   
				   $kid = M('keyword')->data($keyword)->add();
				   
				   $where = array('kid' => $kid);
				   
				   $linkinfo = str_replace("AAA", $kid, $_POST['linkinfo']);
				   $linkinfos=explode(",",$linkinfo);
		           $keywords['linkinfo']  = serialize($linkinfos);
		           M('keyword')->data($keywords)->where($where)->save();
		           echo "<script>window.location.href='index.php?g=admin&m=keyword&a=yyst';</script>";
				}
				
			//	$db->inserttable("keyword",$keyword);
			}else{
				$_POST = str_replace("&amp;", "&", $_POST);
				$_POST = str_replace('type="video/x-ms-asf-plugin"', "", $_POST);
				if($keyword['type']==1){
					$keyword['linkinfo']  = NULL;
					$keyword['titles']    = NULL;
					$keyword['imageinfo'] = NULL;
					$keyword['kecontent'] = trim($_POST['kecontent']);
			    }else{
					$keyword['kecontent'] = NULL;
					
					$titles=explode(",",$_POST['titles']);
    			    $imageinfo=explode(",",$_POST['imageinfo']);
    			    
				    $keyword['titles']    = serialize($titles);
				    $keyword['imageinfo'] = serialize($imageinfo);
				    
				    $linkinfo = str_replace("AAA", $keyinfo[0]['kid'], $_POST['linkinfo']);
				    $linkinfos=explode(",",$linkinfo);
				    
		            $keyword['linkinfo']  = serialize($linkinfos);
					$keyword['content1']    = trim($_POST['content1']);
				   	$keyword['content2']    = trim($_POST['content2']);
				   	$keyword['content3']    = trim($_POST['content3']);
				   	$keyword['content4']    = trim($_POST['content4']);
				   	$keyword['content5']    = trim($_POST['content5']);
				   	$keyword['content6']    = trim($_POST['content6']);
			    }
				$where = array('ismess' => 19);
				M('keyword')->data($keyword)->where($where)->save();
				echo "<script>window.location.href='index.php?g=admin&m=keyword&a=yyst';</script>";
			//	$db->updatetable("keyword",$keyword,$where);
			}
		}
		
  		$this->display();
	}
	// 预约试听数据展示
	public function showyyst()
		{
			$keyinfo=M('keyword')->where('ismess=19')->select();
		if(!empty($keyinfo)){
			foreach($keyinfo as $k=>$v) {
				$titles                   = unserialize($v['titles']);
				$imageinfo                = unserialize($v['imageinfo']);
				$linkinfo                 = unserialize($v['linkinfo']);
				$keyinfo[$k]['titles']    = $titles;
				$keyinfo[$k]['imageinfo'] = $imageinfo;
				$keyinfo[$k]['linkinfo']  = $linkinfo;
			}
			echo json_encode($keyinfo);
		}else{
			echo json_encode($keyinfo);
		}
	}
	
}