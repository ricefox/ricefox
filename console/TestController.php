<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/31
 * Time: 19:43
 */

namespace ricefox\console;
use Yii;
use ricefox\block\models\District;
use yii\helpers\ArrayHelper;
use Overtrue\Pinyin\Pinyin;
class TestController extends \yii\console\Controller
{
    public function actionHello()
    {
        $array=District::find()->orderBy('order')->indexBy('id')->asArray()->all();
        $file=Yii::getAlias('@ricefox/district/data/province.php');
        $pro=$this->getProvince($array);
        $cf=Yii::getAlias('@ricefox/district/data/city/');
        $af=Yii::getAlias('@ricefox/district/data/area/');
        file_put_contents($file,$this->getStr($pro));
        foreach($pro as $id=>$item){
            $city=$this->findCity($array,$id);
            if($city){
                $f=$cf.$id.'.php';
                file_put_contents($f,$this->getStr($city));
                foreach($city as $c_id=>$c){
                    $area=$this->findCity($array,$c_id);
                    if($area){
                        $f=$af.$c_id.'.php';
                        file_put_contents($f,$this->getStr($area));
                    }
                }
            }
        }
    }

    public function actionDo()
    {
        $files=$this->getFiles();
        foreach($files as $file){
            $array=require($file);
            $arr=ArrayHelper::map($array,'id','name');
            file_put_contents($file,$this->getStr($arr));
        }
        $file=Yii::getAlias('@ricefox/district/data/province.php');

    }

    public function actionA2()
    {
        $arr=\ricefox\block\models\Category::find()->asArray()->all();
        $trans=\ricefox\block\models\Category::getDb()->beginTransaction();
        try{
            foreach($arr as $item){
                $model=new \ricefox\block\models\UrlModel();
                $model->target_id=$item['id'];
                $model->url=$item['url'];
                $model->protocol=$item['protocol'];
                $model->domain=$item['domain'];
                $model->uri=$item['uri'];
                $model->uri_match=$item['uri_type'];
                $model->suffix_slashes=$item['suffix_slashes'];
                $model->route='article/article/category';
                if(!$model->save()){
                    $trans->rollBack();
                    print_r($model->getErrorString());
                }
            }
            $trans->commit();
        }catch (\Exception $e){
            $trans->rollBack();
            throw $e;
        }

    }

    public function getFiles()
    {
        $file=Yii::getAlias('@ricefox/district/data/area');
        $dh=opendir($file);
        $files=[];
        while(($f=readdir($dh))!==false){
            $nf=$file.'/'.$f;
            if(is_file($nf)){
                $files[]=$nf;
            }
        }
        return $files;
    }

    public function findCity($array,$id)
    {
        $data=[];
        foreach($array as $item){
            if($item['parent_id']==$id){
                $data[$item['id']]=$item;
            }
        }
        return $data;
    }

    public function getStr($array)
    {
        return "<?php\n return ". var_export($array,true).';';
    }
    public function getProvince($array)
    {
        $data=[];
        foreach($array as $id=>$arr){
            if($arr['parent_id']==0){
                $data[$id]=$arr;
            }
        }
        return $data;
    }
    public function actionPinyin()
    {
        $db=Yii::$app->db;
        $command=$db->createCommand('select * from district where status=0 limit 1');
        $update=$db->createCommand('update district set initial=:initial,initials=:initials,pinyin=:pinyin,status=1 where id=:id');
        $row=$command->queryOne();
        while($row){
            $name=$row['name'];
            $pin=$this->toPinyin($name);
            $params=[
                ':pinyin'=>$pin[0],
                ':initial'=>$pin[1],
                ':initials'=>$pin[2],
                ':id'=>$row['id']
            ];
            $update->bindValues($params);
            $update->execute();
            $command=$db->createCommand('select * from district where status=0 limit 1');
            $row=$command->queryOne();
        }
    }

    public function toPinyin($str)
    {
        Pinyin::set('accent',false);
        $pin= Pinyin::trans($str);
        $first=substr($pin,0,1);
        $arr=explode(' ',$pin);
        $thumb='';
        foreach($arr as $item){
            $thumb.=substr($item,0,1);
        }
        return [$pin,$first,$thumb];
    }
    public function actionTest()
    {
        $path=Yii::getAlias('@ricefox/city.php');
        $array=require($path);
        $db=Yii::$app->db;
        $command=$db->createCommand('insert into city (id,`name`,initial,initials,pinyin,`type`,sort) values(:id,:name,:initial,:initials,:pinyin,:type,:sort)');
        foreach($array as $id=>$name){
            $pin=$this->toPinyin($name);
            $params=[
                ':id'=>$id,
                ':name'=>$name,
                ':initial'=>$pin[1],
                ':initials'=>$pin[2],
                ':pinyin'=>$pin[0],
                ':type'=>'city',
                ':sort'=>$id
            ];
            $command->bindValues($params);
            $command->execute();
        }
    }
    public function actionArea()
    {
        $files=require(Yii::getAlias('@ricefox/areafile.php'));
        $db=Yii::$app->db;
        $command=$db->createCommand('insert into city (id,parent_id,`name`,initial,initials,pinyin,`type`,sort,status) values(:id,:parent_id,:name,:initial,:initials,:pinyin,:type,:sort,:status)');
        foreach($files as $file){
            $array=require($file);
            $parent_id=(int)basename($file);
            foreach($array as $id=>$name){
                $query=new \yii\db\Query();
                $row=$query->select('*')->from('city')->where('id='.$id)->one();
                if($row){
                    continue;
                }
                if(in_array($name,['剅河'])){
                    $pin=['lh','l','lou he'];
                }else{
                    $pin=$this->toPinyin($name);
                }
                if(!preg_match('/^[a-z ]+$/',$pin[0])){
                    $status=5;
                    $pin=['','',''];
                }else{
                    $status=0;
                }
                $params=[
                    ':id'=>$id,
                    ':parent_id'=>$parent_id,
                    ':name'=>$name,
                    ':initial'=>$pin[1],
                    ':initials'=>$pin[2],
                    ':pinyin'=>$pin[0],
                    ':type'=>'area',
                    ':sort'=>$id
                ];
                $params[':status']=$status;
                $command->bindValues($params);
                $command->execute();
            }
        }


    }

    public function actionQuan()
    {
        $files=require(Yii::getAlias('@ricefox/quanfile.php'));
        $db=Yii::$app->db;
        $command=$db->createCommand('insert into city (id,parent_id,`name`,initial,initials,pinyin,`type`,sort,status) values(:id,:parent_id,:name,:initial,:initials,:pinyin,:type,:sort,:status)');
        foreach($files as $file){
            $array=require($file);
            $parent_id=(int)basename($file);
            foreach($array as $id=>$name){
                $query=new \yii\db\Query();
                $row=$query->select('*')->from('city')->where('id='.$id)->one();
                if($row){
                    continue;
                }
                if(in_array($name,['剅河'])){
                    $pin=['lh','l','lou he'];
                }else{
                    $pin=$this->toPinyin($name);
                }
                if(!preg_match('/^[a-z ]+$/',$pin[0])){
                    $status=5;
                    $pin=['','',''];
                }else{
                    $status=0;
                }
                $params=[
                    ':id'=>$id,
                    ':parent_id'=>$parent_id,
                    ':name'=>$name,
                    ':initial'=>$pin[1],
                    ':initials'=>$pin[2],
                    ':pinyin'=>$pin[0],
                    ':type'=>'quan',
                    ':sort'=>$id
                ];
                $params[':status']=$status;
                $command->bindValues($params);
                $command->execute();
            }
        }


    }

    public function actionT2()
    {
        $db=Yii::$app->db;
        $query=new \yii\db\Query();
        $rows=$query->select('id,name')->from('city')->where('type="quan"')->all();
        $update=$db->createCommand('update city set `name`=:name where id=:id');
        foreach($rows as $row){
            $update->bindValues([
                ':name'=>trim($row['name'],',\'"'),
                ':id'=>$row['id']
            ]);
            $update->execute();
        }
    }

    public function actionGo()
    {
        $query=new \yii\db\Query();
        $row=$query->select('*')->from('city')->where('status=6 and type="quan"')->one();
        $db=Yii::$app->db;
        $update=$db->createCommand('update city set initial=:initial,initials=:initials,pinyin=:pinyin,status=:status where id=:id');
        while($row){
            $name=$row['name'];
            $pin=$this->toPinyin($name);
            $pinyin=explode(' ',$pin[0]);
            $str=implode('',$pinyin);
            if(!preg_match('/^[a-z]+$/',$str)){
                $pin=['','',''];
                $status=10;
            }else{
                $status=7;
            }
            $params=[
                ':initial'=>$pin[1],
                ':initials'=>$pin[2],
                ':pinyin'=>$pin[0],
                ':id'=>$row['id'],
                ':status'=>$status
            ];
            $update->bindValues($params);
            $update->execute();
            $query=new \yii\db\Query();
            $row=$query->select('*')->from('city')->where('status=6 and type="quan"')->one();
        }
    }

    public function dict($name)
    {
        $dict=[
            '剅河'=>['lh','l','lou he'],
            '长埫口'=>['']
        ];
    }
    public function actionFiles()
    {
        $path=Yii::getAlias('@ricefox/quan');
        $dh=opendir($path);
        while(($f=readdir($dh))){
            $f2=$path.'/'.$f;
            if(is_file($f2) && require($f2)){
                $files[]=$f2;
            }
        }
        file_put_contents('quanfile.php',"<?php \n return ".var_export($files,true).';');
    }
    public function actionTest2()
    {
        $name='虎丘';
        print_r($this->toPinyin($name));echo PHP_EOL;
        //echo Pinyin::trans($name),PHP_EOL;
    }
}