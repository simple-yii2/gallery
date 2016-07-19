<?php

namespace gallery\backend\models;

use Yii;
use yii\base\Model;

use gallery\common\models\Gallery;
use gallery\common\models\GallerySection;

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
	 * @var ActiveRecord Assigned object.
	 */
	public $object;

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		if (($object = $this->object) !== null) {
			$this->setAttributes($object->getAttributes(['title', 'alias']), false);
		}
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
	 * Creates new gallery section using model attributes.
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

		$this->object = $object = new GallerySection([
			'title' => $this->title,
		]);

		if (!$object->appendTo($parent, false))
			return false;

		$object->makeAlias();
		$object->update(false, ['alias']);

		return true;
	}

	/**
	 * Gallery section updating.
	 * @return boolean
	 */
	public function update() {
		if ($this->object === null)
			return false;

		if (!$this->validate())
			return false;

		$object = $this->object;

		$object->setAttributes([
			'title' => $this->title,
			'alias' => $this->alias,
		], false);

		if (!$object->save(false))
			return false;

		return true;
	}

}
