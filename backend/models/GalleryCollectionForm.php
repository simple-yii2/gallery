<?php

namespace cms\gallery\backend\models;

use Yii;
use yii\base\Model;

use cms\gallery\common\models\Gallery;
use cms\gallery\common\models\GalleryCollection;
use cms\gallery\common\models\GalleryImage;

/**
 * Gallery collection editting form.
 */
class GalleryCollectionForm extends Model
{

	/**
	 * @var boolean Active.
	 */
	public $active;

	/**
	 * @var string Alias.
	 */
	public $alias;

	/**
	 * @var string Gallery image.
	 */
	public $image;

	/**
	 * @var string Gallery image thumb.
	 */
	public $thumb;

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
	 * @var cms\gallery\common\models\GalleryCollection
	 */
	private $_object;

	/**
	 * @inheritdoc
	 * @param cms\gallery\common\models\GalleryCollection $object 
	 */
	public function __construct(\cms\gallery\common\models\GalleryCollection $object, $config = [])
	{
		$this->_object = $object;

		//attributes
		$this->active = $object->active == 0 ? '0' : '1';
		$this->image = $object->image;
		$this->thumb = $object->thumb;
		$this->title = $object->title;
		$this->alias = $object->alias;
		$this->description = $object->description;
		$this->images = $object->images;

		//file caching
		Yii::$app->storage->cacheObject($object);
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
			'alias' => Yii::t('gallery', 'Alias'),
			'image' => Yii::t('gallery', 'Image'),
			'title' => Yii::t('gallery', 'Title'),
			'description' => Yii::t('gallery', 'Description'),
			'images' => Yii::t('gallery', 'Gallery'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['active', 'boolean'],
			[['image', 'thumb', 'description'], 'string', 'max' => 200],
			[['title', 'alias'], 'string', 'max' => 100],
			['images', 'safe'],
			['title', 'required'],
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
	 * @return cms\gallery\common\models\GalleryCollection
	 */
	public function getObject()
	{
		return $this->_object;
	}

	/**
	 * Save object using model attributes
	 * @param cms\gallery\common\models\GallerySection|null $object 
	 * @return boolean
	 */
	public function save(\cms\gallery\common\models\GallerySection $parent = null)
	{
		if (!$this->validate())
			return false;

		$object = $this->_object;

		$object->active = $this->active == 1;
		$object->image = empty($this->image) ? null : $this->image;
		$object->thumb = empty($this->thumb) ? null : $this->thumb;
		$object->title = $this->title;
		$object->alias = $this->alias;
		$object->description = $this->description;
		$object->imageCount = sizeof($this->images);

		Yii::$app->storage->storeObject($object);

		if ($object->getIsNewRecord()) {
			if (!$object->appendTo($parent, false))
				return false;

			$object->makeAlias();
			$object->update(false, ['alias']);
		} else {
			if (!$object->save(false))
				return false;
		}

		$this->updateImages();

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
		foreach ($this->images as $data) {
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
		}

		// delete
		foreach ($old as $image) {
			Yii::$app->storage->removeObject($image);
			$image->delete();
		}
	}

}
