<?php

use yii\helpers\Html;
use yii\web\JsExpression;

use dkhlystov\widgets\NestedTreeGrid;
use cms\gallery\common\models\GalleryItem;
use cms\gallery\common\models\GalleryCollection;

$title = Yii::t('gallery', 'Galleries');

$this->title = $title . ' | ' . Yii::$app->name;

$this->params['breadcrumbs'] = [
	$title,
];

?>
<h1><?= Html::encode($title) ?></h1>

<div class="btn-toolbar" role="toolbar">
	<?= Html::a(Yii::t('gallery', 'Create gallery'), ['create'], ['class' => 'btn btn-primary']) ?>
</div>

<?= NestedTreeGrid::widget([
	'dataProvider' => $dataProvider,
	'initialNode' => $initial,
	'moveAction' => ['move'],
	'showRoots' => true,
	'tableOptions' => ['class' => 'table table-condensed'],
	'pluginOptions' => [
		'onMoveOver' => new JsExpression('function (item, helper, target, position) {
			if (item.data("depth") == 0 || target.data("depth") == 0)
				return false;

			if (item.data("tree") != target.data("tree"))
				return false;

			return position != 1;
		}'),
	],
	'rowOptions' => function ($model, $key, $index, $grid) {
		$options = ['data' => [
			'depth' => $model->depth,
			'tree' => $model->tree,
		]];

		if (!$model->active)
			Html::addCssClass($options, 'warning');

		return $options;
	},
	'columns' => [
		[
			'attribute' => 'title',
			'header' => Yii::t('gallery', 'Title'),
			'format' => 'html',
			'value' => function($model, $key, $index, $column) {
				$result = '';

				if (($model instanceof GalleryItem) && !empty($model->thumb))
					$result .= Html::img($model->thumb, ['height' => 20]) . '&nbsp;';

				$result .= Html::encode($model->title);

				if (($model instanceof GalleryItem) && $model->imageCount > 1)
					$result .= '&nbsp;' . Html::tag('span', $model->imageCount, ['class' => 'badge']);

				return $result;
			}
		],
		[
			'class' => 'yii\grid\ActionColumn',
			'options' => ['style' => 'width: 75px;'],
			'template' => '{update} {delete} {create}',
			'buttons' => [
				'create' => function($url, $model, $key) {
					if (!($model instanceof GalleryCollection))
						return '';

					$title = Yii::t('gallery', 'Create image');

					return Html::a('<span class="glyphicon glyphicon-plus"></span>', $url, [
						'title' => $title,
						'aria-label' => $title,
						'data-pjax' => 0,
					]);
				},
			],
			'urlCreator' => function($action, $model, $key, $index) {
				if ($action == 'create')
					return ['item/create', 'id' => $model->id];

				$route = $action;

				if ($model instanceof GalleryItem)
					$route = 'item/' . $route;

				return [$route, 'id' => $model->id];
			},
		],
	],
]) ?>
