<?php

namespace cms\gallery\backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use cms\gallery\backend\models\GalleryItemForm;
use cms\gallery\common\models\GalleryItem;
use cms\gallery\common\models\GalleryCollection;

class ItemController extends Controller
{

	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'rules' => [
					['allow' => true, 'roles' => ['Gallery']],
				],
			],
		];
	}

	/**
	 * Create
	 * @param integer $id collection id
	 * @return string
	 */
	public function actionCreate($id)
	{
		$parent = GalleryCollection::findOne($id);
		if ($parent === null && ($perent instanceof GalleryCollection))
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));

		$model = new GalleryItemForm;

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save($parent)) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));
			return $this->redirect(['collection/index', 'id' => $model->getObject()->id]);
		}

		return $this->render('create', [
			'model' => $model,
			'collection' => $parent,
		]);
	}

	/**
	 * Update
	 * @param integer $id
	 * @return string
	 */
	public function actionUpdate($id)
	{
		$object = GalleryItem::findOne($id);
		if ($object === null && ($perent instanceof GalleryItem))
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));

		$model = new GalleryItemForm($object);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));
			return $this->redirect(['collection/index', 'id' => $model->getObject()->id]);
		}

		return $this->render('update', [
			'model' => $model,
			'collection' => $object->parents(1)->one(),
		]);
	}

	/**
	 * Delete
	 * @param integer $id
	 * @return string
	 */
	public function actionDelete($id)
	{
		$object = GalleryItem::findOne($id);
		if ($object === null && ($perent instanceof GalleryItem))
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));

		$sibling = $object->prev()->one();
		if ($sibling === null)
			$sibling = $object->next()->one();

		//remove images and files
		foreach ($object->images as $image) {
			Yii::$app->storage->removeObject($image);
			$image->delete();
		}

		//collection
		if ($object->deleteWithChildren())
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Item deleted successfully.'));

		return $this->redirect(['collection/index', 'id' => $sibling ? $sibling->id : null]);
	}

}
