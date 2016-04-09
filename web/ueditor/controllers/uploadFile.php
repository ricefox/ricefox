<?php
/**
 * 上传附件
 */

if(!defined('BASE_PATH'))exit;

include(BASE_PATH.'/components/UploadFile.php');

$config = array(
    "pathFormat" => $conf['filePathFormat'],
    "maxSize" => $conf['fileMaxSize'],
    "allowFiles" => $conf['fileAllowFiles'],
    'rootPath'=>isset($conf['fileRootPath']) ? $conf['fileRootPath'] : $conf['rootPath']
);

$fieldName = $conf['fileFieldName'];

$uploadFile=new UploadFile($fieldName,$config);

$uploadFile->run();

$return=[
    "state" => $uploadFile->stateInfo,
    "url" => $uploadFile->path,// url访问路径，如：/hello/world/image.txt
    "size" => $uploadFile->size,//文件大小
    "title" => htmlspecialchars($uploadFile->name),// 保存后的文件名，如：hello.txt
    "original" => htmlspecialchars($uploadFile->originName),// 原文件名
    "type"=>$uploadFile->ext
];

return json_encode($return);