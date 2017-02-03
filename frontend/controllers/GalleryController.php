<?php

namespace cms\gallery\frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use cms\gallery\common\models\GalleryCollection;

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
		$model = GalleryCollection::findByAlias($alias);
		if (!($model instanceof GalleryCollection) || !$model->active)
			throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));

		return $this->render('index', [
			'model' => $model,
		]);
	}

}
