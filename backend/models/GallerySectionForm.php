<?php

namespace cms\gallery\backend\models;

use Yii;
use yii\base\Model;

use cms\gallery\common\models\Gallery;
use cms\gallery\common\models\GallerySection;

/**
 * Gallery section editting form.
 */
class GallerySectionForm extends Model
{

	/**
	 * @var string Title.
	 */
	public $title;

	/**
	 * @var string Alias.
	 */
	public $alias;

	/**
	 * @var cms\gallery\common\models\GallerySection
	 */
	private $_object;

	/**
	 * @inheritdoc
	 * @param cms\gallery\common\models\GallerySection $object 
	 */
	public function __construct(\cms\gallery\common\models\GallerySection $object, $config = [])
	{
		$this->_object = $object;

		//attributes
		$this->title = $object->title;
		$this->alias = $object->alias;

		parent::__construct($config);
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'title' => Yii::t('gallery', 'Title'),
			'alias' => Yii::t('gallery', 'Alias'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'alias'], 'string', 'max' => 100],
			['title', 'required'],
		];
	}

	/**
	 * Object getter
	 * @return cms\gallery\common\models\GallerySection
	 */
	public function getObject()
	{
		return $this->_object;
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

		$object->title = $this->title;
		$object->alias = $this->alias;

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
