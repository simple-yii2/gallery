<?php

namespace gallery\common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

use helpers\Translit;
use creocoder\nestedsets\NestedSetsBehavior;
use creocoder\nestedsets\NestedSetsQueryBehavior;

/**
 * Base gallery active record
 */
class Gallery extends ActiveRecord
{

	/**
	 * Type constants
	 */
	const TYPE_SECTION = 0;
	const TYPE_COLLECTION = 1;

	/**
	 * @inheritdoc
	 */
	public static function instantiate($row)
	{
		if (isset($row['type'])) {
			if ($row['type'] == static::TYPE_SECTION)
				return new GallerySection;

			if ($row['type'] == static::TYPE_COLLECTION)
				return new GalleryCollection;
		}

		return new static;
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Gallery';
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
	 * Making gallery alias from title and id
	 * @return void
	 */
	public function makeAlias()
	{
		$this->alias = Translit::t($this->title . '-' . $this->id);
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'tree' => [
				'class' => NestedSetsBehavior::className(),
			],
		];
	}

	/**
	 * @inheritdoc
	 */
	public static function find()
	{
		return new GalleryQuery(get_called_class());
	}

}

/**
 * Base gallery active query
 */
class GalleryQuery extends ActiveQuery
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			NestedSetsQueryBehavior::className(),
		];
	}

	/**
	 * Add only section condition.
	 * @return ActiveQuery
	 */
	public function section()
	{
		return $this->andWhere(['type' => Gallery::TYPE_SECTION]);
	}

	/**
	 * Add only collection condition.
	 * @return ActiveQuery
	 */
	public function collection()
	{
		return $this->andWhere(['type' => Gallery::TYPE_COLLECTION]);
	}

}
