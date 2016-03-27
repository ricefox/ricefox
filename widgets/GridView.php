<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/16
 * Time: 0:02
 */

namespace ricefox\widgets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use Yii;

class GridView extends \yii\grid\GridView
{
    public $dataColumnClass='ricefox\widgets\DataColumn';
    public $layout = "{summary}\n{form}\n{items}\n{footer}\n{formend}\n{pager}";
    public $showGridFooter=true;
    public $showGridForm=true;
    public $gridFormBegin=null;
    public $gridFormEnd=null;
    public $footers=[];
    public $formOptions=['method'=>'post'];
    public $summaryOptions=['class'=>'grid-summary form-inline'];
    function init()
    {
        parent::init();
        if($this->showGridForm && !isset($this->formOptions['id'])){
            $this->formOptions['id']=$this->options['id'].'-form';
        }
        if($this->showGridFooter===true)$this->runFooters();
        $this->summary='当前显示第 {begin} 到 {end} 行，共 {totalCount} 行';
    }
    function run()
    {
        $view=$this->getView();
        if($this->showGridForm){

            $formId=$this->formOptions['id'];
            $gridId=$this->options['id'];
            $view->registerJs("jQuery('.multi-item').on('click',{gridId:'$gridId'},multiSubmit)");
        }
        GridViewAsset::register($view);
        parent::run();
    }

    /**
     * @inheritdoc
     */
    public function renderSection($name)
    {
        switch ($name) {
            case '{summary}':
                return $this->renderSummary();
            case '{form}':
                if($this->showGridForm){
                    $method=ArrayHelper::remove($this->formOptions,'method','post');
                     $form=Html::beginForm(false,$method,$this->formOptions);
                    if($this->gridFormBegin instanceof \Closure){
                        $forms=call_user_func($this->gridFormBegin);
                        if(is_string($forms)){
                            $form=$form.$forms;
                        }
                    }
                    return $form;
                }else{
                    return '';
                }
            case '{formend}':
                $end=$this->showGridForm ? '</form>':'';
                if($this->gridFormEnd instanceof \Closure){
                    $ends=call_user_func($this->gridFormEnd);
                    if(is_string($ends))$end=$ends.$end;
                }
                return $end;
            case '{footer}':
                if($this->showGridFooter===false)return '';
                return $this->renderFooter();
            default:
                return parent::renderSection($name);
        }
    }

    public function renderSummary()
    {
        $name='gridShowRows';
        if(isset($_COOKIE[$name])){

            $selected=$_COOKIE[$name];
        }else{
            $selected=null;
        }
        $tag=ArrayHelper::remove($this->summaryOptions,'tag','div');
        $summary=Html::beginTag($tag,$this->summaryOptions);
        $summary.='<div class="col grid-summary-item">显示行数 '.Html::dropDownList($name,$selected,[20=>20,30=>30,50=>50,100=>100],['id'=>'gridShowRows','class'=>'form-control col']).'</div>';
        $summary.='<div class="col grid-summary-item">'.parent::renderSummary().'</div>';
        //$summary.=$this->renderSorter();
        $summary.=Html::endTag($tag);
        return $summary;
    }

    public function renderSorter()
    {
        $sort = $this->dataProvider->getSort();
        if ($sort === false || empty($sort->attributes) || $this->dataProvider->getCount() <= 0) {
            return '';
        }
        $attributes=array_keys($sort->attributes);
        $urls=[];
        foreach($attributes as $name){
            $url=$sort->createUrl($name);
            $urls[$url]=$name;
        }
        $html='<div class="col grid-summary-item">排序 '.Html::dropDownList('sort',0,$urls,['id'=>'sorts']).'</div>';
        return $html;

    }

    /**
     * 渲染表格的底部元素，如果showFooter属性为true时
     * @return string
     */
    public function renderFooter()
    {
        $footer='<div class="grid-footer">';
        $footer.='<div class="col grid-footer-item grid-footer-first">选中项：</div>';

        foreach($this->footers as $item){
            $footer.='<div class="col grid-footer-item">'.$item.'</div>';
        }
        $footer.='</div>';
        return $footer;
    }

    /**
     * 获取gird的底部元素
     *
     * 可以为数组，['tag'=>'a','content'=>'hello','options'=>['class'=>'hello']],并使用Html::tag()方法渲染,tag为button、submitButton时
     * 使用Html::button()/Html::submitButton()方法渲染
     * 也可以为字符串，将直接输出。
     * 键为'update','delete'时，特殊对象，若为字符串，则视为表单提交的url
     */
    public function runFooters()
    {

        $footers=$this->footers;

        foreach($footers as $key=>$item){
            if(is_array($item)){
                $options=isset($item['options'])?$item['options']:[];
                $content=isset($item['content'])?$item['content']:'';
                if(isset($item['tag'])){
                    switch($item['tag']){
                        case 'button':
                            $footers[$key]=Html::button($content,$options);
                            break;
                        case 'submitButton':
                            $footers[$key]=Html::submitButton($content,$options);
                            break;
                        default:
                            $footers[$key]=Html::tag($item['tag'],$content,$options);
                    }
                }else{
                    unset($footers[$key]);
                }
            }
        }
        if(!isset($footers['delete'])|| is_string($footers['delete'])){
            $action=isset($footers['delete'])?$footers['delete']:Url::toRoute('multi-delete');
            $html=Html::submitButton('删除',['id'=>$this->formOptions['id'].'-delete',
                'data-action'=>$action,'class'=>'btn btn-primary delete multi-item']);
            array_unshift($footers,$html);
        }
        if(!isset($footers['update']) || is_string($footers['update'])){
            $action=isset($footers['update'])?$footers['update']:Url::toRoute('multi-update');
            $html=Html::submitButton('更新',['id'=>$this->formOptions['id'].'-update',
                'data-action'=>$action,'class'=>'btn btn-primary multi-item']);
            array_unshift($footers,$html);
        }
        $this->footers=$footers;
    }
}