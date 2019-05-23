
var fn = {
    addClass:function(){
        //添加课程上不日期选择效果
        $('.week_ul li').click(function(){
            $(this).addClass("active1").siblings().removeClass("active1");
        });
        //点击添加课程
        $('.J_addClass').click(function(){
            var html = $('#addClassForm').html();
            layer.open({
                type: 1,
                content: html,
                style: 'width:100%; height:'+ document.documentElement.clientHeight +'px; background-color:#F2F2F2; border:none;',
                success:function(){
                    $('#formId').submit(function(){
                        alert(1);
                        return false;
                    })
                }
            });
        });
		 $('.J_Clickp p').click(function(){
            var html = $('#addClassFormp').html();
            layer.open({
                type: 1,
                content: html,
                style: 'width:100%; height:'+ document.documentElement.clientHeight +'px; background-color:#F2F2F2; border:none;',
                success:function(){
                    $('#formId').submit(function(){
                        alert(1);
                        return false;
                    })
                }
            });
        });
        //$('#formId').submit(function(){
        //    alert(2);
        //    return false;
        //})
    }
};

//
//var arr = ['1','2','3','4','5','6','7'];
////alert(arr[1])
//
//var sss = function(ids){
//    alert(ids);
//};
//
//function  ddd(k){
//    alert(k);
//}
//

//sss(2);
//ddd(1);


//var  arr2 = {
  //  id:'11',
 //   name:'wwwww',
  //  func:function (id) {
 //           alert(id);
 //   }
//}

//alert(arr2['name']);
//alert(arr2.name);
//arr2.func(2)