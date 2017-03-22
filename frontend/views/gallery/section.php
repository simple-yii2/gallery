<?php

use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;

use cms\gallery\common\models\GalleryItem;
use cms\gallery\frontend\assets\GalleryAsset;

GalleryAsset::register($this);

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

<?= ListView::widget([
	'dataProvider' => $dataProvider,
	'layout' => '{items}',
	'options' => ['class' => 'row gallery'],
	'itemOptions' => ['class' => 'col-sm-4 col-md-3'],
	'itemView' => function($model, $key, $index, $widget) {
		$count = '';
		if ($model instanceof GalleryItem)
			$count = Html::tag('span', $model->imageCount, ['class' => 'gallery-count']);

		$title = Html::tag('span', Html::encode($model->title), ['class' => 'gallery-title']);

		$caption = Html::tag('span', $count . $title, ['class' => 'gallery-caption']);

		$image = Html::img($model->thumb);

		return Html::a($image . $caption, ['', 'alias' => $model->alias]);
	},
]) ?>
