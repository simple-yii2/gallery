<?php

namespace cms\gallery\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

use cms\gallery\common\models\Gallery;

/**
 * Controller for gallery tree management
 */
class GalleryController extends Controller
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
