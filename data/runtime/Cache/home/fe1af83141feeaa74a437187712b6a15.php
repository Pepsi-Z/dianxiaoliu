<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0" />
    <title>定位</title>
    <link rel="stylesheet" href="../Style/static/css/bootstrap.css">
    <link href="../Style/static/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="../Style/static/css/main.css" rel="stylesheet" type="text/css">
    <style>
        .lxtBox{ width:100%; text-align:center;}
        .lxt{ width:60%; margin-top:50px; }
        .modal{left: 12.5%; width: 75%;margin:0 auto; top:28%!important;}
    </style>
</head>
<body class="xq_bgcolor_w">
<div class="dingwei_w">
    <!--  <a href="#">长春</a>-->
    <div>


        <?php if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><span><?php echo ($val["sou"]); ?></span>
            <ul>
                <?php if(is_array($val["na"])): $i = 0; $__LIST__ = $val["na"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Index/index',array('id'=>$v['id'],'name'=>$v['name']));?>"><?php echo ($v["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
            </ul><?php endforeach; endif; else: echo "" ;endif; ?>

    </div>
    <script src="../Style/static/js/jquery-1.9.1.min.js"></script>
    <script src="../Style/static/js/bootstrap.js"></script>
    <script>
        $(function(){
            //点击否时的状态
            $('.btn-default').click(function(){
                var elm='<div class="c_black4"></div>';
                if($('.c_black4').length>0){
                }else{
                    $('body').append(elm);
                }
                $('.c_box').show();
                $('.c_black4').click(function(){
                    $('.c_box').hide();
                    $(this).remove();
                })
            })
            $(function(){
                //点击是时的状态
                $('.btn-primary').click(function(){
                    var elm='<div class="c_black4"></div>';
                    if($('.c_black4').length>0){
                    }else{
                        $('body').append(elm);
                    }
                    $('.c_box').show();
                    $('.c_black4').click(function(){
                        $('.c_box').hide();
                        $(this).remove();
                    })
                });
            })
        })
    </script>
</body>
</html>