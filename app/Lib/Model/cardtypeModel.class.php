<?php
class cardtypeModel extends RelationModel
{
    protected $_validate = array(
       array('gname', 'require', '{%admin_username_empty}'), //不能为空
       array('gname', '', '{%admin_name_exists}', 0, 'unique', 1), //新增的时候检测重复
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
        $where = "gname='" . $name . "'  AND ". $pk ."<>'" . $id . "'";
        $result = $this->where($where)->count($pk);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}