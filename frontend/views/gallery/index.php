<?php

use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;

use dkhlystov\widgets\Lightbox;
use cms\gallery\frontend\assets\CollectionAsset;

CollectionAsset::register($this);

$dataProvider = new ArrayDataProvider([
	'allModels' => $model->children(1)->andWhere(['active' => true])->all(),
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
	'itemOptions' => ['class' => 'col-sm-6 col-md-4'],
	'itemView' => function($model, $key, $index, $widget) {
		if ($model->imageCount > 1) {
			$count = '<span>' . $model->imageCount . '</span>';
		} else {
			$count = '';
		}

		$result = '';
		foreach ($model->images as $image) {
			$result .= Html::a(Html::img($image->thumb) . $count, $image->file);
		}

		if (!empty($model->title))
			$result .= Html::tag('h4', Html::encode($model->title));

		if (!empty($model->description))
			$result .= Html::tag('div', Html::encode($model->description), ['class' => 'description']);

		return $result;
	},
]) ?>

<?php Lightbox::end(); ?>
