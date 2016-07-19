<?php

namespace gallery\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use gallery\common\models\Gallery;
use gallery\common\models\GallerySection;
use gallery\backend\models\GallerySectionForm;

/**
 * Gallery section controller.
 */
class SectionController extends Controller
{

	/**
	 * Access control.
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
	 * Gallery tree.
	 * @param integer|null $id Initial item id
	 * @return void
	 */
	public function actionIndex($id = null)
	{
		$initial = Gallery::findOne($id);

		$dataProvider = new ActiveDataProvider([
			'query' => Gallery::find(),
		]);

		return $this->render('index', [
			'dataProvider' => $dataProvider,
			'initial' => $initial,
		]);
	}

	/**
	 * Create gallery section.
	 * @param integer|null $id Parent item id.
	 * @return void
	 */
	public function actionCreate($id = null)
	{
		$model = new GallerySectionForm;

		if ($model->load(Yii::$app->getRequest()->post()) && $model->create($id)) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));
			return $this->redirect(['index', 'id' => $model->object->id]);
		}

		return $this->render('create', [
			'model' => $model,
		]);
	}

	/**
	 * Gallery section updating.
	 * @param integer $id Gallery section id.
	 * @return void
	 */
	public function actionUpdate($id)
	{
		$object = GallerySection::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('gallery', 'Section not found.'));

		$model = new GallerySectionForm(['object' => $object]);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->update()) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));
			return $this->redirect(['index', 'id' => $model->object->id]);
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
		$model = Gallery::findOne($id);
		if ($model === null)
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));

		$parent = $model->parents(1)->one();

		$collections = [];
		if ($model instanceof GalleryCollection)
			$collections[] = $model;
		$collections = array_merge($collections, $model->children()->collection()->all());
		foreach ($collections as $collection) {
			foreach ($collection->images as $image) {
				$image->delete();
				Yii::$app->storage->removeObject($image);
			}
			Yii::$app->storage->removeObject($collection);
		}

		if ($model->deleteWithChildren())
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Item deleted successfully.'));

		$url = ['index'];
		if ($parent !== null && !$parent->isRoot())
			$url['id'] = $parent->id;

		return $this->redirect($url);
	}

}
