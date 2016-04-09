<?php

namespace ricefox\block\controllers;

use Yii;
use ricefox\block\models\AdminMenu;
use ricefox\block\models\AdminMenuSearch;
use ricefox\base\BackendController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
/**
 * AdminMenuController implements the CRUD actions for AdminMenu model.
 */
class AdminMenuController extends BackendController
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
     * Lists all AdminMenu models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AdminMenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdminMenu model.
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
     * Creates a new AdminMenu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AdminMenu();
        $model->loadDefaultValues();
        $request=Yii::$app->request;
        if ($model->load($request->post())) {
            $result=$this->save($model);
            if($result===true){
                $this->success('add');
                return $this->goBack(['index']);
            }else{
                if($result===false){
                    $this->failed('add');
                }else{
                    $this->failed('add',$result);
                }
            }
        }
        $parents=$model->getParents();
        $parents[1]=[];//添加菜单应去掉disabled
        //print_r($this->layouts);
        return $this->render('create', [
            'model' => $model,
            'parents'=>$parents
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
            $model=new AdminMenu();
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
        $model=new AdminMenu();
        // 通过 ModelKit Behavior中的方法删除多行。
        if($model->deleteMultiRows($keys)){
            \Yii::$app->session->setFlash('success','删除成功');
        }else{
            \Yii::$app->session->setFlash('error','删除失败');
        }
        return $this->goBack(Url::to(['list']));
    }


    /**
     * Updates an existing AdminMenu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->old_parent_id=$model->parent_id;
        $request=Yii::$app->request;
        if ($model->load($request->post())) {
            if($model->old_parent_id!=$model->parent_id){
                $model->parentIdChanged=true;
            }
            $result=$this->save($model);
            if($result===true){
                $this->success('update');
                return $this->goBack(['index']);
            }else{
                if($result===false){
                    $this->failed('update');
                }else{
                    $this->failed('update',$result);
                }
            }
        }
        $parents=$model->getParents();
        return $this->render('update', [
            'model' => $model,
            'parents'=>$parents
        ]);

    }

    /**
     * Deletes an existing AdminMenu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AdminMenu model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminMenu the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminMenu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
