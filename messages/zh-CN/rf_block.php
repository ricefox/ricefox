<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/25
 * Time: 20:45
 */

$block=[
    'Class ID'=>'模块标识',
    'Class'=>'模块类',
    'Module ID'=>'模块',
    'Parent ID'=>'父栏目',
    'Show Type'=>'信息显示类型',
    'Uri Type'=>'类型',
    'Suffix Slashes'=>'后缀',
    'Title Name'=>'标题名',
    'Related'=>'相关栏目',
    'Admin Menu'=>'后台菜单'
];

return array_merge(include('rf.php'),$block);