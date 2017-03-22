<?php

namespace cms\gallery\common\models;

/**
 * Gallery section (root collection) active record
 * 
 * In section you can specify thumb width and height,
 * but you can not specify image or thumb associated
 * with section.
 */
class GallerySection extends Gallery
{

	/**
	 * @inheritdoc
	 */
	public function __construct($config = [])
	{
		parent::__construct($config);

		$this->type = self::TYPE_SECTION;
	}	

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		if ($this->active === null)
			$this->active = true;

		if ($this->thumbWidth === null)
			$this->thumbWidth = 360;

		if ($this->thumbHeight === null)
			$this->thumbHeight = 270;
	}

}
