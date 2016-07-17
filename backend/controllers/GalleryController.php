<?php

namespace gallery\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use gallery\backend\models\GalleryForm;
use gallery\common\models\Gallery;

/**
 * Gallery manage controller
 */
class GalleryController extends Controller
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
	 * Gallery list.
	 * @return void
	 */
	public function actionIndex()
	{
		$dataProvider = new ActiveDataProvider([
			'query' => Gallery::find(),
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}

	/**
	 * Gallery creating.
	 * @return void
	 */
	public function actionCreate()
	{
		$model = new GalleryForm;

		if ($model->load(Yii::$app->getRequest()->post()) && $model->create()) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));
			return $this->redirect([
				'index',
			]);
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
		$object = Gallery::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('gallery', 'Gallery not found.'));

		$model = new GalleryForm(['object' => $object]);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->update()) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));
			return $this->redirect(['index']);
		}

		return $this->render('update', [
			'model' => $model,
		]);
	}

	/**
	 * Gallery deleting.
	 * @param integer $id Gallery id.
	 * @return void
	 */
	public function actionDelete($id)
	{
		$item = Gallery::findOne($id);
		if ($item === null)
			throw new BadRequestHttpException(Yii::t('gallery', 'Gallery not found.'));

		if ($item->delete()) {
			foreach ($item->images as $image) {
				$image->delete();
				Yii::$app->storage->removeObject($image);
			}

			Yii::$app->storage->removeObject($item);
			
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Gallery deleted successfully.'));
		}

		return $this->redirect(['index']);
	}

}
