<?php

use yii\helpers\Html;

$title = $model->title;

$this->title = $title . ' | ' . Yii::$app->name;

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render($model->type == $model::TYPE_SECTION ? 'index/section' : 'index/collection', [
	'model' => $model,
]) ?>
