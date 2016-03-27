<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/25
 * Time: 20:45
 */

$user=[
    'Rbac'=>'RBAC权限管理',
    'Role'=>'角色',
    'Permission'=>'权限',
    'Create Role'=>'创建角色',
    'Create Permission'=>'创建权限',
    'Role Name'=>'角色名',
    'Permission Name'=>'权限名',
    'Update Role'=>'更新角色',
    'Update Permission'=>'更新权限',
    'Children'=>'子项',
    'Login'=>'登录',
    'This username has already been taken.'=>'用户名已存在',
    'This email address has already been taken.'=>'邮箱已存在'
];

return array_merge(include('rf.php'),$user);