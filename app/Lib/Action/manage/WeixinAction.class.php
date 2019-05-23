<?php
// 本类由系统自动生成，仅供测试用途
header("content-type:text/html;charset=utf-8");
class WeixinAction extends Action {
	
	public $wxurl ; 
	
	function __construct()   
    {   
		$this->wxurl = $_SERVER['SERVER_NAME'].__ROOT__ ;
	}

	public function wenben($fromUsername, $toUsername, $time, $contentStr)
	{
		//////文本链接的处理/ ///
		$str=$contentStr;
	    $reg = '/\shref=[\'\"]([^\'"]*)[\'"]/i';
		preg_match_all($reg , $str , $out_ary);//正则：得到href的地址
		$src_ary = $out_ary[1];
       if(!empty($src_ary))//存在
      {
      	$comment=$src_ary[0];
      	if(stristr($comment,$_SERVER['SERVER_NAME']))
      	{
      		if(stristr($comment,"?"))
      		{
      			$links=$comment."&key=".$fromUsername;
      			$contentStr= str_replace($comment,$links,$str);
      		}else
      		{
      			$links=$comment."?key=".$fromUsername;
      			$contentStr= str_replace($comment,$links,$str);
      		}
      	}
      }
		
      	//////文本链接的处理 END////
      
		     $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
              		$msgType = "text";
                	//$contentStr =$contentStr;
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            	echo $resultStr;
	}
	public function tuwen($textTpl,$fromUsername, $toUsername, $time,$count)
	{
              		$msgType = "news";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType,$count);
            	echo $resultStr;
	}
	
	public function index(){
		import('Think.ORG.Weixin');// 导入微信类
		//traceHttp();
		$wechat = new Weixin();
		$wechat->valid();
		//$wechat->responseMsg();

		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (!empty($postStr)){
			$key_word=M('keyword');

			$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$fromUsername = trim($postObj->FromUserName);//发送方帐号（一个OpenID）
			$toUsername = $postObj->ToUserName;//开发者微信号 
			$keyword = trim($postObj->Content);//用户发来的信息
			$RX_TYPE = trim($postObj->MsgType);//类型
			$EventKey=trim($postObj->EventKey);//事件KEY值
			$Event=$postObj->Event;//事件类型
			$time = time();
			//getAllUserList();
			if($fromUsername!='')
			{
			 	$user= M('user')->field('id,wechatid')->where("wechatid='".$fromUsername."'")->find();
			 	if($user)
			 	{				
			 	}else 
			 	{
			 		$date=time();
        			$data['username']=$fromUsername;//用户名
        			$data['wechatid']=$fromUsername;//微信id
        			$data['reg_time']=$date;
        			$data['last_time']=$date;
        			$userid= M('user')->add($data);
			 	}
			}

			//  回复语音消息
			if($RX_TYPE=='voice')
			{
					$textTpl.= " 
					<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
				    <FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>1399714152</CreateTime>
					<MsgType><![CDATA[voice]]></MsgType>
					<Voice><MediaId><![CDATA[aZWTxOVO1OAM5wtrFxWlFHadr3HDbsl3j58YmcuaShOtXLnURyb_W3oJZhMOaILY]]></MediaId></Voice>
					</xml>";
                    $this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));
			}
			
//			//  回复转到客服
//				if($keyword == 1){
//					
//					$textTpl.= " 
//					<xml>
//					<ToUserName><![CDATA[%s]]></ToUserName>
//				    <FromUserName><![CDATA[%s]]></FromUserName>
//					<CreateTime>1399714152</CreateTime>
//					<MsgType><![CDATA[transfer_customer_service]]></MsgType>
//					</xml>";
//                    $this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));
//				}
			
			if($RX_TYPE=='event')
			{
			
			//**自定义点击事件**//
			if($Event=='CLICK')
			{

				if($EventKey!='')
				{
					 $where=array('keyword'=>$EventKey);
					 $custom_key= M('custom_menu')->where($where)->find();
					$key_list= $key_word->where("kyword='".$custom_key['keyword']."'")->find();
					//$key_list= $key_word->where("kyword='".$EventKey."'")->find();
					if(is_array($key_list))
					{
						if($key_list['type']==1)//文本
						{
							$this->wenben($fromUsername, $toUsername, $time,$key_list['kecontent']);
						}else //图文
						{
							$titles                   = unserialize($key_list['titles']);
							$imageinfo                = unserialize($key_list['imageinfo']);
							$linkinfo                 = unserialize($key_list['linkinfo']);

							$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount> 
                            <Articles>";
							for($i=0;$i<count($titles);$i++)
							{
								if(stristr($linkinfo[$i],$_SERVER['SERVER_NAME']))
								{
									if(stristr($linkinfo[$i],"?"))
									{
										$links=$linkinfo[$i]."&key=".$fromUsername;
									}else
									{
										$links=$linkinfo[$i]."?key=".$fromUsername;
									}
								}else{
									$links=$linkinfo[$i];
								}
								
								if(stristr($imageinfo[$i],$_SERVER['SERVER_NAME']))
								{
								$images=$imageinfo[$i];
								}else
								{
								$images="http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.$imageinfo[$i];
								}
								
								
								$textTpl.= " <item>
                           <Title><![CDATA[".$titles[$i]."]]></Title> 
                           <Description><![CDATA[".$titles[$i]."]]></Description>
                          <PicUrl><![CDATA[".$images."]]></PicUrl>
                           <Url><![CDATA[".$links."]]></Url>
                           </item>";
						 /*  $textTpl.= " <item>
                           <Title><![CDATA[".$titles[$i]."]]></Title> 
                           <Description><![CDATA[".$titles[$i]."]]></Description>
                          <PicUrl><![CDATA[http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.$imageinfo[$i]."]]></PicUrl>
                           <Url><![CDATA[".$links."]]></Url>
                           </item>";*/
							}
							$textTpl.= "</Articles>
                           <FuncFlag>0</FuncFlag>
                           </xml> 
							";
							$this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));
						}

					}

				}
			}
			
			// 课程体系
			if($EventKey == "kctx"){

							$key_list= $key_word->where("ismess=11")->find();
							if($key_list['type']==1)//文本
						{
							$this->wenben($fromUsername, $toUsername, $time,$key_list['kecontent']);
						}else //图文
						{
							$titles                   = unserialize($key_list['titles']);
							$imageinfo                = unserialize($key_list['imageinfo']);
							$linkinfo                 = unserialize($key_list['linkinfo']);

							$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount> 
                            <Articles>";
							for($i=0;$i<count($titles);$i++)
							{
								if(stristr($linkinfo[$i],$_SERVER['SERVER_NAME']))
								{
									if(stristr($linkinfo[$i],"?"))
									{
										$links=$linkinfo[$i]."&key=".$fromUsername;
									}else
									{
										$links=$linkinfo[$i]."?key=".$fromUsername;
									}
								}else{
									$links=$linkinfo[$i];
								}
                            if(stristr($imageinfo[$i],$_SERVER['SERVER_NAME']))
								{
								$images=$imageinfo[$i];
								}else
								{
								$images="http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.$imageinfo[$i];
								}
								
								
								$textTpl.= " <item>
                           <Title><![CDATA[".$titles[$i]."]]></Title> 
                           <Description><![CDATA[".$titles[$i]."]]></Description>
                          <PicUrl><![CDATA[".$images."]]></PicUrl>
                           <Url><![CDATA[".$links."]]></Url>
                           </item>";
							}
							$textTpl.= "</Articles>
                           <FuncFlag>0</FuncFlag>
                           </xml> 
							";
							$this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));

				}
			}
				// 精彩视频
				if($EventKey == "jcsp"){

							$key_list= $key_word->where("ismess=12")->find();
							if($key_list['type']==1)//文本
						{
							$this->wenben($fromUsername, $toUsername, $time,$key_list['kecontent']);
						}else //图文
						{
							$titles                   = unserialize($key_list['titles']);
							$imageinfo                = unserialize($key_list['imageinfo']);
							$linkinfo                 = unserialize($key_list['linkinfo']);

							$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount> 
                            <Articles>";
							for($i=0;$i<count($titles);$i++)
							{
								if(stristr($linkinfo[$i],$_SERVER['SERVER_NAME']))
								{
									if(stristr($linkinfo[$i],"?"))
									{
										$links=$linkinfo[$i]."&key=".$fromUsername;
									}else
									{
										$links=$linkinfo[$i]."?key=".$fromUsername;
									}
								}else{
									$links=$linkinfo[$i];
								}
                            if(stristr($imageinfo[$i],$_SERVER['SERVER_NAME']))
								{
								$images=$imageinfo[$i];
								}else
								{
								$images="http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.$imageinfo[$i];
								}
								
								
								$textTpl.= " <item>
                           <Title><![CDATA[".$titles[$i]."]]></Title> 
                           <Description><![CDATA[".$titles[$i]."]]></Description>
                          <PicUrl><![CDATA[".$images."]]></PicUrl>
                           <Url><![CDATA[".$links."]]></Url>
                           </item>";
							}
							$textTpl.= "</Articles>
                           <FuncFlag>0</FuncFlag>
                           </xml> 
							";
							$this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));

				}
				}
				// 品牌介绍
				if($EventKey == "ppjs"){

							$key_list= $key_word->where("ismess=13")->find();
							if($key_list['type']==1)//文本
						{
							$this->wenben($fromUsername, $toUsername, $time,$key_list['kecontent']);
						}else //图文
						{
							$titles                   = unserialize($key_list['titles']);
							$imageinfo                = unserialize($key_list['imageinfo']);
							$linkinfo                 = unserialize($key_list['linkinfo']);

							$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount> 
                            <Articles>";
							for($i=0;$i<count($titles);$i++)
							{
								if(stristr($linkinfo[$i],$_SERVER['SERVER_NAME']))
								{
									if(stristr($linkinfo[$i],"?"))
									{
										$links=$linkinfo[$i]."&key=".$fromUsername;
									}else
									{
										$links=$linkinfo[$i]."?key=".$fromUsername;
									}
								}else{
									$links=$linkinfo[$i];
								}
                            if(stristr($imageinfo[$i],$_SERVER['SERVER_NAME']))
								{
								$images=$imageinfo[$i];
								}else
								{
								$images="http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.$imageinfo[$i];
								}
								
								
								$textTpl.= " <item>
                           <Title><![CDATA[".$titles[$i]."]]></Title> 
                           <Description><![CDATA[".$titles[$i]."]]></Description>
                          <PicUrl><![CDATA[".$images."]]></PicUrl>
                           <Url><![CDATA[".$links."]]></Url>
                           </item>";
							}
							$textTpl.= "</Articles>
                           <FuncFlag>0</FuncFlag>
                           </xml> 
							";
							$this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));

				}
				}
			// 活动预告
				if($EventKey == "hdyg"){

							$key_list= $key_word->where("ismess=14")->find();
							if($key_list['type']==1)//文本
						{
							$this->wenben($fromUsername, $toUsername, $time,$key_list['kecontent']);
						}else //图文
						{
							$titles                   = unserialize($key_list['titles']);
							$imageinfo                = unserialize($key_list['imageinfo']);
							$linkinfo                 = unserialize($key_list['linkinfo']);

							$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount> 
                            <Articles>";
							for($i=0;$i<count($titles);$i++)
							{
								if(stristr($linkinfo[$i],$_SERVER['SERVER_NAME']))
								{
									if(stristr($linkinfo[$i],"?"))
									{
										$links=$linkinfo[$i]."&key=".$fromUsername;
									}else
									{
										$links=$linkinfo[$i]."?key=".$fromUsername;
									}
								}else{
									$links=$linkinfo[$i];
								}
                            if(stristr($imageinfo[$i],$_SERVER['SERVER_NAME']))
								{
								$images=$imageinfo[$i];
								}else
								{
								$images="http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.$imageinfo[$i];
								}
								
								
								$textTpl.= " <item>
                           <Title><![CDATA[".$titles[$i]."]]></Title> 
                           <Description><![CDATA[".$titles[$i]."]]></Description>
                          <PicUrl><![CDATA[".$images."]]></PicUrl>
                           <Url><![CDATA[".$links."]]></Url>
                           </item>";
							}
							$textTpl.= "</Articles>
                           <FuncFlag>0</FuncFlag>
                           </xml> 
							";
							$this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));

				}
				}
			// 活动回顾
				if($EventKey == "hdhg"){

							$key_list= $key_word->where("ismess=15")->find();
							if($key_list['type']==1)//文本
						{
							$this->wenben($fromUsername, $toUsername, $time,$key_list['kecontent']);
						}else //图文
						{
							$titles                   = unserialize($key_list['titles']);
							$imageinfo                = unserialize($key_list['imageinfo']);
							$linkinfo                 = unserialize($key_list['linkinfo']);

							$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount> 
                            <Articles>";
							for($i=0;$i<count($titles);$i++)
							{
								if(stristr($linkinfo[$i],$_SERVER['SERVER_NAME']))
								{
									if(stristr($linkinfo[$i],"?"))
									{
										$links=$linkinfo[$i]."&key=".$fromUsername;
									}else
									{
										$links=$linkinfo[$i]."?key=".$fromUsername;
									}
								}else{
									$links=$linkinfo[$i];
								}
                            if(stristr($imageinfo[$i],$_SERVER['SERVER_NAME']))
								{
								$images=$imageinfo[$i];
								}else
								{
								$images="http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.$imageinfo[$i];
								}
								
								
								$textTpl.= " <item>
                           <Title><![CDATA[".$titles[$i]."]]></Title> 
                           <Description><![CDATA[".$titles[$i]."]]></Description>
                          <PicUrl><![CDATA[".$images."]]></PicUrl>
                           <Url><![CDATA[".$links."]]></Url>
                           </item>";
							}
							$textTpl.= "</Articles>
                           <FuncFlag>0</FuncFlag>
                           </xml> 
							";
							$this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));

				}
				}
			// 联系我们
				if($EventKey == "lxwm"){

							$key_list= $key_word->where("ismess=16")->find();
							if($key_list['type']==1)//文本
						{
							$this->wenben($fromUsername, $toUsername, $time,$key_list['kecontent']);
						}else //图文
						{
							$titles                   = unserialize($key_list['titles']);
							$imageinfo                = unserialize($key_list['imageinfo']);
							$linkinfo                 = unserialize($key_list['linkinfo']);

							$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount> 
                            <Articles>";
							for($i=0;$i<count($titles);$i++)
							{
								if(stristr($linkinfo[$i],$_SERVER['SERVER_NAME']))
								{
									if(stristr($linkinfo[$i],"?"))
									{
										$links=$linkinfo[$i]."&key=".$fromUsername;
									}else
									{
										$links=$linkinfo[$i]."?key=".$fromUsername;
									}
								}else{
									$links=$linkinfo[$i];
								}
                            if(stristr($imageinfo[$i],$_SERVER['SERVER_NAME']))
								{
								$images=$imageinfo[$i];
								}else
								{
								$images="http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.$imageinfo[$i];
								}
								
								
								$textTpl.= " <item>
                           <Title><![CDATA[".$titles[$i]."]]></Title> 
                           <Description><![CDATA[".$titles[$i]."]]></Description>
                          <PicUrl><![CDATA[".$images."]]></PicUrl>
                           <Url><![CDATA[".$links."]]></Url>
                           </item>";
							}
							$textTpl.= "</Articles>
                           <FuncFlag>0</FuncFlag>
                           </xml> 
							";
							$this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));
						}
				}
			// 新闻中心
				if($EventKey == "xwzx"){

							$key_list= $key_word->where("ismess=17")->find();
							if($key_list['type']==1)//文本
						{
							$this->wenben($fromUsername, $toUsername, $time,$key_list['kecontent']);
						}else //图文
						{
							$titles                   = unserialize($key_list['titles']);
							$imageinfo                = unserialize($key_list['imageinfo']);
							$linkinfo                 = unserialize($key_list['linkinfo']);

							$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount> 
                            <Articles>";
							for($i=0;$i<count($titles);$i++)
							{
								if(stristr($linkinfo[$i],$_SERVER['SERVER_NAME']))
								{
									if(stristr($linkinfo[$i],"?"))
									{
										$links=$linkinfo[$i]."&key=".$fromUsername;
									}else
									{
										$links=$linkinfo[$i]."?key=".$fromUsername;
									}
								}else{
									$links=$linkinfo[$i];
								}
                            if(stristr($imageinfo[$i],$_SERVER['SERVER_NAME']))
								{
								$images=$imageinfo[$i];
								}else
								{
								$images="http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.$imageinfo[$i];
								}
								
								
								$textTpl.= " <item>
                           <Title><![CDATA[".$titles[$i]."]]></Title> 
                           <Description><![CDATA[".$titles[$i]."]]></Description>
                          <PicUrl><![CDATA[".$images."]]></PicUrl>
                           <Url><![CDATA[".$links."]]></Url>
                           </item>";
							}
							$textTpl.= "</Articles>
                           <FuncFlag>0</FuncFlag>
                           </xml> 
							";
							$this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));
						}
				}
				
			// 中心活动
				if($EventKey == "zxhd"){

							$key_list= $key_word->where("ismess=18")->find();
							if($key_list['type']==1)//文本
						{
							$this->wenben($fromUsername, $toUsername, $time,$key_list['kecontent']);
						}else //图文
						{
							$titles                   = unserialize($key_list['titles']);
							$imageinfo                = unserialize($key_list['imageinfo']);
							$linkinfo                 = unserialize($key_list['linkinfo']);

							$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount> 
                            <Articles>";
							for($i=0;$i<count($titles);$i++)
							{
								if(stristr($linkinfo[$i],$_SERVER['SERVER_NAME']))
								{
									if(stristr($linkinfo[$i],"?"))
									{
										$links=$linkinfo[$i]."&key=".$fromUsername;
									}else
									{
										$links=$linkinfo[$i]."?key=".$fromUsername;
									}
								}else{
									$links=$linkinfo[$i];
								}
                            if(stristr($imageinfo[$i],$_SERVER['SERVER_NAME']))
								{
								$images=$imageinfo[$i];
								}else
								{
								$images="http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.$imageinfo[$i];
								}
								
								
								$textTpl.= " <item>
                           <Title><![CDATA[".$titles[$i]."]]></Title> 
                           <Description><![CDATA[".$titles[$i]."]]></Description>
                          <PicUrl><![CDATA[".$images."]]></PicUrl>
                           <Url><![CDATA[".$links."]]></Url>
                           </item>";
							}
							$textTpl.= "</Articles>
                           <FuncFlag>0</FuncFlag>
                           </xml> 
							";
							$this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));
						}
				}
				
			// 预约试听
				if($EventKey == "yyst"){

							$key_list= $key_word->where("ismess=19")->find();
							if($key_list['type']==1)//文本
						{
							$this->wenben($fromUsername, $toUsername, $time,$key_list['kecontent']);
						}else //图文
						{
							$titles                   = unserialize($key_list['titles']);
							$imageinfo                = unserialize($key_list['imageinfo']);
							$linkinfo                 = unserialize($key_list['linkinfo']);

							$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount> 
                            <Articles>";
							for($i=0;$i<count($titles);$i++)
							{
								if(stristr($linkinfo[$i],$_SERVER['SERVER_NAME']))
								{
									if(stristr($linkinfo[$i],"?"))
									{
										$links=$linkinfo[$i]."&key=".$fromUsername;
									}else
									{
										$links=$linkinfo[$i]."?key=".$fromUsername;
									}
								}else{
									$links=$linkinfo[$i];
								}
                            if(stristr($imageinfo[$i],$_SERVER['SERVER_NAME']))
								{
								$images=$imageinfo[$i];
								}else
								{
								$images="http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.$imageinfo[$i];
								}
								
								
								$textTpl.= " <item>
                           <Title><![CDATA[".$titles[$i]."]]></Title> 
                           <Description><![CDATA[".$titles[$i]."]]></Description>
                          <PicUrl><![CDATA[".$images."]]></PicUrl>
                           <Url><![CDATA[".$links."]]></Url>
                           </item>";
							}
							$textTpl.= "</Articles>
                           <FuncFlag>0</FuncFlag>
                           </xml> 
							";
							$this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));
						}
				}
				if($Event=='subscribe')
				{
					$key_list= $key_word->where("isfollow=1")->find();
					if(is_array($key_list))//关注时回复
					{
						if($key_list['type']==1)//文本
						{
							$this->wenben($fromUsername, $toUsername, $time,$key_list['kecontent']);
						}else //图文
						{

							$titles                   = unserialize($key_list['titles']);
							$imageinfo                = unserialize($key_list['imageinfo']);
							$linkinfo                 = unserialize($key_list['linkinfo']);

							$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount> 
                            <Articles>";
							for($i=0;$i<count($titles);$i++)
							{
								if(stristr($linkinfo[$i],$_SERVER['SERVER_NAME']))
								{
									if(stristr($linkinfo[$i],"?"))
									{
										$links=$linkinfo[$i]."&key=".$fromUsername;
									}else
									{
										$links=$linkinfo[$i]."?key=".$fromUsername;
									}
								}else{
									$links=$linkinfo[$i];
								}
                            if(stristr($imageinfo[$i],$_SERVER['SERVER_NAME']))
								{
								$images=$imageinfo[$i];
								}else
								{
								$images="http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.$imageinfo[$i];
								}
								
								
								$textTpl.= " <item>
                           <Title><![CDATA[".$titles[$i]."]]></Title> 
                           <Description><![CDATA[".$titles[$i]."]]></Description>
                          <PicUrl><![CDATA[".$images."]]></PicUrl>
                           <Url><![CDATA[".$links."]]></Url>
                           </item>";
							}
							$textTpl.= "</Articles>
                           <FuncFlag>0</FuncFlag>
                           </xml> 
							";
							$this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));
						}
					}
				}
			}
				
			if(!empty($keyword))
			{
				$key_list= $key_word->where("kyword='".$keyword."'")->find();
				if(is_array($key_list))
				{
					if($key_list['type']==1)//文本
					{
						$this->wenben($fromUsername, $toUsername, $time,$key_list['kecontent']);
					}else //图文
					{
							$titles                   = unserialize($key_list['titles']);
							$imageinfo                = unserialize($key_list['imageinfo']);
							$linkinfo                 = unserialize($key_list['linkinfo']);
						
                    $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount> 
                            <Articles>";
                    for($i=0;$i<count($titles);$i++)
                    {
                    	if(stristr($linkinfo[$i],$_SERVER['SERVER_NAME']))
                    	{
                    		if(stristr($linkinfo[$i],"?"))
                    		{
                    			$links=$linkinfo[$i]."&key=".$fromUsername;
                    		}else
                    		{
                    			$links=$linkinfo[$i]."?key=".$fromUsername;
                    		}
                    	}else{
                    		$links=$linkinfo[$i];
                    	}
                    	   if(stristr($imageinfo[$i],$_SERVER['SERVER_NAME']))
								{
								$images=$imageinfo[$i];
								}else
								{
								$images="http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.$imageinfo[$i];
								}
								
								
								$textTpl.= " <item>
                           <Title><![CDATA[".$titles[$i]."]]></Title> 
                           <Description><![CDATA[".$titles[$i]."]]></Description>
                          <PicUrl><![CDATA[".$images."]]></PicUrl>
                           <Url><![CDATA[".$links."]]></Url>
                           </item>";
                    }
                          $textTpl.= "</Articles>
                           <FuncFlag>0</FuncFlag>
                           </xml> 
							";
                    $this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));
					}

				}else //自动回复
				{
					$key_list= $key_word->where("ismess=1")->find();
					if(is_array($key_list))//是否存在
					{
						if($key_list['type']==1)//文本
						{
							$this->wenben($fromUsername, $toUsername, $time, $key_list['kecontent']);
						}else //图文
						{
							$titles                   = unserialize($key_list['titles']);
							$imageinfo                = unserialize($key_list['imageinfo']);
							$linkinfo                 = unserialize($key_list['linkinfo']);

							$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							 <ArticleCount>%s</ArticleCount> 
                            <Articles>";
                    for($i=0;$i<count($titles);$i++)
                    {
                    	if(stristr($linkinfo[$i],$_SERVER['SERVER_NAME']))
                    	{
                    		if(stristr($linkinfo[$i],"?"))
                    		{
                    			$links=$linkinfo[$i]."&key=".$fromUsername;
                    		}else
                    		{
                    			$links=$linkinfo[$i]."?key=".$fromUsername;
                    		}
                    	}else{
                    		$links=$linkinfo[$i];
                    	}
                    	
                    	   if(stristr($imageinfo[$i],$_SERVER['SERVER_NAME']))
								{
								$images=$imageinfo[$i];
								}else
								{
								$images="http://".$_SERVER['SERVER_NAME'].__ROOT__.'/'.$imageinfo[$i];
								}
								
								
								$textTpl.= " <item>
                           <Title><![CDATA[".$titles[$i]."]]></Title> 
                           <Description><![CDATA[".$titles[$i]."]]></Description>
                          <PicUrl><![CDATA[".$images."]]></PicUrl>
                           <Url><![CDATA[".$links."]]></Url>
                           </item>";
                    }
                          $textTpl.= "</Articles>
                           <FuncFlag>0</FuncFlag>
                           </xml> 
							";
                    $this->tuwen($textTpl,$fromUsername, $toUsername, $time,count($titles));
						}
					}else
					{

					}
				}


			}else{
				echo "Input something...";
			}

		}else {
			echo "";
			exit;
		}

	}

	/**
	**zfck
	**/	
	public function sendmsg($fromUsername, $toUsername, $time, $para){
					
			$data['ftid'] =	"";	 
			$data['from_id']="";//用户名
			$data['from_name'] = $fromUsername;	 
			$data['to_id']= "";//用户名
			$data['to_name']=  (string)$toUsername;
			$data['add_time']= $time;
			$data['info']= $para;
			$data['status']= 1;
			$message = M('message')->add($data);
			if($message){			
				 return "亲，您的留言已经收到，我们会尽快处理！";
			}else{
				
				 return "留言没有发送成功！";
			}
			
		}
	 
	public function data($keyword){
		
			$cmd = array("天气"=>"tq","翻译"=>"fy","找书"=>"cs","电影"=>"movies","txl"=>"txl","bd"=>"bd","淘宝"=>"taobao","留言"=>"msg","音乐"=>"music","股票"=>"gp","快递"=>"kd","归属"=>"ckmobei","身份证"=>"ckpopid","通讯录"=>"concate");
			 
			$keyword = strtolower(trim($keyword));
	   
				if($keyword == "help"){

					$keywordArr = array();
					$keywordArr['cmd'] = "help";
					$keywordArr['para'] = $keyword;
					return $keywordArr;
					exit();	
				}
				
				$str2= stristr($keyword,":");

				$str= false;

				if(!$str2){
					$str1 = stristr($keyword,"：");
					if($str1){
					$keyword = str_replace("：","#",$keyword);
					$str = ture;
					}

				}else{
					$keyword = str_replace(":","#",$keyword);
					$str = ture;

				}
				 

				if($str){

					$keyfn = explode("#",$keyword);

					if(array_key_exists($keyfn[0],$cmd)){

						$keywordArr = array();	
						$keywordArr['cmd']  = $cmd[$keyfn[0]];
						$keywordArr['para'] = $keyfn[1];

						return $keywordArr;	

					}

				}else{
				 
					   return $keyword;	
				}

		}
		
		
		
		
	public function getkuaidi($keyword){
		
		import('Think.ORG.Kuaidicompany');// 导入快递公司
		
		$kd = new Kuaidicompany();

		$keyword = $this->get_utf8_string($keyword);

		$typeNu = $this->findNum($keyword);

		$str = $typeNu;

		$type = substr($keyword,0,strlen($keyword)-strlen($typeNu));

		$typeCom = $kd->ckkdcm($type);

	   if(isset($typeCom) && isset($typeNu)){

		 $url ="http://wap.kuaidi100.com/wap_result.jsp?rand=20130517&id=$typeCom&fromWeb=null&&postid=$typeNu";

		 $curl = curl_init();
		 curl_setopt ($curl, CURLOPT_URL, $url);
		 curl_setopt($curl,CURLOPT_HEADER,0);     
		 curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
		 curl_setopt ($curl, CURLOPT_TIMEOUT,5);
		
		 $content = curl_exec($curl);

		 $output = explode('<div style="margin:8px 0;padding:5px;background-color:#FFFAE2">',$content);

		 $content = explode('</div>',$output[1]);
		 $content = str_replace("</p>", "\n", $content[1]);
		 $content = str_replace("&middot;", "", $content);
		 $str = preg_replace( "@<(.*?)>@is", "", $content);

		if(empty($str)){ $str='查询失败，请重试';}  

		}else{

			$str='查询失败，请重新核对快递信息！';
		}
	 
		$contentStr = $this->get_utf8_string(trim($str)); 

		return  $contentStr;

	}
	
	/**
	 * 同步微信服务器所有关注用户
	 * @return
	 */
	public function getAllUserList(){
		//WechatUserService wUserSvc = new WechatUserService();// (WechatUserService)SpringUtil.getBean("wechatUserService");
		
		 $appId= C('pin_appid');
    	 $appSecret=C('pin_appsecret');
    	 $wechat_tx_url = "https://api.weixin.qq.com";
		$ACCESS_LIST= $this->curl($appid,$appsecret);//获取到的凭证
    	
    	if($ACCESS_LIST['access_token']!='')
    	{
    		$access_token=$ACCESS_LIST['access_token'];//获取到ACCESS_TOKEN
		
			$url = $wechat_tx_url."/cgi-bin/user/get?access_token="+$access_token;
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$a = curl_exec($ch);
			$strjson = json_decode($a);
			$openIdarr = $strjson->data->openid;
			for ($i = 0; $i < count($openIdarr); $i++) {
				$openid = $openIdarr[$i];
				$atr['openid']=$openid;
				echo $openid;
				//M('wechatuser')->add($atr);
			}
			
			//			if(next_openid!=null&&!"".equals(next_openid))
//				url += "&next_openid="+next_openid;
//			HttpClient client = new HttpClient();
//			GetMethod get = new GetMethod(url);
//	        try {
//	        	client.executeMethod(get);
//	        	String getJson=get.getResponseBodyAsString();
//	        	getJson = new String(getJson.getBytes("ISO8859_1"),"UTF-8");
//	        	JSONObject result = JSONObject.fromObject(getJson);
//	        	
//	        	int total = result.optInt("total");
//	        	int count = result.optInt("count");
//	        	if(count>0){
//		        	JSONArray array = result.optJSONObject("data").optJSONArray("openid");
//		        	String nextOpenid = result.optString("next_openid");
//		        	for(int i=0;i<array.size();i++){
//		        		String openid = array.getString(i);
//		        		if(new WechatUser().dao.getUserbyopenid(openid)==null){
//		        			SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");//设置日期格式	
//		        			UserService.subscribe(openid,df.format(new Date()));
//		        			//wUserSvc.subscribe(openid,""+DateUtil.getWechatDateTime());//101
//		        		}
//		        	}
//		        	if(!"".equals(nextOpenid)){
//		        		getAllUserList(nextOpenid);
//		        	}
//	        	}
//	        }
//	        catch (Exception e) {
//	        	LogUtil.exceptionLog(e);
//	        }
		}
	}

		 //  将一些字符转化成utf8格式      
	public function get_utf8_string($content)
	{    
		
		 $encoding = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK','BIG5'));  
		   return  mb_convert_encoding($content, 'utf-8', $encoding);
	}

	//提取字符串中数字

	public function findNum($str='')
	{
		$str=trim($str);
		if(empty($str)){return '';}
		$temp=array('1','2','3','4','5','6','7','8','9','0');
		$result='';
		for($i=0;$i<strlen($str);$i++){
			if(in_array($str[$i],$temp)){
				$result.=$str[$i];
			}
		}
		return $result;
	}
	
    
}