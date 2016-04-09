<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/13
 * Time: 13:40
 */

include (__DIR__.'/Base.php');

/**
 * Class CatchImage
 * 抓取远程图片，并添加水印
 */
class CatchImage extends Base
{
    /**
     * @var string 待抓取图片的url
     */
    public $src;
    /**
     * @var integer 图片宽度
     */
    public $imageWidth;
    /**
     * @var integer 图片高度
     */
    public $imageHeight;

    /**
     * @param $src
     * @param $config Array 配置数组。示例
     *   $config = array(
     *   "pathFormat" => $conf['catcherPathFormat'],
     *   "maxSize" => $conf['catcherMaxSize'],
     *   "allowFiles" => $conf['catcherAllowFiles'],
     *   "originName" => "remote.png",
     *   'rootPath'=>isset($conf['catcherRootPath']) ? $conf['catcherRootPath'] : $conf['rootPath']
     *   );
     */
    function __construct($src,Array $config)
    {
        $this->src=$src;
        $this->config=$config;
    }

    function run()
    {
        $imgUrl = htmlspecialchars($this->src);
        $imgUrl = str_replace("&amp;", "&", $imgUrl);
        if (strpos($imgUrl, "http") !== 0) {
            $this->stateInfo = $this->getState("ERROR_HTTP_LINK");
            return;
        }
        //获取请求头并检测死链
        $heads = get_headers($imgUrl);
        if (!(stristr($heads[0], "200") && stristr($heads[0], "OK"))) {
            $this->stateInfo = $this->getState("ERROR_DEAD_LINK");
            return;
        }
        //格式验证(扩展名验证和Content-Type验证)
        $fileType = strtolower(strrchr($imgUrl, '.'));
        if (!in_array($fileType, $this->config['allowFiles']) || stristr($heads['Content-Type'], "image")) {
            $this->stateInfo = $this->getState("ERROR_HTTP_CONTENTTYPE");
            return;
        }
        //打开输出缓冲区并获取远程图片
        ob_start();
        $context = stream_context_create(
            array('http' => array(
                'follow_location' => false // don't follow redirects
            ))
        );
        readfile($imgUrl, false, $context);
        $img = ob_get_contents();
        ob_end_clean();
        preg_match("/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m);

        $this->originName = $m ? $m[1]:"";
        $this->size = strlen($img);
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

        //创建目录失败
        if (!file_exists($dirname) && !mkdir($dirname, 0777, true)) {
            $this->stateInfo = $this->getState("ERROR_CREATE_DIR");
            return;
        } else if (!is_writeable($dirname)) {
            $this->stateInfo = $this->getState("ERROR_DIR_NOT_WRITEABLE");
            return;
        }
        //移动文件
        if (!(file_put_contents($this->absolutePath, $img) && file_exists($this->absolutePath))) { //移动失败
            $this->stateInfo = $this->getState("ERROR_WRITE_CONTENT");
        }
        else
        {   //移动成功
            $this->stateInfo = $this->stateMap[0];
            $info=getimagesize($this->absolutePath);
            $this->imageWidth=$info[0];
            $this->imageHeight=$info[1];
            $this->watermark();
        }
    }
}