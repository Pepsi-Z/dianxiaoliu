 $(function(){
     var imgurl=$("#logimg").attr("src");  //分享中带有的图片
     var url=window.location.href;         //分享页的地址
     url = CheckWechatId(url);
     var title=document.title;             //分享内容的标题
     //var desc = document.getElementByName("description").content;
     var desc = '';
     weixin(imgurl,url,title,desc);
 });

function CheckWechatId(url) {
    var chars = url;
    var newUrl = /wechat_id\/.{1,28}/.exec(url),nUrl;
    if (newUrl == null) {
        var Urlhtml = /.html/.exec(url);
        if (Urlhtml == null) {
            nUrl = chars + "/wechat_id/FromUserName";
            return nUrl;
        } else {
            nUrl = chars.replace(Urlhtml, "/wechat_id/FromUserName");
            return nUrl;
        }
    } else {
        nUrl = chars.replace(newUrl, "wechat_id/FromUserName");
        return nUrl;
    }
}

//获取所有A标签，循环替换wechatId
function replaceLink(){
    //var aLen = $('a').parent('.coryright').siblings();
    var aLen = $('a');
    for (var i = aLen.length-1; i >= 0; i--) {
        url = CheckWechatId(aLen.eq(i).attr('href'));
        aLen.eq(i).attr('href',url);
    };
}


function weixin(a,b,c,desc){ 
    document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
        window.shareData = {
            "imgUrl": a,
            "timeLineLink": b,
            "sendFriendLink": b,
            "weiboLink": b,
            "tTitle": c,
            "fTitle": c,
            "tContent": desc,
            "fContent": desc,
            "wContent": desc
        };


        // 发送给好友
        WeixinJSBridge.on('menu:share:appmessage', function (argv) {
            WeixinJSBridge.invoke('sendAppMessage', {
                "img_url": window.shareData.imgUrl,
                "img_width": "640",
                "img_height": "640",
                "link": window.shareData.sendFriendLink,
                "desc": window.shareData.fContent,
                "title": window.shareData.fTitle
            }, function (res) {
                _report('send_msg', '111111');
            })
        });


        // 分享到朋友圈
        WeixinJSBridge.on('menu:share:timeline', function (argv) {
            WeixinJSBridge.invoke('shareTimeline', {
                "img_url": window.shareData.imgUrl,
                "img_width": "640",
                "img_height": "640",
                "link": window.shareData.timeLineLink,
                "desc": window.shareData.tContent,
                "title": window.shareData.tTitle
            }, function (res) {
                _report('timeline', res.err_msg);
            });
        });


        // 分享到微博
        WeixinJSBridge.on('menu:share:weibo', function (argv) {
            WeixinJSBridge.invoke('shareWeibo', {
                    "content": window.shareData.wContent,
                    "url": window.shareData.weiboLink
                }, function (res) {
                    _report('weibo', res.err_msg);
                });
        });
    }, false)
}

function toShare(){
    var url = location.href;
    if(url.indexOf("review/show") > 0){
        window.location.href = "/Wap/index/fromShare/keyword/活动回顾/title/活动回顾";
    }
}
