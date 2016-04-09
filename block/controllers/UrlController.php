<?php

namespace ricefox\block\controllers;

use Yii;
use ricefox\block\models\UrlModel;
use ricefox\block\models\UrlModelSearch;
use ricefox\base\BackendController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
/**
 * UrlController implements the CRUD actions for UrlModel model.
 */
class UrlController extends BackendController
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
     * Lists all UrlModel models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UrlModelSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Displays a single UrlModel model.
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
     * 创建一行 UrlModel model.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new UrlModel();
        $model->loadDefaultValues();
        $request=Yii::$app->request;
        if ($request->isPost) {
            $post=$request->post();
            $model->load($post);
            if($model->save()){
                $this->success('add');
                return $this->goBack(['index']);
            }else{
                $this->failed('add');
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    /**
     * Updates an existing UrlModel model.
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
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    /**
     * Deletes an existing UrlModel model.
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
     * Finds the UrlModel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UrlModel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UrlModel::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
