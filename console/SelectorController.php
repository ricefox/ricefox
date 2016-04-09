<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/1
 * Time: 14:28
 */

namespace ricefox\console;
use Yii;
use yii\base\Exception;

class SelectorController extends \yii\console\Controller
{
    public function actionExtract()
    {
        $doc=new \DOMDocument();
        $doc->formatOutput=true;
        $path=Yii::getAlias('@ricefox/console');
        $doc->load($path.'/html.php');
        $links=$doc->getElementsByTagName('p');
        $tops=[];
        foreach($links as $link){
            $tops[]=[$link->textContent];
        }
        $tpl=file_get_contents($path.'/tpl.php');
        $html=file_get_contents($path.'/menu.php');
        $tpl=strtr($tpl,['{body}'=>$html]);
        $doc->loadHTML($tpl);
        $finder=new \DOMXPath($doc);
        $class='child_item';
        $nodes=$finder->query("//*[contains(@class,'$class')]");

        foreach($nodes as $i=>$node){
            $groups=[];
            $dl=$node->getElementsByTagName('dl');
            foreach($dl as $n=>$o){
                $group=[];
                $dt=$o->getElementsByTagName('dt')[0];
                $group['name']=$dt->nodeValue;
                $dd=$o->getElementsByTagName('dd')[0];
                $as=$dd->getElementsByTagName('a');
                $arr=[];
                foreach($as as $a){
                    $arr[]=$a->nodeValue;
                }
                $group['values']=$arr;
                $groups[]=$group;
            }
            $tops[$i]['values']=$groups;
        }
        file_put_contents('job.php',"<?php \n return ".var_export($tops,true).';');
    }

    public function actionAdd()
    {
        $path='@ricefox/console/job.php';
        $path=Yii::getAlias($path);
        $tops=require($path);
        $db=Yii::$app->db;
        $trans=$db->beginTransaction();
        try{
            $command1=$db->createCommand('insert into job_group (name,parent_id) VALUES (:name,:parent_id)');
            $command2=$db->createCommand('insert into job (group_id,name) VALUES (:group_id,:name)');
            foreach($tops as $top){
                $g1=$top[0];
                $command1->bindValues([':name'=>$g1,':parent_id'=>0]);
                $command1->execute();
                $gid=$db->lastInsertID;
                $groups=$top['values'];
                foreach($groups as $group){
                    $g2=$group['name'];
                    $command1->bindValues([':name'=>$g2,':parent_id'=>$gid]);
                    $command1->execute();
                    $gid2=$db->lastInsertID;
                    $jobs=$group['values'];
                    foreach($jobs as $job){
                        $command2->bindValues([':group_id'=>$gid2,':name'=>$job]);
                        $command2->execute();
                    }
                }
            }
            $trans->commit();
        }catch (Exception $e){
            $trans->rollBack();
            throw $e;
        }

    }

    public function actionTest()
    {
        $doc=new \DOMDocument();
        $doc->formatOutput=true;
        $path=Yii::getAlias('@ricefox/console');
        $doc->load($path.'/html.php');
        $links=$doc->getElementsByTagName('li');
        $link=$links[0];
        $text=$this->getText($link);
        file_put_contents('text.txt',$text);
        echo $text;;

        //print_r($link->nodeType);
        //print_r($link->childNodes);
    }
    public function getText(\DOMElement $element)
    {
        echo $element->nodeName.PHP_EOL;
        $childNodes=$element->childNodes;
        $text='';
        foreach($childNodes as $node){
            if($node->nodeType==1){
                $text.=$this->getText($node);
            }else if($node->nodeType==3){
                $t=trim($node->nodeValue);
                echo mb_detect_encoding($t);
                if($t) $text.=$t;
            }
        }
        return $text;
    }

    function DOMInnerHTML(\DOMNode $element)
    {
        $innerHTML = "";
        $children  = $element->childNodes;

        foreach ($children as $child)
        {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }
}