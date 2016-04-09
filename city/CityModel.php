<?php

namespace ricefox\city;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property integer $id
 * @property integer $parent_id
 * @property string $name
 * @property string $initial
 * @property string $initials
 * @property string $pinyin
 * @property string $type
 * @property integer $sort
 * @property integer $status
 */

class CityModel extends \ricefox\base\ActiveRecord
{
    public $basePath;
    public function init()
    {
        $this->basePath=Yii::getAlias('@ricefox/city');
    }
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
        return 'city';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'sort', 'status'], 'integer'],
            [['name', 'initial', 'initials', 'pinyin', 'type'], 'required'],
            [['type'], 'string'],
            [['name'], 'string', 'max' => 10],
            [['initial'], 'string', 'max' => 1],
            [['initials'], 'string', 'max' => 7],
            [['pinyin'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'initial' => 'Initial',
            'initials' => 'Initials',
            'pinyin' => 'Pinyin',
            'type' => 'Type',
            'sort' => 'Sort',
            'status' => 'Status',
        ];
    }

    public static function getCityAlpha()
    {
        return [
            'hot'=>'热门',
            'abcd'=>'ABCD',
            'efghj'=>'EFGHJ',
            'klmn'=>'KLMN',
            'pqrst'=>'PQRST',
            'wxyz'=>'WXYZ'
        ];
    }
    public function getCityByAlpha($alpha)
    {
        $file=$this->basePath.'/data/alpha/'.$alpha.'.php';
        if(file_exists($file)){
            return require($file);
        }else{
            return [];
        }
    }
    public function getArea($cid)
    {
        $file=$this->basePath.'/data/area/'.$cid.'.php';
        if(file_exists($file)){
            return require($file);
        }else{
            return [];
        }
    }
    public function getQuan($aid)
    {
        $file=$this->basePath.'/data/quan/'.$aid.'.php';
        if(file_exists($file)){
            return require($file);
        }else{
            return [];
        }
    }

}
