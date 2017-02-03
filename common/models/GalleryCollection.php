<?php

namespace cms\gallery\common\models;

use Yii;
use yii\db\ActiveRecord;

use helpers\Translit;

/**
 * Gallery collection active record
 */
class GalleryCollection extends Gallery
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->type = self::TYPE_COLLECTION;
		$this->active = true;
		$this->thumbWidth = 360;
		$this->thumbHeight = 270;
	}

	/**
	 * Making gallery alias from title and id
	 * @return void
	 */
	public function makeAlias()
	{
		$this->alias = Translit::t($this->title . '-' . $this->id);
	}

}
