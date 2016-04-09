<?php

namespace ricefox\job\models;

use Yii;

/**
 * This is the model class for table "hire".
 *
 * @property integer $id
 * @property string $title
 * @property string $job
 * @property integer $company_id
 * @property integer $number
 * @property integer $year
 * @property integer $city
 * @property integer $area
 * @property string $welfare
 * @property integer $type
 * @property integer $edu
 * @property integer $salary
 * @property string $address
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $content
 */

class Hire extends \ricefox\base\ActiveRecord
{
    public $province;
    public function behaviors()
    {
        return [

        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hire';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'job', 'number', 'year', 'type', 'edu', 'salary', 'content'], 'required'],
            [['company_id', 'number', 'year' , 'type', 'edu', 'salary'], 'integer'],
            [['content'], 'string'],
            [['title', 'job'], 'string', 'max' => 25],
            [['address'], 'string', 'max' => 50],
            [['welfare'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rf_job', 'ID'),
            'title' => '职位标题',
            'job' => '招聘职位',
            'company_id' => Yii::t('rf_job', 'Company ID'),
            'number' => Yii::t('rf_job', 'Hire Number'),
            'year' => '经验要求',
            'city' => Yii::t('rf_job', 'City'),
            'area' => Yii::t('rf_job', 'Area'),
            'welfare' => '福利',
            'type' => '招聘类型',
            'edu' => '学历要求',
            'salary' => '薪水',
            'address' => '详细地址',
            'created_at' => Yii::t('rf_job', 'Created At'),
            'updated_at' => Yii::t('rf_job', 'Updated At'),
            'content' => '职位信息',
        ];
    }
    public function beforeValidate()
    {
        if(is_array($this->welfare)){
            $this->welfare=implode(',',$this->welfare);
        }
        return parent::beforeValidate();
    }
    public function beforeSave($insert)
    {
        if($insert){
            if(!$this->created_at)$this->created_at=time();
        }
        $this->updated_at=time();
        return parent::beforeSave($insert);
    }
}
