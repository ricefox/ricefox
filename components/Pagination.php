<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/5
 * Time: 18:51
 */

namespace ricefox\components;

use yii\data\Pagination as ParentPagination;

class Pagination extends ParentPagination
{
    /**
     * @var int 分页显示多少页。
     */
    public $showPages=5;
    private $_isCompute;
    private $_startPage;
    private $_endPage;
    private $_hasPage;
    public $showEnd=false;
    function show($url)
    {
        $array=$this->getPagesArray();
        if(!$array)return '';
        $pagination='<ul class=pagination>';
        foreach($array as $item){
            $p='<li ';
            if(isset($item['selected'])){
                $p.='class="selected"';
            }
            $p.='>';
            if(isset($item['selected'])){
                $href='javascript:void(0)';
            }else{
                $replace=$item['page']===1 ? '' : $item['page'];
                $href=str_replace('{page}',$replace,$url);
            }
            $p.='<a href="'.$href.'">'.$item['name'].'</a>';
            $pagination.=$p."\n";
        }
        $pagination.='</ul>';
        return $pagination;
    }
    private function __getPage()
    {
        return $this->getPage()+1;
    }
    function computePage()
    {
        if($this->_isCompute){
            return true;
        }
        $infoSize=$this->getPageSize();
        $pageSum=$this->getPageCount();
        $infoSum=$this->totalCount;
        $pageSize=$this->showPages;
        $page=$this->__getPage();
        if($infoSum<=$infoSize)
        {
            $this->_hasPage=false;
            return true;
        }
        $this->_hasPage=true;
        if($pageSum<=$pageSize)
        {
            $this->_startPage=1;
            $this->_endPage=$pageSum;
            return true;
        }
        $isEven=$pageSize%2===0;
        if($isEven)
        {
            if($page<=$pageSize/2)
            {
                $this->_startPage=1;
                $this->_endPage=$pageSize;
            }
            else
            {
                if($pageSum-$page>=$pageSize/2)
                {
                    $this->_startPage=$page-($pageSize/2)+1;
                    $this->_endPage=$page+($pageSize/2);
                }
                else
                {
                    $this->_endPage=$pageSum;
                    $this->_startPage=$pageSum-$pageSize+1;
                }
            }
        }
        else
        {
            if($page<=($pageSize+1)/2)
            {
                $this->_startPage=1;
                $this->_endPage=$pageSize;
            }
            else
            {
                if($pageSum-$page>=($pageSize-1)/2)
                {
                    $this->_startPage=$page-(($pageSize-1)/2);
                    $this->_endPage=$page+(($pageSize-1)/2);
                }
                else
                {
                    $this->_endPage=$pageSum;
                    $this->_startPage=$pageSum-$pageSize+1;
                }
            }
        }
        return true;
    }

    function getPagesArray()
    {
        $this->computePage();
        if(!$this->_hasPage)return [];
        $links=[];
        $size=$this->showPages;
        $page=$this->__getPage();//当前页。
        $count=$this->getPageCount();//总页数
        if($page > ($size / 2 + 1))
        {
            $links[]=['page'=>1,'name'=>'首页'];
        }
        if($page > $size/2)
        {
            $links[]=['page'=>$page-1,'name'=>'上一页'];
        }

        for($i=$this->_startPage; $i <= $this->_endPage; $i++)
        {
            $i=(int)$i;
            $link=['page'=>$i,'name'=>$i];
            if($i==$page){
                $link['selected']=true;
            }
            $links[]=$link;
        }

        if($page < $count-($size/2))
        {
            $links[]=['page'=>$page+1,'name'=>'下一页'];
        }
        // 最后一页
        if($this->showEnd && $count>$size)
        {
            $links[]=['page'=>$count,'name'=>'尾页'];
        }
        return $links;
    }

}

