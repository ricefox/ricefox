<?php

namespace ricefox\block\controllers;

use Yii;
use ricefox\block\models\FooterLink;
use ricefox\block\models\FooterLinkSearch;
use ricefox\base\BackendController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use ricefox\block\models\Module;
use ricefox\block\models\Category;
/**
 * FooterLinkController implements the CRUD actions for FooterLink model.
 */
class FooterLinkController extends BackendController
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
     * Lists all FooterLink models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FooterLinkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 创建一行 FooterLink model.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FooterLink();
        $model->loadDefaultValues();
        $request=Yii::$app->request;
        if ($request->isPost) {
            $post=$request->post();
            $model->load($post);
            if($model->save()){
                $this->success('add');
                return $this->goBack(['index']);
            }else{
                $this->failed('add',$model->getErrorString());
            }
        }
        $modules=Module::getItems();
        return $this->render('create', [
            'model' => $model,
            'modules'=>$modules
        ]);
    }
    public function actionGetCategories($moduleId)
    {
        $model=new Category();
        $module=Module::findOne($moduleId);
        $categories=$model->getItems($module['class_id']);
        $model2=new FooterLink();
        return $this->renderPartial('_categories',[
            'categories'=>$categories,
            'model'=>$model2
        ]);
    }
    /**
     * Updates an existing FooterLink model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $request=Yii::$app->request;
        if ($request->isPost) {
            $post=$request->post();
            $model->load($post);
            if($model->save()){
                $this->success('update');
                return $this->goBack(['index']);
            }else{
                $this->failed('update');
            }
        }
        $modules=Module::getItems();
        return $this->render('update', [
            'model' => $model,
            'modules'=>$modules
        ]);
    }
    /**
     * Deletes an existing FooterLink model.
     *
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if($this->findModel($id)->delete()){
            $this->success('delete');
        }else{
            $this->failed('delete');
        }
        return $this->goBack(['index']);
    }
    /**
     * Finds the FooterLink model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FooterLink the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FooterLink::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
            $model=new FooterLink();
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
        $model=new FooterLink();
        // 通过 ModelKit Behavior中的方法删除多行。
        if($model->deleteMultiRows($keys)){
            \Yii::$app->session->setFlash('success','删除成功');
        }else{
            \Yii::$app->session->setFlash('error','删除失败');
        }
        return $this->goBack(Url::to(['list']));
    }

}
