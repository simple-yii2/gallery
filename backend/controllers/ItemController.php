<?php

namespace cms\gallery\backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use cms\gallery\backend\models\GalleryItemForm;

use cms\gallery\common\models\Gallery;
use cms\gallery\common\models\GallerySection;
use cms\gallery\common\models\GalleryCollection;
use cms\gallery\common\models\GalleryItem;

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
		$parent = Gallery::findOne($id);
		if (!($parent instanceof GallerySection || $parent instanceof GalleryCollection))
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));

		$model = new GalleryItemForm;

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save($parent)) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));

			return $this->redirect(['gallery/index', 'id' => $model->getObject()->id]);
		}

		return $this->render('create', [
			'model' => $model,
			'parent' => $parent,
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
		if (!($object instanceof GalleryItem))
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));

		$model = new GalleryItemForm($object);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));

			return $this->redirect(['gallery/index', 'id' => $model->getObject()->id]);
		}

		return $this->render('update', [
			'model' => $model,
			'parent' => $object->parents(1)->one(),
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
		if (!($object instanceof GalleryItem))
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));

		$initial = $object->prev()->one();
		if ($initial === null)
			$initial = $object->next()->one();
		if ($initial === null)
			$initial = $object->parents(1)->one();

		//remove images and files
		foreach ($object->images as $image) {
			Yii::$app->storage->removeObject($image);
			$image->delete();
		}

		//remove node
		if ($object->deleteWithChildren())
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Item deleted successfully.'));

		return $this->redirect(['gallery/index', 'id' => $initial ? $initial->id : null]);
	}

}
