<?php

namespace cms\gallery\common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

use creocoder\nestedsets\NestedSetsBehavior;
use creocoder\nestedsets\NestedSetsQueryBehavior;

use helpers\Translit;

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
	const TYPE_ITEM = 2;

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

			if ($row['type'] == static::TYPE_ITEM)
				return new GalleryItem;
		}

		return new static;
	}

	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'gallery';
	}

	/**
	 * Find by alias
	 * @param sring $alias Alias or id.
	 * @return Gallery
	 */
	public static function findByAlias($alias) {
		$model = static::findOne(['alias' => $alias]);
		if ($model === null)
			$model = static::findOne(['id' => $alias]);

		return $model;
	}

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'tree' => [
				'class' => NestedSetsBehavior::className(),
				'treeAttribute' => 'tree',
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

	/**
	 * Making gallery alias from title and id
	 * @return void
	 */
	public function makeAlias()
	{
		$this->alias = Translit::t($this->title . '-' . $this->id);
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
