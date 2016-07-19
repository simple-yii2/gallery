<?php

use yii\helpers\Html;

$title = Yii::t('gallery', 'Create gallery');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	['label' => Yii::t('gallery', 'Galleries'), 'url' => ['index']],
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render('_form', [
	'model' => $model,
]) ?>
