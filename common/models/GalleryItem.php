<?php

namespace cms\gallery\common\models;

use yii\helpers\Url;

use dkhlystov\storage\components\StoredInterface;

class GalleryItem extends Gallery implements StoredInterface
{

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->type = self::TYPE_ITEM;
		$this->active = true;
		$this->thumbWidth = 360;
		$this->thumbHeight = 270;
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		$behaviors = parent::behaviors();

		if (class_exists('cms\sitemap\common\behaviors\SitemapBehavior')) {
			$behaviors['sitemap'] = [
				'class' => 'cms\sitemap\common\behaviors\SitemapBehavior',
				'loc' => function($model) {
					return Url::toRoute(['/gallery/gallery/index', 'alias' => $model->alias]);
				},
				'active' => 'active',
			];
		}

		return $behaviors;
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
