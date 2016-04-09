<?php

namespace ricefox\article\controllers;

use ricefox\article\models\ArticleData;
use Yii;
use ricefox\article\models\Article;
use ricefox\article\models\ArticleSearch;
use ricefox\base\BackendController;
use ricefox\block\models\Category;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * AdminController implements the CRUD actions for Article model.
 */
class AdminController extends BackendController
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
            ],
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $categories=Category::getItems();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categories'=>$categories
        ]);
    }

    /**
     * 返回文章的搜索数据。通过view参数使用不同的显示模版。
     *
     * @return string
     */
    function actionSearch()
    {
        $searchModel=new ArticleSearch();
        $provider=$searchModel->search(Yii::$app->request->get());
        //print_r($searchModel->attributes);
        $categories=Category::getItems();
        return $this->render('index',['dataProvider'=>$provider,'searchModel'=>$searchModel,'categories'=>$categories]);
    }

    /**
     * Displays a single Article model.
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
     * 创建文章
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Article();
        $model->loadDefaultValues();
        $articleData=new ArticleData();
        $articleData->loadDefaultValues();
        $request=Yii::$app->request;
        if ($request->isPost) {
            $post=$request->post();
            $model->load($post);
            $articleData->load($post);
            $model->content=$articleData->content;
            if($model->validate() && $articleData->validate() && $model->saveArticle($articleData)){
                $this->success('add');
                return $this->refresh();
            }
        }
        $categories=Category::getItems();
        return $this->render('create', [
            'model' => $model,
            'categories'=>$categories,
            'articleData'=>$articleData
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
            $model=new Article();
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
        $model=new Article();
        // 通过 ModelKit Behavior中的方法删除多行。
        if($model->deleteMultiRows($keys)){
            \Yii::$app->session->setFlash('success','删除成功');
        }else{
            \Yii::$app->session->setFlash('error','删除失败');
        }
        return $this->goBack(Url::to(['list']));
    }


    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /** @var Article $model */
        $model = $this->findModel($id);
        /** @var ArticleData $articleData */
        $articleData=ArticleData::findOne($id);
        $request=Yii::$app->request;
        if ($request->isPost) {
            $post=$request->post();
            $model->load($post);
            $articleData->load($post);
            $model->content=$articleData->content;
            if($model->validate() && $articleData->validate() && $model->saveArticle($articleData)){
                $this->success('update');
                return $this->refresh();
            }
        }
        $categories=Category::getItems();
        return $this->render('update', [
            'model' => $model,
            'categories'=>$categories,
            'articleData'=>$articleData
        ]);
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Article::deleteById($id)){
            \Yii::$app->session->setFlash('success','删除成功');
        }else{
            \Yii::$app->session->setFlash('error','删除失败');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
