<?php

namespace cms\gallery\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use cms\gallery\common\models\Gallery;
use cms\gallery\common\models\GalleryCollection;
use cms\gallery\backend\models\GalleryCollectionForm;

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
	 * Tree
	 * @param integer|null $id Initial item id
	 * @return string
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
	 * Create
	 * @return string
	 */
	public function actionCreate()
	{
		$model = new GalleryCollectionForm;

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));
			
			return $this->redirect(['index', 'id' => $model->getObject()->id]);
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
		$object = GalleryCollection::findOne($id);
		if ($object === null && ($perent instanceof GalleryCollection))
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));

		$model = new GalleryCollectionForm($object);

		if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Changes saved successfully.'));
			return $this->redirect(['index', 'id' => $model->getObject()->id]);
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
		if ($object === null && ($perent instanceof GalleryCollection))
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));

		$collections = $object->children()->collection()->all();

		//remove images and files
		foreach ($collections as $collection) {
			foreach ($collection->images as $image) {
				$image->delete();
				Yii::$app->storage->removeObject($image);
			}
			Yii::$app->storage->removeObject($collection);
		}

		//remove section with collections
		if ($object->deleteWithChildren())
			Yii::$app->session->setFlash('success', Yii::t('gallery', 'Item deleted successfully.'));

		return $this->redirect(['index']);
	}

	/**
	 * Move
	 * @param integer $id 
	 * @param integer $target 
	 * @param integer $position 
	 * @return string
	 */
	public function actionMove($id, $target, $position)
	{
		$object = Gallery::findOne($id);
		if ($object === null)
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));
		if ($object->isRoot())
			return;

		$t = Gallery::findOne($target);
		if ($t === null)
			throw new BadRequestHttpException(Yii::t('gallery', 'Item not found.'));
		if ($t->isRoot())
			return;

		if ($object->tree != $t->tree)
			return;

		switch ($position) {
			case 0:
				$object->insertBefore($t);
				break;
			
			case 2:
				$object->insertAfter($t);
				break;
		}
	}

}
