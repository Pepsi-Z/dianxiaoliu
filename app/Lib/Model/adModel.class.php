<?php
class adModel extends RelationModel {
    //关联关系
    protected $_link = array(
        //关联角色
        'pname' => array(
            'mapping_type' => BELONGS_TO,
            'class_name' => 'item_cate',
            'foreign_key' => 'pid',
        ),
    );


}