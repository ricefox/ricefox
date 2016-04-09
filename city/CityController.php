<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/5
 * Time: 10:35
 */

namespace ricefox\city;
use Yii;
class CityController extends \yii\console\Controller
{
    /** @var  CityModel */
    public $model;
    public $basePath;
    public function init()
    {
        parent::init();
        $this->model=new CityModel();
        $this->basePath=Yii::getAlias('@ricefox/city');
    }

    public function actionCreateAlpha()
    {
        $alpha=$this->model->getCityAlpha();
        foreach($alpha as $key=>$value)
        {
            if($key=='hot')continue;
            $alphas=str_split($key);
            $array=$this->model->find()->select('id,name,initial')->where(['in','initial',$alphas])
                ->andWhere('type="city" and parent_id=0')->asArray()->all();
            $groups=[];
            foreach($array as $item){
                $groups[$item['initial']][$item['id']]=$item['name'];
            }
            $f=$this->basePath.'/data/alpha/'.$key.'.php';
            file_put_contents($f,"<?php \n return ".var_export($groups,true).";");
        }
    }

    public function actionCreateArea()
    {
        $cities=$this->model->find()->select('id')->where('type="city" and parent_id=0')->indexBy('id')->asArray()->all();
        foreach($cities as $cid=>$city)
        {
            print_r($cid);echo PHP_EOL;
            $cid=(int)$cid;
            $areas=$this->model->find()->select('id,name')->where('type="area" and parent_id='.$cid)->asArray()->all();
            $array=[];
            foreach($areas as $area){
                $array[$area['id']]=$area['name'];
            }
            $f=$this->basePath.'/data/area/'.$cid.'.php';
            if($array){
                file_put_contents($f,"<?php \n return ".var_export($array,true).";");
            }else{
                continue;
            }
            $quan1=[];
            foreach($array as $aid=>$aname){
                $quan=$this->model->find()->select('id,name')->where('type="quan" and parent_id='.$aid)->asArray()->all();
                $quan2=[];
                foreach($quan as $item){
                    $quan2[$item['id']]=$item['name'];
                }
                if($quan2){
                    $quan1[$aid]=$quan2;
                }
            }
            $f2=$this->basePath.'/data/quan/'.$cid.'.php';
            if($quan1){
                file_put_contents($f2,"<?php \n return ".var_export($quan1,true).";");
            }
        }

    }

    public function actionTest()
    {
        $arr=str_split('abc');
        $array=$this->model->find()->select('id,name,initial')->where(['in','initial',$arr])->limit(10)->all();
        print_r($array);
    }

    public function actionQuan()
    {
        $path=$this->basePath.'/data/quan2';
        $path2=$this->basePath.'/data/quan';
        $dh=opendir($path);
        while(($f=readdir($dh))!==false){
            $f2=$path.'/'.$f;
            if(is_file($f2)){
                $array=require($f2);
                foreach($array as $id=>$arr){
                    $f3=$path2.'/'.$id.'.php';
                    file_put_contents($f3,"<?php \n return ".var_export($arr,true).";");
                }
            }
        }
    }
}