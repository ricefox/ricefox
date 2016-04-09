<?php

namespace ricefox\block\controllers;

use Yii;
use ricefox\block\models\Category;
use ricefox\block\models\UrlModel;
use ricefox\block\models\CategroySearch;
use ricefox\block\models\Module;
use ricefox\base\BackendController;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends BackendController
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CategroySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();
        $model->loadDefaultValues();
        $urlModel=new UrlModel();
        $urlModel->loadDefaultValues();
        $request=Yii::$app->request;
        if ($request->isPost) {
            $post=$request->post();
            $urlModel->load($post);
            $urlModel->target_id=0;
            $model->load($post);
            $trans=$model->getDb()->beginTransaction();
            try{
                if(!$urlModel->save()){
                    $trans->rollBack();
                    $error=$urlModel->getErrorString();
                    $this->failed('add',$error);
                }else{
                    $model->url=$urlModel->url;
                    if(!$model->save()){
                        $trans->rollBack();
                        $error=$urlModel->getErrorString();
                        $this->failed('add',$error);
                    }else{
                        $urlModel->target_id=$model->id;
                        $urlModel->save();
                        $trans->commit();
                        $this->success('add');
                        return $this->goBack(['index']);
                    }
                }
            }catch(\Exception $e){
                $trans->rollBack();
                throw $e;
            }

        }

        $modules=Module::getItems();
        return $this->render('create', [
            'model' => $model,
            'modules'=>$modules,
            'urlModel'=>$urlModel
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /** @var Category $model */
        $model = $this->findModel($id);
        $model->old_parent_id=$model->parent_id;
        /** @var UrlModel $urlModel */
        $urlModel=UrlModel::findOne(['target_id'=>$model->id]);
        $request=Yii::$app->request;
        if ($request->isPost) {
            $post=$request->post();
            $urlModel->load($post);
            $model->load($post);
            $trans=$model->getDb()->beginTransaction();
            try{
                if(!$urlModel->save()){
                    $trans->rollBack();
                    $error=$urlModel->getErrorString();
                    $this->failed('update',$error);
                }else{
                    $model->url=$urlModel->url;
                    if(!$model->save()){
                        $trans->rollBack();
                        $error=$urlModel->getErrorString();
                        $this->failed('update',$error);
                    }else{
                        $trans->commit();
                        $this->success('update');
                        return $this->goBack(['index']);
                    }
                }
            }catch(\Exception $e){
                $trans->rollBack();
                throw $e;
            }
        }
        $modules=Module::getItems();
        return $this->render('update', [
            'model' => $model,
            'modules'=>$modules,
            'urlModel'=>$urlModel
        ]);
    }

    public function actionGetParents($moduleId,$id=0)
    {
        $model=new Category();
        if($id>0){
            $model->id=$id;
        }
        $module=Module::findOne($moduleId);
        $parents=$model->getParents($module['class_id']);
        //$parents[0]=[0=>'请选择父栏目']+$parents[0];
        return $this->renderPartial('_parents',[
            'parents'=>$parents,
            'model'=>$model
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
            $model=new Category();
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
        $model=new Category();
        // 通过 ModelKit Behavior中的方法删除多行。
        if($model->deleteMultiRows($keys)){
            \Yii::$app->session->setFlash('success','删除成功');
        }else{
            \Yii::$app->session->setFlash('error','删除失败');
        }
        return $this->goBack(Url::to(['list']));
    }




    /**
     * Deletes an existing Category model.
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
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
