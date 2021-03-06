<?php

use yii\helpers\Html;

$title = Yii::t('gallery', 'Create section');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	['label' => Yii::t('gallery', 'Galleries'), 'url' => ['gallery/index']],
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render('form', [
	'model' => $model,
]) ?>
