<?php

namespace cms\gallery\frontend\assets;

use yii\web\AssetBundle;

class CollectionAsset extends AssetBundle
{

	public $css = [
		'collection.css',
	];

	public function init()
	{
		parent::init();

		$this->sourcePath = __DIR__ . '/collection';
	}

}
