<?php

namespace gallery\common\models;

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
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'title' => Yii::t('gallery', 'Title'),
		];
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
