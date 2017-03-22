<?php

use yii\helpers\Html;

$title = $model->getObject()->title;

$this->title = $title . ' | ' . Yii::$app->name;

$breadcrumbs = [['label' => Yii::t('gallery', 'Galleries'), 'url' => ['gallery/index']]];
foreach (array_merge($parent->parents()->all(), [$parent]) as $object) {
	if ($object instanceof GallerySection) {
		$url = ['section/update', 'id' => $object->id];
	} else {
		$url = ['collection/update', 'id' => $object->id];
	}
	$breadcrumbs[] = ['label' => $object->title, 'url' => $url];
}
$breadcrumbs[] = $title;
$this->params['breadcrumbs'] = $breadcrumbs;

?>
<h1><?= Html::encode($title) ?></h1>

<?= $this->render('form', [
	'model' => $model,
	'parent' => $parent,
]) ?>
