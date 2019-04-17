<?php

namespace cms\gallery\backend\models;

use Yii;
use yii\base\Model;

use cms\gallery\common\models\GallerySection;

/**
 * Gallery section editting form.
 */
class GallerySectionForm extends Model
{

    /**
     * @var boolean Active.
     */
    public $active;

    /**
     * @var string Title.
     */
    public $title;

    /**
     * @var string Alias.
     */
    public $alias;

    /**
     * @var GallerySection
     */
    private $_object;

    /**
     * @inheritdoc
     * @param GallerySection $object 
     */
    public function __construct(GallerySection $object = null, $config = [])
    {
        if ($object === null)
            $object = new GallerySection;
        
        $this->_object = $object;

        //attributes
        $this->active = $object->active == 0 ? '0' : '1';
        $this->title = $object->title;
        $this->alias = $object->alias;

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'active' => Yii::t('gallery', 'Active'),
            'title' => Yii::t('gallery', 'Title'),
            'alias' => Yii::t('gallery', 'Alias'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['active', 'boolean'],
            [['title', 'alias'], 'string', 'max' => 100],
            ['title', 'required'],
        ];
    }

    /**
     * Object getter
     * @return GallerySection
     */
    public function getObject()
    {
        return $this->_object;
    }

    /**
     * Determine if object is empty
     * @return boolean
     */
    public function isEmpty()
    {
        return $this->_object->getIsNewRecord() || ($this->_object->rgt - $this->_object->lft == 1);
    }

    /**
     * Save object using model attributes
     * @return boolean
     */
    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $object = $this->_object;

        $object->active = $this->active == 1;
        $object->title = $this->title;
        $object->alias = $this->alias;

        if ($object->getIsNewRecord()) {
            if (!$object->makeRoot(false)) {
                return false;
            }
        } else {
            if (!$object->save(false)) {
                return false;
            }
        }

        if (empty($object->alias)) {
            $object->makeAlias();
            $object->update(false, ['alias']);
        }

        return true;
    }

}
