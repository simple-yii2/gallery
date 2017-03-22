<?php

use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;

use dkhlystov\widgets\Lightbox;
use cms\gallery\frontend\assets\GalleryAsset;

GalleryAsset::register($this);

$dataProvider = new ArrayDataProvider([
	'allModels' => $model->images,
	'pagination' => false,
]);

$title = $model->title;

$this->title = $title . ' | ' . Yii::$app->name;

Yii::$app->params['breadcrumbs'] = [
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<?php Lightbox::begin(); ?>

<?= ListView::widget([
	'dataProvider' => $dataProvider,
	'layout' => '{items}',
	'options' => ['class' => 'row gallery'],
	'itemOptions' => ['class' => 'col-sm-4 col-md-3'],
	'itemView' => function($model, $key, $index, $widget) {
		$title = '';
		if (!empty($model->title))
			$title = Html::tag('span', Html::encode($model->title), ['class' => 'gallery-title']);

		$caption = '';
		if (!empty($caption))
			$caption = Html::tag('span', $title, ['class' => 'gallery-caption']);

		$image = Html::img($model->thumb);

		return Html::a($image . $caption, $model->file);
	},
]) ?>

<?php Lightbox::end(); ?>
