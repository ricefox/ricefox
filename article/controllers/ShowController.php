<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/6
 * Time: 16:23
 */

namespace ricefox\article\controllers;

use Yii;
use ricefox\article\models\Show;
use ricefox\components\ContentPagination;
use ricefox\block\models\Category;

class ShowController extends \ricefox\base\FrontendController
{

    public function actionContent($id)
    {
        $article=Show::article($id);
        if(!$article){
            throw new \yii\web\NotFoundHttpException();
        }
        $prev=Show::prevArticle($article['created_at'],$article['category_id']);
        $next=Show::nextArticle($article['created_at'],$article['category_id']);
        $return=['prev'=>$prev,'next'=>$next];
        // 内容中如含有[page]分页标记，对内容进行分页
        if($article['paging']==1){
            $request=Yii::$app->request;
            $page=$request->get('page');
            $sep=$request->get('sep');
            $url=$request->getAbsoluteUrl();
            $search=$id.$sep.($sep ? $page : '');
            $urlTpl=str_replace($search,$id.'_{page}',$url);
            $return ['pages']=ContentPagination::run($article['content'],$urlTpl,$page);
        }else{
            $return ['pages']='';
        }
        $return['article']=$article;
        $this->getCategory($article['category_id']);

        return $this->renderDevice('content',$return);
    }
    public function getCategory($categoryId=0)
    {
        if(!$categoryId){
            $categoryId=Yii::$app->request->get('targetId',0);
        }
        if($categoryId){
            $this->getView()->category=Category::getCategory($categoryId);
        }
    }

    public function actionCategory()
    {
        $request=Yii::$app->request;
        $this->getCategory();
        $category=$this->getView()->category;
        if(strpos($category['showId'],',')===false){
            $where=' category_id='.trim($category['id']);
        }else{
            $where=' category_id in ('.$category['showId'].')';
        }
        $query=(new \yii\db\Query())->from('article')->where($where);
        $count=$query->count();
        // 分页
        if($request->get('page')){
            $page=$request->get('page')-1;
        }else{
            $page=0;
        }
        $pagination=new \ricefox\components\Pagination(['totalCount'=>$count,'page'=>$page]);
        $articles=$query->orderBy('created_at desc')->offset($pagination->offset)->limit($pagination->limit)
            ->all();
        $url=$request->getHostInfo().'/'.$request->get('pathInfo').'/{page}';
        if(($queryString=$request->getQueryString())){
            $url.='?'.$queryString;
        }
        $pages=$pagination->show($url);
        $this->view->params['top']=Show::top(Show::POS_CATEGORY,$category);
        return $this->renderDevice('category',['articles'=>$articles,'pages'=>$pages]);
    }
}