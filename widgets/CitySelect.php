<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/31
 * Time: 22:40
 */

namespace ricefox\widgets;
use ricefox\district\District;
use kartik\depdrop\DepDrop;
use yii\helpers\Url;
use Yii;
class CitySelect extends \yii\base\Widget
{
    /** @var  District */
    public $district;
    public $provinceId=0;
    public $cityId=0;
    public $areaId=0;
    public $url='';
    public $label='地点';
    /** @var  \ricefox\widgets\ActiveForm */
    public $form;
    /** @var \ricefox\base\ActiveRecord */
    public $model;
    public function init()
    {
        parent::init();
        $this->district=new District();
    }

    public function run()
    {
        CitySelectAsset::register($this->getView());
        $provinces=$this->district->getProvinces();
        $cities=$this->district->getCities($this->provinceId);
        $areas=$this->district->getAreas($this->cityId);
        $form=$this->form;
        $model=$this->model;
        echo '<div class="district">';
        echo $form->fieldInline($model, 'province')->label($this->label)->width(200)
            ->dropDownList($provinces, ['id'=>'provinceId','prompt'=>'请选择']);

        $controller=Yii::$app->controller->id;
        $action='district';
        //$url=$controller.'/'.$action;

        echo $form->fieldInline($model, 'city')->label(false)->width(200)
            ->dropDownList($cities,['id'=>'cityId','prompt'=>'请选择']);

        echo $form->fieldInline($model, 'area')->label(false)->width(200)
            ->dropDownList($areas,['id'=>'areaId','prompt'=>'请选择']);
        echo $form->fieldInline($model, 'address')->width(300)
            ->textInput(['maxlength' => true,'placeholder'=>'详细地址','id'=>'addressId'])->label(false);
        echo '</div>';

    }
    
}