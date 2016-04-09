<?php

namespace ricefox\setting\controllers;

use Yii;
use ricefox\setting\models\Setting;
use ricefox\setting\models\SettingSearch;
use ricefox\setting\models\SettingGroup;
use ricefox\base\BackendController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * SettingController implements the CRUD actions for Setting model.
 */
class SettingController extends BackendController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'multi-update'=>['post'],
                    'multi-delete'=>['post']
                ],
            ]
        ];
    }

    /**
     * Lists all Setting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SettingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $groupItems=SettingGroup::getItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'groupItems'=>$groupItems
        ]);
    }

    /**
     * Displays a single Setting model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Setting model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Setting();
        $request=Yii::$app->request;
        if($request->isPost){
            $model->load(Yii::$app->request->post());
            $values=$request->post('value');
            switch($model->type)
            {
                case 'input':
                case 'number':
                    $val=$values['input'];
                    break;
                case 'textarea':
                    $val=$values['textarea'];
                    break;
                default:
                    $value=$values['group'];
                    $n=$value['name'];
                    $v=$value['value'];
                    $array=[];
                    foreach($n as $i=>$item){
                        if(trim($item)!=='' && isset($v[$i]) && trim($v[$i])!==''){
                            $array[$item]=$v[$i];
                        }
                    }
                    if($array){
                        $val=json_encode($array);
                    }
            }
            if(isset($val) && $val){
                $model->value=$val;
            }
            if($model->save()){
                $this->success('add');
                return $this->goBack(['index']);
            }else{
                $this->failed('add');
            }
        }
        $groupItems=SettingGroup::getItems();
        $types=Setting::getTypes();
        return $this->render('create', [
            'model' => $model,
            'groupItems'=>$groupItems,
            'types'=>$types
        ]);
    }


    /**
     * 单表的多行更新
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \yii\db\Exception
    */
    function actionMultiUpdate()
    {
        //通过DataFetch Behavior中的方法获取需要更新的数据行，
        $array=$this->getMultiRows();
        if($array){
            $model=new Setting();
            if($model->updateMultiRows($array)){
                \Yii::$app->session->setFlash('success','操作成功');
            }else{
                \Yii::$app->session->setFlash('error','操作失败 '.$model->getErrorString());
            }
        }
        return $this->goBack(Url::to(['index']));
    }


    /**
     * 单表的多行删除
     * @return \yii\web\Response
     * @thrown \yii\base\Exception
    */
    function actionMultiDelete()
    {
        //通过DataFetch Behavior中的方法获取需要删除的主键数组，
        $keys=$this->getKeys();
        $model=new Setting();
        // 通过 ModelKit Behavior中的方法删除多行。
        if($model->deleteMultiRows($keys)){
            \Yii::$app->session->setFlash('success','删除成功');
        }else{
            \Yii::$app->session->setFlash('error','删除失败');
        }
        return $this->goBack(Url::to(['index']));
    }


    /**
     * Updates an existing Setting model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $request=Yii::$app->request;
        if($request->isPost){
            $model->load(Yii::$app->request->post());
            $values=$request->post('value');
            switch($model->type)
            {
                case 'input':
                case 'number':
                    $val=$values['input'];
                    break;
                case 'textarea':
                    $val=$values['textarea'];
                    break;
                default:
                    $value=$values['group'];
                    $n=$value['name'];
                    $v=$value['value'];
                    $array=[];
                    foreach($n as $i=>$item){
                        if(trim($item)!=='' && isset($v[$i]) && trim($v[$i])!==''){
                            $array[$item]=$v[$i];
                        }
                    }
                    if($array){
                        $val=json_encode($array);
                    }
            }
            if(isset($val) && $val){
                $model->value=$val;
            }
            if($model->save()){
                $this->success('update');
                return $this->goBack(['index']);
            }else{
                $this->failed('update');
            }
        }
        $groupItems=SettingGroup::getItems();
        $types=Setting::getTypes();
        return $this->render('update', [
            'model' => $model,
            'groupItems'=>$groupItems,
            'types'=>$types
        ]);
    }

    /**
     * Deletes an existing Setting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if($this->findModel($id)->delete()!==false){
            $this->success('delete');
        }else{
            $this->failed('delete');
        }
        return $this->goBack(['index']);
    }

    /**
     * Finds the Setting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Setting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Setting::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
