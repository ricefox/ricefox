<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/26
 * Time: 12:30
 */

namespace ricefox\base;
use yii\helpers\ArrayHelper;

class ActiveRecord extends \yii\db\ActiveRecord
{
    /**
     * 通过主键更新单表中的多行数据
     * @param array $rows 待更新的多行数据，
     * @return bool
     * @throws \Exception
     */
    function updateMultiRows(Array $rows)
    {
        $model=$this;
        // 这里的$k是数据表主键。单主键 '5' , 联合主键 '{"id":"5","otherColumn":"columnValue"}'
        $pk=$model::primaryKey()[0];
        foreach($rows as $k=>$row){
            $cond=json_decode($k,true);
            if(!is_array($cond)){
                //单主键。
                $cond=[$pk=>$k];
            }
            $attributes=[];
            foreach($row as $attr=>$value){
                $model->$attr=$value;
                $attributes[]=$attr;
            }
            if($model->validate($attributes)){
                try{
                    $command=$model->getDb()->createCommand()->update($model->tableName(),$row,$cond);
                    $command->execute();
                }catch (\Exception $e){
                    throw new \Exception($e);
                }
            }else{
                return false;
            }
        }
        return true;
    }

    /**
     * 通过主键删除单表的多行数据。
     * @param array $rows 单主键：$rows=[1,2,3,...]，联合主键：$rows=[['id'=>5,'cid'=>7],['id'=>2,'cid'=>4],...]
     * @return bool 删除成功返回true，失败返回false
     * @throws \yii\db\Exception
     */
    public function deleteMultiRows(Array $rows)
    {
        $model=$this;
        if(!isset($rows[0]))return false;
        $table=$model->tableName();
        // 构造sql语句
        $sql="delete from `$table` where";
        $primaryKeys=!is_array($rows[0])?$model->primaryKey():array_keys($rows[0]);
        foreach($primaryKeys as $key){
            $sql.=" `$key`=:$key and";
        }
        $sql=substr($sql,0,-3);
        $command=$model->getDb()->createCommand($sql);
        foreach($rows as $key){
            $params=[];
            if(!is_array($key)){
                $params=[':'.$primaryKeys[0]=>$key];
            }else{
                foreach($key as $k=>$v){
                    $params[':'.$k]=$v;
                }
            }
            $command->bindValues($params)->execute();
        }
        return true;
    }

    public function getErrorString()
    {
        /** @var \yii\db\ActiveRecord $model */
        $model=$this;
        $errors=$model->getErrors();
        $string='';
        foreach($errors as $key=>$values){
            $label=$model->getAttributeLabel($key);
            $error=implode(' ',$values);
            $string.=$label.' : '.$error;
        }
        return $string;
    }

    public function getOne($cond)
    {
        /** @var \yii\db\ActiveRecord $model */
        $model=$this;
        $row=$model::findOne($cond);
        $formats=$this->formats();
        if(!empty($formats)){
            $this->formatRow($row);
        }
        return $row;
    }

    public function getAll($field='*',$cond='',$orderBy='',$options=[])
    {
        /** @var \yii\db\ActiveRecord $model */
        $model=$this;
        $query=$model->find()->select($field);
        if($cond)$query->where($cond);
        if($orderBy)$query->orderBy($orderBy);
        if(isset($options['asArray'])){
            $query->asArray();
        }
        $rows=$query->all();
        $array=[];
        $index=null;
        if(isset($options['index']))$index=$options['index'];
        $formats=$this->formats();
        if(!empty($formats)){
            foreach($rows as &$row){
                $this->formatRow($row);
                if($index)$array[$row[$index]]=$row;
            }
        }
        return $index ? $array : $rows;
    }

    public function getAllArray($field='*',$cond='',$orderBy='',$options=[])
    {
        $options['asArray']=true;
        return $this->getAll($field,$cond,$orderBy,$options);
    }

    public function keyValues($rows=[],$key='id',$value='name')
    {
        if(!$rows){
            $rows=$this->getAll();
        }
        return ArrayHelper::map($rows,$key,$value);
    }

    public function formatRow(&$row)
    {
        /** @var \yii\db\ActiveRecord $model */
        $model=$this;
        $formats=$this->formats();
        foreach($formats as $item)
        {
            $fields=$item[0];
            $method=$item[1];
            if(is_string($fields))$fields=(array)$fields;
            foreach($fields as $field){
                if(isset($row[$field]) && $row[$field]){
                    $row[$field]=$model->$method($row[$field]);
                }
            }
        }
    }

    public function formats()
    {
        return [];
    }

}