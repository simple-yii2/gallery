<?php

namespace gallery\backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use gallery\backend\models\GalleryCollectionForm;
use gallery\common\models\GalleryCollection;

/**
 * Gallery collection controller
 */
class CollectionController extends Controller
{

	/**
	 * Access control
	 * @return array
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
	 * Create gallery collection.
	 * @param integer|null $id Parent item id.
	 * @return void
	 */
	public function actionCreate($id = null)
	{
		$model = new GalleryCollectionForm;

		if ($model->load(Yii::$app->getRequest()->post()) && $model->create($id)) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));
			return $this->redirect(['section/index', 'id' => $model->object->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Gallery updating.
	 * @param integer $id Gallery id.
	 * @return void
	 */
	public function actionUpdate($id)
	{
		$object = GalleryCollection::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('gallery', 'Gallery not found.'));

		$model = new GalleryCollectionForm(['object' => $object]);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->update()) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));
			return $this->redirect(['section/index', 'id' => $model->object->id]);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

}
