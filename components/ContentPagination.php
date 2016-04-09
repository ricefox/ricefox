<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/6
 * Time: 18:01
 */

/**
 * 内容分页链接
 */
namespace ricefox\components;

class ContentPagination
{
    public static $pages;
    public $startPage;
    public $endPage;
    public $pageSize=5;
    public static function run(&$content,$urlTpl,$page=1)
    {
        $obj=new static();
        $array=array_filter(explode('[page]',$content));
        $len=count($array);
        if($len>1){
            $content=$array[$page];
            return $obj->getPages($len,$page,$urlTpl);
        }
        return '';
    }

    /**
     * @param $count int 总页数
     * @param $page int 当前页
     * @param $tpl string url模版
     * @return string 分页html字符串
     */
    public function getPages($count,$page,$tpl)
    {
        $array=$this->getLinksArray($count,$page);
        if(!$array)return '';
        $pagination='<ul class="pagination pagination-center">';
        foreach($array as $item){
            $p='<li ';
            if(isset($item['active'])){
                $p.='class="active"';
            }
            $p.='>';
            if(isset($item['active'])){
                $href='javascript:void(0)';
            }else{
                if($item['page']==1){
                    $href=str_replace('_{page}','',$tpl);
                }else{
                    $href=str_replace('{page}',$item['page'],$tpl);
                }


            }
            $p.='<a href="'.$href.'">'.$item['name'].'</a>';
            $pagination.=$p."\n";
        }
        $pagination.='</ul>';
        return $pagination;
    }
    // 获取分页数组
    function getLinksArray($count,$page)
    {

        $links=[];
        $size=$this->pageSize;
        $this->computePage($count,$page);
        if($page > ($size / 2 + 1))
        {
            $links[]=['page'=>1,'name'=>'首页'];
        }
        if($page > $size/2)
        {
            $links[]=['page'=>$page-1,'name'=>'上一页'];
        }
        for($i=$this->startPage; $i <= $this->endPage; $i++)
        {
            $i=(int)$i;
            $link=['page'=>$i,'name'=>$i];
            if($i==$page){
                $link['active']=true;
            }
            $links[]=$link;
        }

        if($page < $count-($size/2))
        {
            $links[]=['page'=>$page+1,'name'=>'下一页'];
        }
        // 最后一页
        if($count>$size)
        {
            $links[]=['page'=>$count,'name'=>'尾页'];
        }
        return $links;
    }
    // 计算开始页到结束页
    function computePage($count,$page)
    {
        $pageSum=$count;
        $pageSize=$this->pageSize;
        if($pageSum<=$pageSize)
        {
            $this->startPage=1;
            $this->endPage=$pageSum;
            return true;
        }
        $isEven=$pageSize%2===0;
        if($isEven)
        {
            if($page<=$pageSize/2)
            {
                $this->startPage=1;
                $this->endPage=$pageSize;
            }
            else
            {
                if($pageSum-$page>=$pageSize/2)
                {
                    $this->startPage=$page-($pageSize/2)+1;
                    $this->endPage=$page+($pageSize/2);
                }
                else
                {
                    $this->endPage=$pageSum;
                    $this->startPage=$pageSum-$pageSize+1;
                }
            }
        }
        else
        {
            if($page<=($pageSize+1)/2)
            {
                $this->startPage=1;
                $this->endPage=$pageSize;
            }
            else
            {
                if($pageSum-$page>=($pageSize-1)/2)
                {
                    $this->startPage=$page-(($pageSize-1)/2);
                    $this->endPage=$page+(($pageSize-1)/2);
                }
                else
                {
                    $this->endPage=$pageSum;
                    $this->startPage=$pageSum-$pageSize+1;
                }
            }
        }
    }
}