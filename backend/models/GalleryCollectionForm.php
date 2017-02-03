<?php

namespace cms\gallery\backend\models;

use Yii;
use yii\base\Model;

use cms\gallery\common\models\Gallery;
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
	 * @var integer
	 */
	public $thumbWidth;

	/**
	 * @var integer
	 */
	public $thumbHeight;

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
		$this->thumbWidth = $object->thumbWidth;
		$this->thumbHeight = $object->thumbHeight;

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
			'thumbWidth' => Yii::t('gallery', 'Thumb width'),
			'thumbHeight' => Yii::t('gallery', 'Thumb height'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			['active', 'boolean'],
			[['title'], 'string', 'max' => 100],
			['title', 'required'],
			[['thumbWidth', 'thumbHeight'], 'integer', 'min' => 32, 'max' => 1000],
			[['thumbWidth', 'thumbHeight'], 'required']
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
	 * Determine if object is empty
	 * @return boolean
	 */
	public function isEmpty()
	{
		return $this->_object->getIsNewRecord() || ($this->_object->rgt - $this->_object->lft == 1);
	}

	/**
	 * Save object using model attributes
	 * @return boolean
	 */
	public function save()
	{
		if (!$this->validate())
			return false;

		$object = $this->_object;

		$object->active = $this->active == 1;
		$object->title = $this->title;
		$object->thumbWidth = (integer) $this->thumbWidth;
		$object->thumbHeight = (integer) $this->thumbHeight;

		if ($object->getIsNewRecord()) {
			if (!$object->makeRoot(false))
				return false;

			$object->makeAlias();
			$object->update(false, ['alias']);
		} else {
			if (!$object->save(false))
				return false;
		}

		return true;
	}

}
