<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/28
 * Time: 8:50
 */

namespace ricefox\behaviors;
use yii\db\ActiveRecord;
use yii\caching\Cache;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;

class ActiveTag extends \yii\base\Behavior
{
    /** @var Cache */
    public $cache = 'cache';

    /**
     * Get events list.
     * @return array
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_DELETE => 'invalidateTags',
            ActiveRecord::EVENT_AFTER_INSERT => 'invalidateTags',
            ActiveRecord::EVENT_AFTER_UPDATE => 'invalidateTags',
        ];
    }

    /**
     * Invalidate model tags.
     * @return bool
     */
    public function invalidateTags()
    {
        \yii\caching\TagDependency::invalidate(
            $this->getCacheComponent(),
            [
                $this->commonTag()
            ]
        );
        return true;
    }

    public function commonTag()
    {
        /** @var \ricefox\base\ActiveRecord $owner */
        $owner=$this->owner;
        return $owner->className().'[commonTag]';
    }

    public static function getCommonTag($model)
    {
        if (is_object($model) && $model instanceof ActiveRecord) {
            $class = $model->className();
        }else if(is_string($model)){
            $class=$model;
        }else{
            throw new InvalidParamException("Param $model must be a string or an \\yii\\db\\ActiveRecord object.");
        }
        return $class . '[commonTag]';
    }

    /**
     * @return Cache
     * @throws InvalidConfigException
     */
    private function getCacheComponent()
    {
        if (!($this->cache instanceof Cache)) {
            $this->cache = is_string($this->cache) ? Yii::$app->{$this->cache} : null;
            if (!$this->cache) {
                throw new InvalidConfigException('Invalid cache component Id');
            }
        }
        return $this->cache;
    }
}