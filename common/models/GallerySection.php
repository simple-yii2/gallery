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
        parent::__construct(array_replace([
            'active' => true,
        ], $config));

        $this->type = self::TYPE_SECTION;
    }   

    public function getItems()
    {
        return $this->children(1)->orderBy(['type' => SORT_ASC])->all();
    }
}
