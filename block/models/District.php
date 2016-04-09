<?php

namespace ricefox\block\models;

use Yii;
use ricefox\behaviors\ActiveTree;
/**
 * This is the model class for table "district".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parentid
 * @property string $initial
 * @property string $initials
 * @property string $pinyin
 * @property string $suffix
 * @property string $code
 * @property integer $order
 */

class District extends \ricefox\base\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class'=>ActiveTree::className()
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'district';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parentid', 'order'], 'integer'],
            [['name', 'initials'], 'string', 'max' => 90],
            [['initial'], 'string', 'max' => 9],
            [['pinyin'], 'string', 'max' => 270],
            [['suffix'], 'string', 'max' => 45],
            [['code'], 'string', 'max' => 63],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('rf_block', 'ID'),
            'name' => Yii::t('rf_block', 'Name'),
            'parentid' => Yii::t('rf_block', 'Parentid'),
            'initial' => Yii::t('rf_block', 'Initial'),
            'initials' => Yii::t('rf_block', 'Initials'),
            'pinyin' => Yii::t('rf_block', 'Pinyin'),
            'suffix' => Yii::t('rf_block', 'Suffix'),
            'code' => Yii::t('rf_block', 'Code'),
            'order' => Yii::t('rf_block', 'Order'),
        ];
    }


}
