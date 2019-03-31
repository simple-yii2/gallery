<?php

namespace cms\gallery\backend;

use Yii;

use cms\components\BackendModule;

/**
 * Gallry backend module
 */
class Module extends BackendModule {

	/**
	 * @inheritdoc
	 */
	public static function moduleName()
	{
		return 'gallery';
	}

	/**
	 * @inheritdoc
	 */
	public static function cmsSecurity()
	{
		//rbac
		$auth = Yii::$app->getAuthManager();
		if ($auth->getRole('Gallery') === null) {
			//role
			$role = $auth->createRole('Gallery');
			$auth->add($role);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function cmsMenu()
	{
		if (!Yii::$app->user->can('Gallery'))
			return [];

		return [
			['label' => Yii::t('gallery', 'Galleries'), 'url' => ['/gallery/gallery/index']],
		];
	}

}
