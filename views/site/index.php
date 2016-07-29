<?php
/* @var $this yii\web\View */

use app\models\Project;

$this->title = 'NP Public Camera';
?>
<div class="site-index">
    <!--    <div class="jumbotron">-->
    <!--        <h1>Congratulations!</h1>-->
    <!---->
    <!--        <p class="lead">You have successfully created your Yii-powered application.</p>-->
    <!---->
    <!--        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>-->
    <!--    </div>-->
    <!---->
    <!--    <p>List of Projects</p>-->

    <div class="body-content">

        <?php
        $projects = Project::findAll(['status' => 1]);
        foreach ($projects as $project) {
            ?>
            <div class="row">
                <div class="col-lg-4">
                    <h2><?= $project->label ?></h2>

                    <p><?= $project->remark ?></p>

                    <p><a class="btn btn-default" href=<?= "project/" . $project->id ?>>Find Out &raquo;</a></p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
