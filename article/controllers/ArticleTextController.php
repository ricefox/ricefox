<?php

namespace ricefox\article\controllers;

use Yii;
use ricefox\article\models\ArticleText;
use ricefox\article\models\ArticleTextSearch;
use ricefox\base\BackendController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use ricefox\article\models\ArticleImageCreateSearch;
use ricefox\block\models\Category;
/**
 * ArticleTextController implements the CRUD actions for ArticleText model.
 */
class ArticleTextController extends BackendController
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
     * Lists all ArticleText models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleTextSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * 创建一行 ArticleText model.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ArticleText();
        $model->loadDefaultValues();
        $request=Yii::$app->request;
        if ($request->isPost) {
            $post=$request->post();
            $rows=$this->getMultiRows();
            if($rows){
                $result=$model->add($post,$rows);
                if($result===true){
                    $this->success('add');
                    return $this->goBack(['index']);
                }else{
                    $this->failed('add',$result);
                }
            }
        }
        $searchModel = new ArticleImageCreateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $categories=Category::getItems();
        return $this->render('create', [
            'model' => $model,
            'categories'=>$categories,
            'keyNames'=>$this->getKeyNames(),
            'positions'=>$this->getPositions(),
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
            'status'=>$this->getStatus()
        ]);
    }

    public function getKeyNames()
    {
        $keyNames=$this->module->setting['articleText.key'];
        foreach($keyNames as $key=>&$value){
            $value='['.$key.']'.$value;
        }
        return $keyNames;
    }
    public function getStatus()
    {
        $keyNames=$this->module->setting['status'];
        /*foreach($keyNames as $key=>&$value){
            $value='['.$key.']'.$value;
        }*/
        return $keyNames;
    }
    public function getPositions()
    {
        $positions=$this->module->setting['articleText.position'];
        foreach($positions as $key=>&$value){
            $value='['.$key.']'.$value;
        }
        return $positions;
    }

    /**
     * Updates an existing ArticleText model.
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
            //print_r($model->attributes);exit;
            if($model->save()){
                $this->success('update');
                return $this->goBack(['index']);
            }else{
                $this->failed('update');
            }
        }
        $categories=Category::getItems();
        return $this->render('update', [
            'model' => $model,
            'categories'=>$categories,
            'keyNames'=>$this->getKeyNames(),
            'positions'=>$this->getPositions(),
        ]);
    }
    /**
     * Deletes an existing ArticleText model.
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
     * Finds the ArticleText model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ArticleText the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ArticleText::findOne($id)) !== null) {
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
            $model=new ArticleText();
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
        $model=new ArticleText();
        // 通过 ModelKit Behavior中的方法删除多行。
        if($model->deleteMultiRows($keys)){
            \Yii::$app->session->setFlash('success','删除成功');
        }else{
            \Yii::$app->session->setFlash('error','删除失败');
        }
        return $this->goBack(Url::to(['list']));
    }

}
