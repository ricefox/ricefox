<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/13
 * Time: 13:39
 */

include(__DIR__.'/Watermark.php');

class Base
{
    /**
     * @var string 状态信息
     */
    public $stateInfo;
    /**
     * @var integer 文件大小，单位为B
     */
    public $size;
    /**
     * @var string 文件保存后的文件名。
     */
    public $name;
    /**
     * @var string 文件保存后去掉根目录后的路径，如：http://www.example.com/hello/my.png 中的，/hello/my.png
     */
    public $path;
    /**
     * @var string 文件扩展名，如：png jpg
     */
    public $ext;
    /**
     * @var array 配置数组
     */
    public $config;
    /**
     * @var string 原来的文件名
     */
    public $originName=''; // 原文件名，如hello.png
    /**
     * @var string 文件保存后的绝对路径，如：/hello/world/my.png
     */
    public $absolutePath='';
    /**
     * @var string 文件保存的路径格式，由$config配置数组指定。
     */
    protected $pathFormat;
    /**
     * @var integer 文件所允许的最大值，由$config配置数组指定
     */
    protected $maxSize;
    /**
     * @var integer 允许的文件后缀，由$config配置数组指定
     */
    protected $allowFiles;
    /**
     * @var string 上传文件的根目录，由$config配置数组指定。不指定则为$_SERVER['DOCUMENT_ROOT']
     */
    protected $rootPath;
    public $stateMap = array( //上传状态映射表，国际化用户需考虑此处数据的国际化
        "SUCCESS", //上传成功标记，在UEditor中内不可改变，否则flash判断会出错
        "文件大小超出 upload_max_filesize 限制",
        "文件大小超出 MAX_FILE_SIZE 限制",
        "文件未被完整上传",
        "没有文件被上传",
        "上传文件为空",
        "ERROR_TMP_FILE" => "临时文件错误",
        "ERROR_TMP_FILE_NOT_FOUND" => "找不到临时文件",
        "ERROR_SIZE_EXCEED" => "文件大小超出网站限制",
        "ERROR_TYPE_NOT_ALLOWED" => "文件类型不允许",
        "ERROR_CREATE_DIR" => "目录创建失败",
        "ERROR_DIR_NOT_WRITEABLE" => "目录没有写权限",
        "ERROR_FILE_MOVE" => "文件保存时出错",
        "ERROR_FILE_NOT_FOUND" => "找不到上传文件",
        "ERROR_WRITE_CONTENT" => "写入文件内容错误",
        "ERROR_UNKNOWN" => "未知错误",
        "ERROR_DEAD_LINK" => "链接不可用",
        "ERROR_HTTP_LINK" => "链接不是http链接",
        "ERROR_HTTP_CONTENTTYPE" => "链接contentType不正确"
    );

    function getState($key)
    {
        return isset($this->stateMap[$key]) ? $this->stateMap[$key] : $this->stateMap['ERROR_UNKNOWN'];
    }

    function getExt()
    {
        if($this->ext) return $this->ext;
        $this->ext=strtolower(strrchr($this->originName, '.'));
        return $this->ext;
    }

    function getPath()
    {
        if($this->path)return $this->path;
        //替换日期事件
        $t = time();
        $d = explode('-', date("Y-y-m-d-H-i-s"));
        $format = $this->config["pathFormat"];
        $format = str_replace("{yyyy}", $d[0], $format);
        $format = str_replace("{yy}", $d[1], $format);
        $format = str_replace("{mm}", $d[2], $format);
        $format = str_replace("{dd}", $d[3], $format);
        $format = str_replace("{hh}", $d[4], $format);
        $format = str_replace("{ii}", $d[5], $format);
        $format = str_replace("{ss}", $d[6], $format);
        $format = str_replace("{time}", $t, $format);

        //过滤文件名的非法自负,并替换文件名
        $oriName = substr($this->originName, 0, strrpos($this->originName, '.'));
        $oriName = preg_replace("/[\|\?\"\<\>\/\*\\\\]+/", '', $oriName);
        $format = str_replace("{filename}", $oriName, $format);

        //替换随机字符串
        $randNum = rand(1, 10000000000) . rand(1, 10000000000);
        if (preg_match("/\{rand\:([\d]*)\}/i", $format, $matches)) {
            $format = preg_replace("/\{rand\:[\d]*\}/i", substr($randNum, 0, $matches[1]), $format);
        }

        $ext = $this->getExt();
        $this->path=$format . $ext;
        return $this->path;
    }

    function getAbsolutePath()
    {
        if($this->absolutePath)return $this->absolutePath;
        $path = $this->path;
        $rootPath = isset($this->config['rootPath'])?$this->config['rootPath']:$_SERVER['DOCUMENT_ROOT'];

        if (substr($path, 0, 1) != '/') {
            $path = '/' . $path;
        }
        $this->absolutePath=$rootPath . $path;
        return $this->absolutePath;
    }

    function getName()
    {
        if($this->name)return $this->name;
        $this->name=substr($this->path, strrpos($this->path, '/') + 1);
        return $this->name;
    }

    function checkSize()
    {
        return $this->size<=$this->config['maxSize'];
    }
    // 获取水印图片。
    function getWaterImage()
    {
        if(isset($this->config['watermark'])){
            $water=$this->config['watermark'];
            if($water[0]!='/'){
                $water=BASE_PATH.'/images/'.$water;
            }
        }else{
            $water=BASE_PATH.'/images/water.png';
        }
        if(file_exists($water)){
            return $water;
        }else{
            return false;
        }
    }
    // 给图片添加水印.
    function watermark()
    {
        if(($water=$this->getWaterImage())){
            Watermark::mark($this->absolutePath,$water,9);
        }
    }

    function checkExt()
    {
        return in_array($this->getExt(),$this->config['allowFiles']);
    }


}