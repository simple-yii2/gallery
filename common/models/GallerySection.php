<?php

namespace cms\gallery\common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Gallery section active record
 */
class GallerySection extends Gallery
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->type = self::TYPE_SECTION;
		$this->active = true;
		$this->thumbWidth = 360;
		$this->thumbHeight = 270;
	}

}
