<?php

namespace cms\gallery\common\models;

use Yii;
use yii\db\ActiveRecord;

use storage\components\StoredInterface;

/**
 * Gallery collection active record
 */
class GalleryCollection extends Gallery implements StoredInterface
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
	 * Images relation
	 * @return ActiveQuery
	 */
	public function getImages()
	{
		return $this->hasMany(GalleryImage::className(), ['gallery_id' => 'id']);
	}

	/**
	 * @inheritdoc
	 */
	public static function find()
	{
		return parent::find()->andWhere(['type' => self::TYPE_COLLECTION]);
	}

	/**
	 * Return files from attributes
	 * @param array $attributes 
	 * @return array
	 */
	private function getFilesFromAttributes($attributes)
	{
		$files = [];

		if (!empty($attributes['image']))
			$files[] = $attributes['image'];

		if (!empty($attributes['thumb']))
			$files[] = $attributes['thumb'];

		return $files;
	}

	/**
	 * @inheritdoc
	 */
	public function getOldFiles()
	{
		return $this->getFilesFromAttributes($this->getOldAttributes());
	}

	/**
	 * @inheritdoc
	 */
	public function getFiles()
	{
		return $this->getFilesFromAttributes($this->getAttributes());
	}

	/**
	 * @inheritdoc
	 */
	public function setFiles($files)
	{
		if (array_key_exists($this->image, $files))
			$this->image = $files[$this->image];

		if (array_key_exists($this->thumb, $files))
			$this->thumb = $files[$this->thumb];
	}

}
