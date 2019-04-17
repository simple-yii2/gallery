<?php

namespace cms\gallery\common\models;

use dkhlystov\storage\components\StoredInterface;

/**
 * Gallery collection active record
 * 
 * Thumb size is specify only in section (root collection).
 * In other collections you can set any image for preview.
 */
class GalleryCollection extends Gallery implements StoredInterface
{
    /**
     * @inheritdoc
     */
    public function __construct($config = [])
    {
        parent::__construct(array_replace([
            'active' => true,
        ], $config));

        $this->type = self::TYPE_COLLECTION;
    }

    public function getItems()
    {
        return $this->children(1)->orderBy(['type' => SORT_ASC])->all();
    }

    /**
     * Return files from attributes
     * @param array $attributes 
     * @return array
     */
    private function getFilesFromAttributes($attributes)
    {
        $files = [];

        if (!empty($attributes['image']))
            $files[] = $attributes['image'];

        if (!empty($attributes['thumb']))
            $files[] = $attributes['thumb'];

        return $files;
    }

    /**
     * @inheritdoc
     */
    public function getOldFiles()
    {
        return $this->getFilesFromAttributes($this->getOldAttributes());
    }

    /**
     * @inheritdoc
     */
    public function getFiles()
    {
        return $this->getFilesFromAttributes($this->getAttributes());
    }

    /**
     * @inheritdoc
     */
    public function setFiles($files)
    {
        if (array_key_exists($this->image, $files))
            $this->image = $files[$this->image];

        if (array_key_exists($this->thumb, $files))
            $this->thumb = $files[$this->thumb];
    }
}
