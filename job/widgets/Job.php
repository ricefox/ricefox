<?php
/**
 * Created by PhpStorm.
 * User: wen
 * Date: 2016/4/3
 * Time: 11:10
 */

namespace ricefox\job\widgets;
use ricefox\widgets\Field;
use ricefox\job\models\JobGroup;
use ricefox\job\models\Job as JobModel;
use Yii;
class Job extends \yii\base\Widget
{
    /** @var  \ricefox\widgets\ActiveForm */
    public $form;
    /** @var \ricefox\base\ActiveRecord */
    public $model;
    public $label='职位';
    public $targetId='job';
    public $maxSelected=3;
    public $separator=';';
    public function run()
    {
        JobAsset::register($this->getView());
        $options=[
            'targetId'=>$this->targetId,
            'maxSelected'=>$this->maxSelected,
            'separator'=>$this->separator
        ];
        $options=json_encode($options);
        $this->getView()->registerJs("window.panelOptions=JSON.parse('$options')",\yii\web\View::POS_BEGIN);
        $jobs=JobModel::getAllJobs();
        echo "<div id=\"panel\" class='panel clear-fix hidden'>";
        echo "<div class='col-fixed' style='width: 200px;'>";
        foreach($jobs as $job){
            echo "<div class='col'><a class='panel-item col' id='panel{$job['id']}' href='#sub{$job['id']}'>{$job['name']}</a></div>";
            echo "<div class='sub-item hidden' id=\"sub{$job['id']}\">";
            foreach($job['sub'] as $sub){
                echo "<div class='sub clear-fix'>";
                echo "<div class='sub-label col-fixed'><a class=''>{$sub['name']}</a></div>";
                echo "<div class='sub-job col-auto'>";
                foreach($sub['jobs'] as $jobId=>$jobName){
                    echo "<div class='btn-link job-item col-block' data-value='$jobId'>{$jobName}</div>";
                }
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";
        }
        echo "</div>";
        echo "<div id=\"panelShow\" class='col-auto'></div>";
        echo "</div>";
    }
}