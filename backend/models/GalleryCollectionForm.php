<?php

namespace cms\gallery\backend\models;

use Yii;
use yii\base\Model;

use cms\gallery\common\models\GallerySection;
use cms\gallery\common\models\GalleryCollection;

/**
 * Gallery section editting form.
 */
class GalleryCollectionForm extends Model
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
	 * @var string
	 */
	public $image;

	/**
	 * @var string
	 */
	public $thumb;

	/**
	 * @var GalleryCollection
	 */
	private $_object;

	/**
	 * @inheritdoc
	 * @param GalleryCollection $object 
	 */
	public function __construct(GalleryCollection $object = null, $config = [])
	{
		if ($object === null)
			$object = new GalleryCollection;
		
		$this->_object = $object;

		//attributes
		$this->active = $object->active == 0 ? '0' : '1';
		$this->title = $object->title;
		$this->description = $object->description;
		$this->image = $object->image;
		$this->thumb = $object->thumb;

		//file caching
		Yii::$app->storage->cacheObject($object);

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
			'image' => Yii::t('gallery', 'Thumb'),
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
			[['image', 'thumb'], 'string'],
			['title', 'required'],
		];
	}

	/**
	 * Object getter
	 * @return GalleryCollection
	 */
	public function getObject()
	{
		return $this->_object;
	}

	/**
	 * Save object using model attributes
	 * @param GallerySection|GalleryCollection|null $parent 
	 * @return boolean
	 */
	public function save($parent = null)
	{
		if (!$this->validate())
			return false;

		$object = $this->_object;

		if ($object->getIsNewRecord() && !($parent instanceof GallerySection || $parent instanceof GalleryCollection))
			throw new \Exception('$parent must be set to create new object');

		$object->active = $this->active == 1;
		$object->title = $this->title;
		$object->description = $this->description;
		$object->image = $this->image;
		$object->thumb = $this->thumb;

		Yii::$app->storage->storeObject($object);

		if ($object->getIsNewRecord()) {
			$object->thumbWidth = $parent->thumbWidth;
			$object->thumbHeight = $parent->thumbHeight;

			if (!$object->appendTo($parent, false))
				return false;
		} else {
			if (!$object->save(false))
				return false;
		}

		if (empty($this->alias)) {
			$object->makeAlias();
			$object->update(false, ['alias']);
		}

		return true;
	}

}
