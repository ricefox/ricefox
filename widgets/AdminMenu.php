<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/3/30
 * Time: 13:56
 */

namespace ricefox\widgets;
use ricefox\block\models\AdminMenu as Model;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;


class AdminMenu extends \yii\bootstrap\Widget
{
    public function run()
    {
        $model=new Model();
        $menus=$model->getMenus();
        $items=[];
        $top=[];
        foreach($menus as $i=>$menu){
            if($menu['parent_id']==0){
                $top[$menu['id']]=$menu;
                unset($menus[$i]);
            }
        }
        foreach($top as $t){
            if(!$t['has_child']){
                $items[]=['label'=>$t['name'],'url'=>$t['url']];
            }else{
                $item=['label'=>$t['name']];
                foreach($menus as $i=>$m){
                    if($m['parent_id']==$t['id'] || in_array($t['id'],explode(',',$m['path']))!==false){
                        $item['items'][]=['label'=>$m['name'],'url'=>$m['url']];
                        unset($menus[$i]);
                    }
                }
                $items[]=$item;
            }
        }
        /** @var \ricefox\setting\Setting $setting */
        $setting=\Yii::$app->get('setting');
        $config=['brandLabel'=>$setting['site']['name'],'brandUrl'=>$setting['site']['url']];
        $config['renderInnerContainer']=false;
        $config['options']=['class'=>'navbar navbar-default ricefox-navbar'];
        NavBar::begin($config);
        echo Nav::widget([
            'items'=>$items,
            'options'=>['class'=>'navbar-nav']
        ]);
        NavBar::end();
    }
}