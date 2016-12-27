<?php

use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;

use dkhlystov\widgets\Lightbox;
use cms\gallery\frontend\assets\CollectionAsset;

CollectionAsset::register($this);

$dataProvider = new ArrayDataProvider([
	'allModels' => $model->images,
	'pagination' => false,
]);

Lightbox::begin();

echo ListView::widget([
	'dataProvider' => $dataProvider,
	'layout' => '{items}',
	'options' => ['class' => 'row gallery'],
	'itemOptions' => ['class' => 'col-sm-6 col-md-4'],
	'itemView' => function($model, $key, $index, $widget) {
		$result = Html::a(Html::img($model->thumb), $model->file);

		if (!empty($model->title))
			$result .= Html::tag('h4', Html::encode($model->title));

		if (!empty($model->description))
			$result .= Html::tag('div', Html::encode($model->description), ['class' => 'description']);

		return $result;
	},
]);

Lightbox::end();