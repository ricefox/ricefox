<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/4
 * Time: 11:05
 */

namespace ricefox\job\widgets;

class Welfare extends \yii\base\Widget
{
    /** @var  \ricefox\widgets\ActiveForm */
    public $form;
    /** @var  \ricefox\base\ActiveRecord */
    public $model;
    /** @var  string */
    public $width='350px';
    public $attribute='welfare';
    public $items=[];
    public function run()
    {
        \ricefox\job\widgets\WelfareAsset::register($this->getView());
        $options=[
            'width'=>$this->width,
            'nonSelectedText'=>'请选择',
            'cols'=>3
        ];
        $options=json_encode($options);
        $this->getView()->registerJs("window.welfareOptions=JSON.parse('$options')",\yii\web\View::POS_BEGIN);
        $form=$this->form;
        $model=$this->model;
        echo $form->fieldInline($model, $this->attribute)
            ->dropDownList($this->items,['id'=>'welfare','multiple'=>true]);
    }
}