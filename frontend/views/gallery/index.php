<?php

use yii\helpers\Html;

$title = $model->title;

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	$title,
];

echo Html::tag('h1', Html::encode($title));

echo $this->render($model->type == $model::TYPE_SECTION ? 'index/section' : 'index/collection', [
	'model' => $model,
]);
