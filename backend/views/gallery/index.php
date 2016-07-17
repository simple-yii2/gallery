<?php

use yii\helpers\Html;
use yii\grid\GridView;

use gallery\common\models\GallerySection;

$title = Yii::t('gallery', 'Galleries');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<div class="btn-toolbar" role="toolbar">
	<?= Html::a(Yii::t('gallery', 'Create'), ['create'], ['class' => 'btn btn-primary']) ?>
</div>

<?= GridView::widget([
	'dataProvider' => $dataProvider,
	'tableOptions' => ['class' => 'table table-condensed'],
	'rowOptions' => function ($model, $key, $index, $grid) {
		return !$model->active ? ['class' => 'warning'] : [];
	},
	'columns' => [
		'title',
		[
			'class' => 'yii\grid\ActionColumn',
			'options' => ['style' => 'width: 50px;'],
			'template' => '{update} {delete}',
		],
	],
]) ?>
