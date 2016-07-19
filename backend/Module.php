<?php

namespace gallery\backend;

use Yii;

use gallery\common\models\Gallery;

/**
 * Gallry backend module
 */
class Module extends \yii\base\Module {

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		$this->checkDatabase();
		self::addTranslation();
	}

	/**
	 * Database checking
	 * @return void
	 */
	protected function checkDatabase()
	{
		//schema
		$db = Yii::$app->db;
		$filename = dirname(__DIR__) . '/schema/' . $db->driverName . '.sql';
		$sql = explode(';', file_get_contents($filename));
		foreach ($sql as $s) {
			if (trim($s) !== '')
				$db->createCommand($s)->execute();
		}

		//rbac
		$auth = Yii::$app->getAuthManager();
		if ($auth->getRole('Gallery') === null) {
			//gallery role
			$gallery = $auth->createRole('Gallery');
			$auth->add($gallery);
		}

		//data
		$root = Gallery::find()->roots()->one();
		if ($root === null) {
			$root = new Gallery(['title' => 'Root']);
			$root->makeRoot();
		}
	}

	/**
	 * Adding translation to i18n
	 * @return void
	 */
	protected static function addTranslation()
	{
		if (!isset(Yii::$app->i18n->translations['gallery'])) {
			Yii::$app->i18n->translations['gallery'] = [
				'class' => 'yii\i18n\PhpMessageSource',
				'sourceLanguage' => 'en-US',
				'basePath' => dirname(__DIR__) . '/messages',
			];
		}
	}

	/**
	 * Making main menu item of module
	 * @param string $base route base
	 * @return array
	 */
	public static function getMenu($base)
	{
		self::addTranslation();

		if (Yii::$app->user->can('Gallery')) {
			return [
				['label' => Yii::t('gallery', 'Galleries'), 'url' => ["$base/gallery/section/index"]],
			];
		}
		
		return [];
	}

}
