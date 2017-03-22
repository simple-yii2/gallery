<?php

namespace cms\gallery\frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

use cms\gallery\common\models\Gallery;
use cms\gallery\common\models\GalleryItem;

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
		if (!$model->active)
			throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));

		$view = $model instanceof GalleryItem ? 'item' : 'section';

		return $this->render($view, [
			'model' => $model,
		]);
	}

}
