<?php

namespace cms\gallery\backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use cms\gallery\common\models\GallerySection;
use cms\gallery\common\models\GalleryItem;
use cms\gallery\backend\models\GallerySectionForm;

/**
 * Root gallery collection controller
 */
class SectionController extends Controller
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
	 * @return string
	 */
	public function actionCreate()
	{
		$model = new GallerySectionForm;

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));
			
			return $this->redirect(['gallery/index', 'id' => $model->getObject()->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Update
	 * @param integer $id
	 * @return string
	 */
	public function actionUpdate($id)
	{
		$object = GallerySection::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));

		$model = new GallerySectionForm($object);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));

			return $this->redirect(['gallery/index', 'id' => $model->getObject()->id]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Delete
	 * @param integer $id
	 * @return string
	 */
	public function actionDelete($id)
	{
		$object = GallerySection::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));

		//remove images and files
		foreach ($object->children()->all() as $child) {
			if ($child instanceof GalleryItem) {
				foreach ($child->images as $image) {
					Yii::$app->storage->removeObject($image);
					$image->delete();
				}
			}

			Yii::$app->storage->removeObject($child);
		}

		//remove section with collections and items
		if ($object->deleteWithChildren())
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Item deleted successfully.'));

		return $this->redirect(['gallery/index']);
	}

}
