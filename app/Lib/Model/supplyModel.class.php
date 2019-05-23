<?php
class supplyModel extends RelationModel
{
    protected $_validate = array(
        array('name', 'require', '请填写产品名称'), //不能为空
//        array('username', '', '{%admin_name_exists}', 0, 'unique', 1), //新增的时候检测重复
    );

    protected $_link = array(
        //关联角色
        'cid' => array(
            'mapping_type' => BELONGS_TO,
            'class_name' => 'item_cate',
            'foreign_key' => 'cid',
        ),
        'pid' => array(
            'mapping_type' => BELONGS_TO,
            'class_name' => 'item_cate',
            'foreign_key' => 'pid',
        )
    );

    /*
     * 检测名称是否存在
     *
     * @param string $name
     * @param int $id
     * @return bool
     */
    public function name_exists($name, $id=0) {
        $pk = $this->getPk();
        $where = "name='" . $name . "'  AND ". $pk ."<>'" . $id . "'";
        $result = $this->where($where)->count($pk);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}