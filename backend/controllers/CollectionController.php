<?php

namespace cms\gallery\backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use cms\gallery\backend\models\GalleryCollectionForm;
use cms\gallery\common\models\GalleryCollection;
use cms\gallery\common\models\GallerySection;

class CollectionController extends Controller
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
	 * @param integer $id section id
	 * @return string
	 */
	public function actionCreate($id)
	{
		$parent = GallerySection::findOne($id);
		if ($parent === null)
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));

		$model = new GalleryCollectionForm(new GalleryCollection);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save($parent)) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));
			return $this->redirect(['section/index', 'id' => $model->getObject()->id]);
		}

		return $this->render('create', [
			'model' => $model,
			'section' => $parent,
		]);
	}

	/**
	 * Update
	 * @param integer $id
	 * @return string
	 */
	public function actionUpdate($id)
	{
		$object = GalleryCollection::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));

		$model = new GalleryCollectionForm($object);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));
			return $this->redirect(['section/index', 'id' => $model->getObject()->id]);
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
		$object = GalleryCollection::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));

		$sibling = $object->prev()->one();
		if ($sibling === null)
			$sibling = $object->next()->one();

		//remove images and files
		foreach ($object->images as $image) {
			$image->delete();
			Yii::$app->storage->removeObject($image);
		}
		Yii::$app->storage->removeObject($object);

		//collection
		if ($object->deleteWithChildren())
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Item deleted successfully.'));

		return $this->redirect(['section/index', 'id' => $sibling ? $sibling->id : null]);
	}

}
