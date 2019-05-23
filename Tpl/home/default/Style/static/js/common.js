/**
 * Created by Administrator on 2015/9/11.
 */
var fn = {
    release:function(Param){
        //获取分类
        $('#pid').change(function(){
            $('#cid').get(0).options.length=1;
            var pid = $(this).val();
            $.post(Param.postUrl,{ pid:pid },function(data){
                if(data.status == 1){
                    $('#cid').get(0).options.length=1;
                    $.each(data.data,function(i,v){
                        var $option=$('<option value="'+v.id+'">'+v.name+'</option>');
                        $('#cid').append($option);
                    })
                }else{
                    //alert("该分类下没有品牌");
                }
            },"json");
        });
        //页面提交
        $('#formId').submit(function(){
            var action = $(this).attr('action');
            var  data = $(this).serialize();
            $.post(action,data,function(re){
                //alert(re)
                var data_fun = '';
                if (re.data == 'reload') {
                    data_fun = function () {
                        window.location.reload();
                    }
                } else if (re.data) {
                    data_fun = function () {
                        window.location.href = re.data
                    }
                } else {
                    data_fun = false
                }
                alert(re.msg);
                setTimeout(function(){data_fun();},1500);
            },'json');
            return false;
        })

        $('input[type=file]').on('change',function(){
            var _this = this,id = $(_this).data('id');
            lrz(_this.files[0], {
                width:700,
                height:700,
                quality:0.7 ,
                before: function() {
                    // console.log('压缩快开始');
                },
                fail: function(err) {
                    layer.close(cover);
                    layer.open({content:err});
                },
                always: function() {
                    // console.log('压缩结束');
                },
                done: function (results) {
                    //alert(results.data)
                    //进行上传 图片
                    $.post(Param.imgUploadUrl, {img: results.base64,id:id}, function (re) {
                        if (re.status) {
                            $(_this).parent('li').css('background-image','url('+re.data+')');
                            $(_this).parent().find('input[type=hidden]').val(re.dialog);
                            results.base64 = null
                        } else {
                            results.base64 = null
                        }
                    }, 'json');
                }
            });
        })
    },
    lists:function(Param){
        $('.delete').click(function(){
            var id = $(this).attr('data-id');
            if(window.confirm("您确定要删除此条信息吗？")){
                $.post(Param.deleteUrl,{ id:id },function(re){
                    //alert(re)
                   if (re == 1) {
                       //alert('删除成功')
                       window.location.reload();
                    } else {
                        alert('删除失败');
                    }
                },'html');
                return false;
            }
        })
    },
    index : function(Param){
        var cid = 0,stop = true,type=0;
        var get_index_list = function(cid,type,page,empty){
            var _page_mod=$('input[name=page]');
            _page_mod.val(page);
            $.post(Param.list,{cid:cid,type:type,page:page},function(re){
                if(empty){
                    $('#index_list').empty();
                }
                if(re.status){
                    $('#index_list').append(re.data);
                }
                stop=true;
            },'json');
        };
        //分类触发
        $('.yy-tab li:first-child').click(function(){
            $('.yy-fenlei-tab').toggle();
            $('.yy-fenlei-ul2').hide();
        });
        //显示子分类
        $('.yy-fenlei-ul li').click(function(){
            var pid = $(this).attr('data-id');
            if(pid == '0'){
                window.location.reload();
            }else{
                $.post(Param.GetList,{ pid:pid },function(data){
                    if(data.status == 1){
                        $('.yy-fenlei-ul2').html('');
                        $('.yy-fenlei-ul2').html(data.data);
                        $('.yy-fenlei-ul2').show();
                        $('.yy-fenlei-ul2').find('li a').click(function(){
                            cid = $(this).data('id');
                            $('.yy-fenlei-tab').toggle();
                            get_index_list(cid,type,1,true);
                        })
                    }else{
                        //alert("该分类下没有品牌");
                    }
                },"json");
            }


            $(this).addClass('yy-fenlei-ul-dj').siblings().removeClass('yy-fenlei-ul-dj')
        });
        //选卡 切换
        $('.yy-tab li a span').click(function(){
            $(this).addClass('yy-tab-click');
            $(this).closest('li').siblings().find('span').removeClass('yy-tab-click');
            type = $(this).data('type');
            get_index_list(cid,type,1,true);
        });
        get_index_list(cid,type,1);

        $(window).scroll(function() {
            //当内容滚动到底部时加载新的内容
            if ( ( $(this).scrollTop() + $(window).height() + 10 >= $(document).height() && $(this).scrollTop() > 10 ) && stop==true) {
                stop = false;
                var _page_mod=$('input[name=page]'),_page = parseInt( _page_mod.val() ) + 1;
                get_index_list(cid,type,_page);
            }
        });

    }
};