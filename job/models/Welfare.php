<?php

namespace ricefox\job\models;

use Yii;

/**
 * This is the model class for table "welfare".
 *
 * @property integer $id
 * @property string $name
 */

class Welfare extends \ricefox\base\ActiveRecord
{
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
        return 'welfare';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rf_job', 'ID'),
            'name' => Yii::t('rf_job', 'Name'),
        ];
    }
    public static function getItems()
    {
        $array=Welfare::find()->asArray()->all();
        $items=[];
        foreach($array as $item){
            $items[$item['id']]=$item['name'];
        }
        return $items;
    }


}
