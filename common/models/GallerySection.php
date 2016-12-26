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

		$this->active = true;
		$this->type = self::TYPE_SECTION;
	}

	/**
	 * @inheritdoc
	 */
	public static function find()
	{
		return parent::find()->andWhere(['type' => self::TYPE_SECTION]);
	}

}
