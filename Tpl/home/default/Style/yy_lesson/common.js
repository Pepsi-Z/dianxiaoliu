/*
* @Desc 前台js通用函数文件
* @Author Joker
* @Date 2014-05-01
*/
$('.J-confirm-dialog').click(function (e) {
    var confirm = $(this).attr('data-confirm');
    if (!confirm) return;
    var result = window.confirm(confirm);
    if (!result) {
        e.stopImmediatePropagation();
        e.preventDefault();
    }
});


// 课程预定通用代码
$("#orderClass").click(function(){
	var wechat_id = document.getElementById("wechat_id").value;
	if(wechat_id == "FromUserName"){
		window.location.href = "/Wap/index/fromShare/keyword/课程预约/title/课程预约";
		toShare();
		return false;
	}else{
		$.ajax({
			url:ORDER_CLASS_URL,
			type:'post',
			//data:{id:id},
			datatype:'text',
			async:true,
			success:function(data){
				//alert(data);
				if(data=='unRegister'){
					//window.location.href=REGISTER_PAGE_URL;
					window.location.href=ORG_LIST_URL;
				}
				if(data=='vip'){
					alert('您是会员无需预约');
				}
				if(data=='ordered'){
					alert('您已经预约过了,不能再次预约');
				}
				if(data == 'org_list'){
					window.location.href=ORG_LIST_URL;
				}
				if(data == 'org'){
					window.location.href=CLASS_QUERY_URL;
				}
			},
			error:function(){
				alert('请求失败');
			}	
		});
	}
});	

/*
* @Desc 前台课程二级联动js
* @Author Yu Liu
* @Date 2014-05-023
*/
function docheck(ob){
	var pid=ob;
	$.ajax({
		url:"/Wap/class/classGet/",
		type:'post',
		data:{pid:pid},
		datatype:'json',
		async:true,
		success:function(data){
			var data=eval(data);
			var old="----请选择课程----";
			//判断返回是否为空
			if(data==null){
				$("#class option").remove();
				$("#class").append("<option>"+old+"</option>");
			}
			if(data!=null){
				$("#class option").remove();//移除节点并循环插入option
				for(var i =0;i<data.length;i++){  
				   var item=data[i];  
				   $("#class").append("<option value = '"+item.id+"'>"+item.name+"</option>");  
				};  
			}
		},
		error:function(){
			alert('请求失败');
		}	
	});
	
}

/*
* @Desc 去掉微信分享功能
* @Author 
* @Date 2014-06-26
*/

// function onBridgeReady(){
//  WeixinJSBridge.call('hideOptionMenu');
// }

// if (typeof WeixinJSBridge == "undefined"){
//     if( document.addEventListener ){
//         document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
//     }else if (document.attachEvent){
//         document.attachEvent('WeixinJSBridgeReady', onBridgeReady); 
//         document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
//     }
// }else{
//     onBridgeReady();
// }



	