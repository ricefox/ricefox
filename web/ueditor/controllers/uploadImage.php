<?php
/**
 * 上传图片
 */

if(!defined('BASE_PATH'))exit;

include(BASE_PATH.'/components/UploadImage.php');

$config = array(
    "pathFormat" => $conf['imagePathFormat'],
    "maxSize" => $conf['imageMaxSize'],
    "allowFiles" => $conf['imageAllowFiles'],
    'rootPath'=>isset($conf['imageRootPath']) ? $conf['imageRootPath'] : $conf['rootPath']
);

$fieldName = $conf['imageFieldName'];

$uploadImage=new UploadImage($fieldName,$config);

$uploadImage->run();

$return=[
    "state" => $uploadImage->stateInfo,
    "url" => $uploadImage->path,// url访问路径，如：/hello/world/image.png
    "size" => $uploadImage->size,//图片大小
    "title" => htmlspecialchars($uploadImage->name),// 图片名，如：hello.png
    "original" => htmlspecialchars($uploadImage->originName),// 图片的原文件名
    "type"=>$uploadImage->ext
];

return json_encode($return);