<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/25
 * Time: 20:45
 */

$article=[
    'Article Module'=>'文章模块',
    'Article Manage'=>'文章管理',
    'Category ID'=>'栏目',
    'Pagination'=>'分页方式',
    'Pagination Length'=>'每页字符数',
    'Source'=>'来源',
    'Username'=>'作者'
];

return array_merge(include('rf.php'),$article);