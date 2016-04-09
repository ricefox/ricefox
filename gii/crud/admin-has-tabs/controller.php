<?php
/**
 * This is the template for generating a CRUD controller class file.
 */

use yii\db\ActiveRecordInterface;
use yii\helpers\StringHelper;


/* @var $this yii\web\View */
/* @var $generator ricefox\gii\crud\Generator */

$controllerClass = StringHelper::basename($generator->controllerClass);
$modelClass = StringHelper::basename($generator->modelClass);
$searchModelClass = StringHelper::basename($generator->searchModelClass);
if ($modelClass === $searchModelClass) {
    $searchModelAlias = $searchModelClass . 'Search';
}
$actions=$generator->getActions();
/* @var $class ActiveRecordInterface */
$class = $generator->modelClass;
$pks = $class::primaryKey();
$urlParams = $generator->generateUrlParams();
$actionParams = $generator->generateActionParams();
$actionParamComments = $generator->generateActionParamComments();

echo "<?php\n";
?>

namespace <?= StringHelper::dirname(ltrim($generator->controllerClass, '\\')) ?>;

use Yii;
use <?= ltrim($generator->modelClass, '\\') ?>;
<?php if (!empty($generator->searchModelClass)): ?>
use <?= ltrim($generator->searchModelClass, '\\') . (isset($searchModelAlias) ? " as $searchModelAlias" : "") ?>;
<?php else: ?>
use yii\data\ActiveDataProvider;
<?php endif; ?>
use <?= ltrim($generator->baseControllerClass, '\\') ?>;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
/**
 * <?= $controllerClass ?> implements the CRUD actions for <?= $modelClass ?> model.
 */
class <?= $controllerClass ?> extends <?= StringHelper::basename($generator->baseControllerClass) . "\n" ?>
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
     * Lists all <?= $modelClass ?> models.
     * @return mixed
     */
    public function actionIndex()
    {
<?php if (!empty($generator->searchModelClass)): ?>
        $searchModel = new <?= isset($searchModelAlias) ? $searchModelAlias : $searchModelClass ?>();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
<?php else: ?>
        $dataProvider = new ActiveDataProvider([
            'query' => <?= $modelClass ?>::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
<?php endif; ?>
    }
<?php if(in_array('view',$actions)){ ?>
    /**
     * Displays a single <?= $modelClass ?> model.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionView(<?= $actionParams ?>)
    {
        return $this->render('view', [
            'model' => $this->findModel(<?= $actionParams ?>),
        ]);
    }
<?php } ?>
<?php if(in_array('create',$actions)){ ?>
    /**
     * 创建一行 <?= $modelClass ?> model.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new <?= $modelClass ?>();
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
<?php } ?>
<?php if(in_array('update',$actions)){ ?>
    /**
     * Updates an existing <?= $modelClass ?> model.
     *
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionUpdate(<?= $actionParams ?>)
    {
        $model = $this->findModel(<?= $actionParams ?>);
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
<?php } ?>
<?php if(in_array('delete',$actions)){ ?>
    /**
     * Deletes an existing <?= $modelClass ?> model.
     *
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return mixed
     */
    public function actionDelete(<?= $actionParams ?>)
    {
        if($this->findModel(<?= $actionParams ?>)->delete()){
            $this->success('delete');
        }else{
            $this->failed('delete');
        }
        return $this->goBack(['index']);
    }
<?php } ?>
    /**
     * Finds the <?= $modelClass ?> model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * <?= implode("\n     * ", $actionParamComments) . "\n" ?>
     * @return <?=                   $modelClass ?> the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(<?= $actionParams ?>)
    {
<?php
if (count($pks) === 1) {
    $condition = '$id';
} else {
    $condition = [];
    foreach ($pks as $pk) {
        $condition[] = "'$pk' => \$$pk";
    }
    $condition = '[' . implode(', ', $condition) . ']';
}
?>
        if (($model = <?= $modelClass ?>::findOne(<?= $condition ?>)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
<?php if(in_array('multi-update',$actions)){ ?>
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
            $model=new <?= $modelClass ?>();
            if($model->updateMultiRows($array)){
            \Yii::$app->session->setFlash('success','操作成功');
            }else{
                \Yii::$app->session->setFlash('error','操作失败 '.$model->getErrorString());
            }
        }
        return $this->goBack(Url::to(['index']));
    }
<?php } ?>
<?php if(in_array('multi-delete',$actions)){ ?>
    /**
    * 单表的多行删除
    * @return \yii\web\Response
    * @thrown \yii\base\Exception
    */
    function actionMultiDelete()
    {
        //通过DataFetch Behavior中的方法获取需要删除的主键数组，
        $keys=$this->getKeys();
        $model=new <?= $modelClass ?>();
        // 通过 ModelKit Behavior中的方法删除多行。
        if($model->deleteMultiRows($keys)){
            \Yii::$app->session->setFlash('success','删除成功');
        }else{
            \Yii::$app->session->setFlash('error','删除失败');
        }
        return $this->goBack(Url::to(['list']));
    }
<?php } ?>

}
