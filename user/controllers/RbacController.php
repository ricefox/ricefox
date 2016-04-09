<?php

namespace ricefox\user\controllers;

use ricefox\user\models\AuthItemForm;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\helpers\Url;
use Yii;

/**
 * BackendUserController implements the CRUD actions for User model.
 */
class RbacController extends \ricefox\base\BackendController
{
    const TYPE_ROLE='Role';
    const TYPE_PERMISSION='Permission';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            /*'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['user manage'],
                    ],
                ],
            ],*/
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'remove-items' => ['post'],
                ],
            ],

        ];
    }

    /**
     * @param integer $type
     * @return mixed
     */
    public function actionIndex($type=null)
    {
        if(!$type)$type=Item::TYPE_ROLE;
        if($type==Item::TYPE_ROLE){
            $data = new ArrayDataProvider(
                [
                    'id' => 'roles',
                    'allModels' => \Yii::$app->getAuthManager()->getRoles(),
                    'sort' => [
                        'attributes' => ['name', 'createdAt', 'updatedAt'],
                    ],
                    'pagination' => [
                        'pageSize' => 10,
                    ],
                ]
            );
        }
        else
        {
            $data= new ArrayDataProvider(
                [
                    'id' => 'permissions',
                    'allModels' => \Yii::$app->getAuthManager()->getPermissions(),
                    'sort' => [
                        'attributes' => ['name', 'createdAt', 'updatedAt'],
                    ],
                    'pagination' => [
                        'pageSize' => 20,
                    ],
                ]
            );
        }

        return $this->render(
            'index',
            [
                'data' => $data,
                'type'=>$type
            ]
        );
    }

    /**
     * Creates a new AuthItem model.
     * @param $type
     * @return string|\yii\web\Response
     * @throws \InvalidArgumentException
     */
    public function actionCreate($type)
    {
        //$rules = ArrayHelper::map(\Yii::$app->getAuthManager()->getRules(), 'name', 'name');
        $model = new AuthItemForm(['isNewRecord' => true]);
        $typeString=$type==Item::TYPE_ROLE?self::TYPE_ROLE:self::TYPE_PERMISSION;
        if ($model->load($_POST) && $model->validate()) {
            $item = $model->createItem();
            if (strlen($model->getErrorMessage()) > 0)
            {
                \Yii::$app->getSession()->setFlash('error', $model->getErrorMessage());
            }
            else
            {
                Yii::$app->session->setFlash('success', Yii::t('rf', 'Your submit has been saved'));
                return $this->goBack(Url::toRoute(['index','type'=>$type]));
            }
        }
        switch ($type) {
            case Item::TYPE_PERMISSION:
                $model->type = Item::TYPE_PERMISSION;
                $items = ArrayHelper::map(
                    \Yii::$app->getAuthManager()->getPermissions(),
                    'name',
                    function ($item) {
                        return $item->name.(strlen($item->description) > 0 ? ' ['.$item->description.']' : '');
                    }
                );
                break;
            case Item::TYPE_ROLE:
                $model->type = Item::TYPE_ROLE;
                $items = ArrayHelper::map(
                    ArrayHelper::merge(
                        \Yii::$app->getAuthManager()->getPermissions(),
                        \Yii::$app->getAuthManager()->getRoles()
                    ),
                    'name',
                    function ($item) {
                        return $item->name.(strlen($item->description) > 0 ? ' ['.$item->description.']' : '');
                    },
                    function ($item) {
                        return $item->type==Item::TYPE_ROLE?'Role':'Permission';
                    }
                );
                break;
            default:
                throw new \InvalidArgumentException('Unexpected item type');
        }
        return $this->render(
            'create',
            [
                'model' => $model,
                'items' => $items,
                'typeString'=>$typeString
            ]
        );

    }

    /**
     * 更新角色或权限
     * @param $id string 角色名|权限名
     * @param $type integer Item::TYPE_ROLE|Item::TYPE_PERMISSION 类型，表明是角色还是权限
     * @return string|\yii\web\Response
     * @throws \InvalidArgumentException
     */
    public function actionUpdate($id, $type)
    {
        //$rules = ArrayHelper::map(\Yii::$app->getAuthManager()->getRules(), 'name', 'name');
        $model = new AuthItemForm;
        $isPost=Yii::$app->request->isPost;
        if ($model->load($_POST) && $model->validate())
        {
            $item = $model->updateItem();
            if (strlen($model->getErrorMessage()) > 0) {
                \Yii::$app->getSession()->setFlash('error', $model->getErrorMessage());
            }
            else
            {
                Yii::$app->session->setFlash('success',Yii::t('rf','Your submit has been saved'));
                return $this->goBack(['index','type'=>$type]);
            }
        }
        if($type==Item::TYPE_PERMISSION){
            $items = ArrayHelper::map(
                \Yii::$app->getAuthManager()->getPermissions(),
                'name',
                function ($item) {
                    return $item->name.(strlen($item->description) > 0 ? ' ['.$item->description.']' : '');
                }
            );
            if(!$isPost)$item=Yii::$app->getAuthManager()->getPermission($id);
        }else{
            $items = ArrayHelper::map(
                ArrayHelper::merge(
                    \Yii::$app->getAuthManager()->getPermissions(),
                    \Yii::$app->getAuthManager()->getRoles()
                ),
                'name',
                function ($item) {
                    return $item->name.(strlen($item->description) > 0 ? ' ['.$item->description.']' : '');
                },
                function ($item) {
                    return $item->type==Item::TYPE_ROLE?self::TYPE_ROLE:self::TYPE_PERMISSION;
                }
            );
            if(!$isPost)$item = Yii::$app->getAuthManager()->getRole($id);
        }

        $children = \Yii::$app->getAuthManager()->getChildren($id);
        $selected = [];
        foreach ($children as $child) {
            $selected[] = $child->name;
        }
        if(!$isPost){
            $model->name = $item->name;
            $model->oldname = $item->name;
            $model->type = $item->type;
            $model->description = $item->description;
            $model->ruleName = $item->ruleName;
        }
        $model->children=$selected;
        $typeString=$type==Item::TYPE_ROLE?self::TYPE_ROLE:self::TYPE_PERMISSION;
        return $this->render(
            'update',
            [
                'model' => $model,
                'items' => $items,
                'typeString'=>$typeString
            ]
        );

    }

    /**
     * 删除多个角色或权限
     * @return \yii\web\Response
     */
    public function actionMultiDelete()
    {
        $keys=$this->getKeys();
        $state=true;
        foreach($keys as $key){
            if(!Yii::$app->getAuthManager()->remove(new Item(['name' => $key]))){
                $this->deleteFailed();
                $state=false;
                break;
            }
        }
        if($state)$this->deleteSuccess();
        return $this->goBack(['index']);
    }

    public function actionRemoveItems()
    {
        foreach ($_POST['items'] as $item) {
            \Yii::$app->getAuthManager()->remove(new Item(['name' => $item]));
        }
    }

    /**
     *
     * @param string $id 角色或权限名
     * @return mixed
     */
    public function actionDelete($id)
    {
        if(Yii::$app->getAuthManager()->remove(new Item(['name' => $id]))){
            $this->deleteSuccess();
        }else{
            $this->deleteFailed();
        }
        return $this->goBack(['index']);
    }
}
