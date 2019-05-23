
<?php
class groupAction extends backendAction {
    public function _initialize() {
        parent::_initialize();

        if(ACTION_NAME == 'index'){
            $big_menu = array(
                'title' => '添加会员级别',
                'iframe' => U('group/add'),
                'id' => 'add',
                'width' => '500',
                'height' => '350',
            );
            $this->assign('big_menu', $big_menu);
            $this->assign('list_table', true);
            $this->order = 'asc';
        }
    }
    //全局积分 配置
    public function setting(){
        if(IS_POST){
            $setting = I('post.setting');
            foreach ($setting as $key => $val) {
                $val = is_array($val) ? serialize($val) : $val;
                D('setting')->where(array('name' => $key))->save(array('data' => $val));
            }
            $this->success(L('operation_success'));
        }else{
            $this->display();
        }
    }


}