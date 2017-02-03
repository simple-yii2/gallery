<?php

use yii\helpers\Html;

$title = $model->getObject()->title;

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	['label' => Yii::t('gallery', 'Galleries'), 'url' => ['collection/index']],
	['label' => $collection->title, 'url' => ['collection/update', 'id' => $collection->id]],
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render('form', [
	'model' => $model,
	'collection' => $collection,
]) ?>
