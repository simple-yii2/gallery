<?php

namespace cms\gallery\frontend\assets;

use yii\web\AssetBundle;

class GalleryAsset extends AssetBundle
{

	public $css = [
		'gallery.css',
	];

	public function init()
	{
		parent::init();

		$this->sourcePath = __DIR__ . '/gallery';
	}

}
