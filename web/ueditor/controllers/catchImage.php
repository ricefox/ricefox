<?php
/**
 * 抓取远程图片。
 */

if(!defined('BASE_PATH'))exit;
include BASE_PATH.'/components/CatchImage.php';
set_time_limit(0);

/* 上传配置 */
$config = array(
    "pathFormat" => $conf['catcherPathFormat'],
    "maxSize" => $conf['catcherMaxSize'],
    "allowFiles" => $conf['catcherAllowFiles'],
    "originName" => "remote.png",
    'rootPath'=>isset($conf['catcherRootPath']) ? $conf['catcherRootPath'] : $conf['rootPath']
);

$fieldName = $conf['catcherFieldName'];

// 获取通过post方法传递的图片url数组
if (isset($_POST[$fieldName])) {
    $source = $_POST[$fieldName];
} else {
    $source = $_GET[$fieldName];
}
// 返回的图片信息数组。
$list=[];//抓取后的图片信息。
$state=true;//保存抓取状态
$info=[];// 保存错误信息
foreach ($source as $imgUrl) {

    $catchImage = new CatchImage($imgUrl, $config);
    $catchImage->run();
    $list[]=[
        "state" => $catchImage->stateInfo,
        "url" => $catchImage->path,// url访问路径，如：/hello/world/image.png
        "size" => $catchImage->size,//图片大小
        "title" => htmlspecialchars($catchImage->name),// 图片名，如：hello.png
        "original" => htmlspecialchars($catchImage->originName),// 图片的原文件名
        "source" => htmlspecialchars($imgUrl),//图片原先的src
        'imageWidth'=>$catchImage->imageWidth,//图片宽度
        'imageHeight'=>$catchImage->imageHeight// 图片高度
    ];
    if($catchImage->stateInfo!=='SUCCESS'){
        $state=false;
        $info[]=$catchImage->stateInfo;
    }
}

/* 返回抓取数据 */
return json_encode(array(
    'state'=> count($list) && $state ? 'SUCCESS':'ERROR',
    'list'=> $list,
    'info'=>implode(' ',$info)
));