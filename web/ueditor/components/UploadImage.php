<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/13
 * Time: 17:56
 */

include(__DIR__.'/Base.php');

class UploadImage extends Base
{
    /**
     * @var string 上传图片的表单字段名
     */
    public $fieldName;
    public $file;
    public $imageWidth;
    public $imageHeight;
    function __construct($fieldName,$config)
    {
        $this->fieldName=$fieldName;
        $this->config=$config;
    }
    function run()
    {
        $file = $this->file = $_FILES[$this->fieldName];
        if (!$file) {
            $this->stateInfo = $this->getState("ERROR_FILE_NOT_FOUND");
            return;
        }
        if ($this->file['error']) {
            $this->stateInfo = $this->getState($file['error']);
            return;
        } else if (!file_exists($file['tmp_name'])) {
            $this->stateInfo = $this->getState("ERROR_TMP_FILE_NOT_FOUND");
            return;
        } else if (!is_uploaded_file($file['tmp_name'])) {
            $this->stateInfo = $this->getState("ERROR_TMPFILE");
            return;
        }
        $info=getimagesize($file['tmp_name']);
        $this->imageWidth=$info[0];
        $this->imageHeight=$info[1];
        $this->originName = $file['name'];
        $this->size = $file['size'];
        $this->getExt();
        $this->getPath();
        $this->getAbsolutePath();
        $this->getName();
        $dirname = dirname($this->absolutePath);

        //检查文件大小是否超出限制
        if (!$this->checkSize()) {
            $this->stateInfo = $this->getState("ERROR_SIZE_EXCEED");
            return;
        }

        //检查是否不允许的文件格式
        if (!$this->checkExt()) {
            $this->stateInfo = $this->getState("ERROR_TYPE_NOT_ALLOWED");
            return;
        }

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getState("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getState("ERROR_DIR_NOT_WRITEABLE");
            return;
        }

        //移动文件
        if (!(move_uploaded_file($file["tmp_name"], $this->absolutePath) && file_exists($this->absolutePath))) { //移动失败
            $this->stateInfo = $this->getState("ERROR_FILE_MOVE");
        } else { //移动成功
            $this->stateInfo = $this->stateMap[0];
            $this->watermark();
        }
    }
}