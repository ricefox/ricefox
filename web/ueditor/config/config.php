<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/13
 * Time: 11:49
 */
/** @var $setting Array */
define('URL_PREFIX',$setting['static.urlPrefix']);

$array[]=[
    // 保存资源文件的根目录。
    'rootPath'=>realpath($_SERVER['DOCUMENT_ROOT'].'/../..').'/'.$setting['static.dir'],
    //'urlPrefix'=>'http://static.yiihigh.me'
];

/**
 * 上传图片的相关配置
 */
$array[]=[
    'imageActionName'=>'uploadImage',
    'imageFieldName'=>'images',
    'imageMaxSize'=>2048000,
    'imageAllowFiles'=>[".png", ".jpg", ".jpeg", ".gif", ".bmp"],
    'imageCompressEnable'=>true,
    'imageCompressBorder'=>1600,
    'imageInsertAlign'=>'none',
    'imageUrlPrefix'=>URL_PREFIX,
    'imagePathFormat'=>'/image/{yyyy}{mm}{dd}/{filename}'
];
/**
 * 抓取远程图片的配置
 */
$array[]=[
    //  “本地域名”，在此列表之内的不会被抓取，默认包含img.baidu.com
    "catcherLocalDomain"=> ["127.0.0.1", "localhost"],
    // 通过get传递的action参数的值
    "catcherActionName"=> "catchImage",
    //  提交的图片列表表单名称
    "catcherFieldName"=> "source",
    // 上传保存路径,可以自定义保存路径和文件名格式
    "catcherPathFormat"=> "/image/{yyyy}{mm}{dd}/{filename}",
    // 图片访问路径前缀
    "catcherUrlPrefix"=> URL_PREFIX,
    // 上传大小限制，单位B
    "catcherMaxSize"=> 2048000,
    // 允许的图片格式
    "catcherAllowFiles"=> [".png", ".jpg", ".jpeg", ".gif", ".bmp"],
];
/**
 * 上传文件的配置
 */
$array[]=[
    //  controller里,执行上传视频的action名称
    "fileActionName"=> "uploadfile",
    //  提交的文件表单名称
    "fileFieldName"=> "files",
    //  上传保存路径,可以自定义保存路径和文件名格式
    "filePathFormat"=> "/file/{yyyy}{mm}{dd}/{filename}",
    //  文件访问路径前缀
    "fileUrlPrefix"=> URL_PREFIX,
    //  上传大小限制，单位B，默认50MB
    "fileMaxSize"=> 51200000,

    "fileAllowFiles"=> [
        ".png", ".jpg", ".jpeg", ".gif", ".bmp",
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
        ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
        ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
    ]
];
/**
 * 显示图片
 */
$array[]=[
    "imageManagerActionName"=> "listimage", /* 执行图片管理的action名称 */
    "imageManagerListPath"=> "/image/", /* 指定要列出图片的目录 */
    "imageManagerListSize"=> 20, /* 每次列出文件数量 */
    "imageManagerUrlPrefix"=> URL_PREFIX, /* 图片访问路径前缀 */
    "imageManagerInsertAlign"=> "none", /* 插入的图片浮动方式 */
    "imageManagerAllowFiles"=> [".png", ".jpg", ".jpeg", ".gif", ".bmp"], /* 列出的文件类型 */
];
/**
 * 显示文件
 */
$array[]=[
    /* 列出指定目录下的文件 */
    "fileManagerActionName"=> "listfile", /* 执行文件管理的action名称 */
    "fileManagerListPath"=> "/file/", /* 指定要列出文件的目录 */
    "fileManagerUrlPrefix"=> URL_PREFIX, /* 文件访问路径前缀 */
    "fileManagerListSize"=> 20, /* 每次列出文件数量 */
    "fileManagerAllowFiles"=> [
        ".png", ".jpg", ".jpeg", ".gif", ".bmp",
        ".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
        ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid",
        ".rar", ".zip", ".tar", ".gz", ".7z", ".bz2", ".cab", ".iso",
        ".doc", ".docx", ".xls", ".xlsx", ".ppt", ".pptx", ".pdf", ".txt", ".md", ".xml"
    ]
];
$return=[];
foreach($array as $item){
    $return=array_merge($return,$item);
}
return $return;



