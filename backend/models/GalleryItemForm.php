<?php

namespace cms\gallery\backend\models;

use Yii;
use yii\base\Model;

use cms\gallery\common\models\Gallery;
use cms\gallery\common\models\GalleryCollection;
use cms\gallery\common\models\GalleryItem;
use cms\gallery\common\models\GalleryImage;

/**
 * Gallery collection editting form.
 */
class GalleryItemForm extends Model
{

	/**
	 * @var boolean Active.
	 */
	public $active;

	/**
	 * @var string Title.
	 */
	public $title;

	/**
	 * @var string Description.
	 */
	public $description;

	/**
	 * @var array Gallery images.
	 */
	public $images = [];

	/**
	 * @var GalleryItem
	 */
	private $_object;

	/**
	 * @inheritdoc
	 * @param GalleryItem $object 
	 */
	public function __construct(GalleryItem $object = null, $config = [])
	{
		if ($object === null)
			$object = new GalleryItem;

		$this->_object = $object;

		//attributes
		$this->active = $object->active == 0 ? '0' : '1';
		$this->title = $object->title;
		$this->description = $object->description;
		$this->images = $object->images;

		//file caching
		foreach ($object->images as $image)
			Yii::$app->storage->cacheObject($image);

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'active' => Yii::t('gallery', 'Active'),
			'title' => Yii::t('gallery', 'Title'),
			'description' => Yii::t('gallery', 'Description'),
			'images' => Yii::t('gallery', 'Images'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['active', 'boolean'],
			['title', 'string', 'max' => 100],
			['description', 'string', 'max' => 200],
			['images', 'safe'],
			['images', 'required'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function setAttributes($values, $safeOnly = true)
	{
		parent::setAttributes($values, $safeOnly);

		if (empty($this->images))
			$this->images = [];
	}

	/**
	 * Object getter
	 * @return GalleryItem
	 */
	public function getObject()
	{
		return $this->_object;
	}

	/**
	 * Save object using model attributes
	 * @param GalleryCollection|null $object 
	 * @return boolean
	 */
	public function save(GalleryCollection $parent = null)
	{
		if (!$this->validate())
			return false;

		$object = $this->_object;

		$object->active = $this->active == 1;
		$object->title = $this->title;
		$object->description = $this->description;
		$object->imageCount = sizeof($this->images);

		if ($object->getIsNewRecord()) {
			if (!$object->appendTo($parent, false))
				return false;
		} else {
			if (!$object->save(false))
				return false;
		}

		$this->updateImages();

		$object->image = $this->images[0]['file'];
		$object->thumb = $this->images[0]['thumb'];
		$object->update(false, ['image', 'thumb']);

		return true;
	}

	/**
	 * Update gallery images.
	 * @return void
	 */
	private function updateImages()
	{
		$object = $this->_object;

		$old = [];
		foreach ($object->images as $image) {
			$old[$image->id] = $image;
		}

		// insert/update
		foreach ($this->images as $key => $data) {
			$id = null;
			if (!empty($data['id']))
				$id = $data['id'];

			if (array_key_exists($id, $old)) {
				$image = $old[$id];
				unset($old[$id]);
			} else {
				$image = new GalleryImage();
				$image->gallery_id = $object->id;
			}
			$image->setAttributes($data);

			Yii::$app->storage->storeObject($image);

			$image->save(false);

			$this->images[$key]['file'] = $image->file;
			$this->images[$key]['thumb'] = $image->thumb;
		}

		// delete
		foreach ($old as $image) {
			Yii::$app->storage->removeObject($image);
			$image->delete();
		}
	}

}
