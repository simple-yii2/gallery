<?php

namespace gallery\backend\models;

use Yii;
use yii\base\Model;

use gallery\common\models\Gallery;
use gallery\common\models\GalleryCollection;
use gallery\common\models\GalleryImage;

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
	 * @var ActiveRecord Assigned object.
	 */
	public $object;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		//default
		$this->active = true;

		if (($object = $this->object) !== null) {
			$this->setAttributes($object->getAttributes(['active', 'image', 'thumb', 'title', 'alias', 'description', 'images']), false);

			Yii::$app->storage->cacheObject($object);
			foreach ($this->images as $image) {
				Yii::$app->storage->cacheObject($image);
			}
		}
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
	 * Creates new gallery using model attributes.
	 * @return boolean
	 */
	public function create($parent_id)
	{
		if (!$this->validate())
			return false;

		$parent = Gallery::findOne($parent_id);
		if ($parent === null)
			$parent = Gallery::find()->roots()->one();

		if ($parent === null)
			return false;

		$this->object = $object = new GalleryCollection([
			'active' => (boolean) $this->active,
			'image' => empty($this->image) ? null : $this->image,
			'thumb' => empty($this->thumb) ? null : $this->thumb,
			'title' => $this->title,
			'alias' => $this->alias,
			'description' => $this->description,
			'imageCount' => sizeof($this->images),
		]);

		Yii::$app->storage->storeObject($object);

		if (!$object->appendTo($parent, false))
			return false;

		$object->makeAlias();
		$object->update(false, ['alias']);

		$this->updateImages();

		return true;
	}

	/**
	 * Gallery updating.
	 * @return boolean
	 */
	public function update() {
		if ($this->object === null)
			return false;

		if (!$this->validate())
			return false;

		$object = $this->object;

		$object->setAttributes([
			'active' => (boolean) $this->active,
			'image' => empty($this->image) ? null : $this->image,
			'thumb' => empty($this->thumb) ? null : $this->thumb,
			'title' => $this->title,
			'alias' => $this->alias,
			'description' => $this->description,
			'imageCount' => sizeof($this->images),
		], false);

		Yii::$app->storage->storeObject($object);

		if (!$object->save(false))
			return false;

		$this->updateImages();

		return true;
	}

	/**
	 * Update gallery images.
	 * @return void
	 */
	private function updateImages()
	{
		$old = [];
		foreach ($this->object->images as $image) {
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
				$image->gallery_id = $this->object->id;
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
