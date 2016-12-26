<?php

namespace cms\gallery\backend\assets;

use yii\web\AssetBundle;

class GalleryFormAsset extends AssetBundle
{

	public $sourcePath = __DIR__ . '/gallery-form';

	public $js = [
		'gallery-form.js',
	];
	
	public $depends = [
		'yii\bootstrap\BootstrapAsset',
		'yii\web\JqueryAsset',
	];

}
