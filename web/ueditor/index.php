<?php
/**
 * 文件为ueditor后台接口，主要处理图片上传、抓取远程图片等功能。
 *
*/

//header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
//header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
date_default_timezone_set("Asia/Chongqing");
error_reporting(1);
header("Content-Type: text/html; charset=utf-8");
define('BASE_PATH',__DIR__);
// 加载配置文件。
$conf = include(BASE_PATH.'/config/config.php');

$action = preg_replace('/[^a-z_-]/i','',$_GET['action']);
$return='';
if($action=='config'){
    $return=json_encode($conf);
}
// file_exists在windows的虚拟机共享目录下，不区分文件名大小写。
else if(file_exists(BASE_PATH.'/controllers/'.$action.'.php')){
    $return=include(BASE_PATH.'/controllers/'.$action.'.php');
}

/* 输出结果 */
if (isset($_GET["callback"])) {
    if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
        echo htmlspecialchars($_GET["callback"]) . '(' . $return . ')';
    } else {
        echo json_encode(array(
            'state'=> 'callback参数不合法'
        ));
    }
} else {
    echo $return;
}