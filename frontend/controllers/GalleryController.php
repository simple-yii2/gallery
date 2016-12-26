<?php

namespace cms\gallery\frontend\controllers;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

use cms\gallery\common\models\Gallery;

/**
 * Gallery frontend controller
 */
class GalleryController extends Controller
{

	/**
	 * Show gallery
	 * @param string $alias 
	 * @return void
	 */
	public function actionIndex($alias)
	{
		$model = Gallery::findByAlias($alias);
		if ($model === null || !$model->active)
			throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));

		return $this->render('index', [
			'model' => $model,
		]);
	}

}
