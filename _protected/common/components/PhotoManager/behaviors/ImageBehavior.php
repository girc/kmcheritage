<?php

namespace common\components\PhotoManager\behaviors;



use yii\db\ActiveRecord;

class ImageBehavior extends \yii\base\Behavior
{/**
 * @var to cache the id when beforeDelete is used so that the id can be used in cascading cleanup during afterSave event
 * @see: http://czcodezone.blogspot.com/2014/01/cascade-delete-in-yii-model-by.html
 */
    private $idCache;
    public $_originalColumnName;
    public $_mediumColumnName;
    public $_thumbColumnName;
    public $_imageAttribute='image';

    public function getOriginalColumnName()
    {
        return $this->_originalColumnName;
    }

    public function setOriginalColumnName($value)
    {
        $this->_originalColumnName = $value;
    }

    public function getMediumColumnName()
    {
        return $this->_mediumColumnName;
    }

    public function setMediumColumnName($value)
    {
        $this->_mediumColumnName = $value;
    }

    public function getThumbColumnName()
    {
        return $this->_thumbColumnName;
    }

    public function setThumbColumnName($value)
    {
        $this->_thumbColumnName = $value;
    }

    //{{{ functions for $image
    /**
     * fetch stored image file name with complete path
     * @return string
     */
    public function getOriginalImageFile()
    {
        $col=$this->_originalColumnName;
        return isset($this->owner->$col) ? Yii::$app->params['uploadPath'] . $this->owner->$col : null;
    }

    /**
     * fetch stored image url
     * @return string
     */
    public function getOriginalImageUrl()
    {
        $colOriginal=$this->_originalColumnName;
        return Yii::$app->params['uploadUrl'] . $this->owner->$colOriginal;
    }

    /**
     * fetch stored image file name with complete path
     * @return string
     */
    public function getMediumImageFile()
    {
        $colMedium=$this->_mediumColumnName;
        return isset($this->owner->$colMedium) ? Yii::$app->params['uploadPath'] . $this->owner->$colMedium : null;
    }

    /**
     * fetch stored image url
     * @return string
     */
    public function getMediumImageUrl()
    {
        $colMedium=$this->_mediumColumnName;
        return Yii::$app->params['uploadUrl'] . $this->owner->$colMedium;
    }

    /**
     * fetch stored image file name with complete path
     * @return string
     */
    public function getThumbImageFile()
    {
        $colThumb=$this->_thumbColumnName;
        return isset($this->owner->$colThumb) ? Yii::$app->params['uploadPath'] . $this->owner->$colThumb : null;
    }

    /**
     * fetch stored image url
     * @return string
     */
    public function getThumbImageUrl()
    {
        $col=$this->_thumbColumnName;
        return Yii::$app->params['uploadUrl'] . $this->owner->$col;
    }

    /**
     * Process deletion of image
     *
     * @return boolean the status of deletion
     */
    public function deleteImage()
    {
        // remove files
        if (is_file($this->getOriginalImageFile()))
            @unlink($this->getOriginalImageFile());
        if (is_file($this->getMediumImageFile()))
            @unlink($this->getMediumImageFile());
        if (is_file($this->getThumbImageFile()))
            @unlink($this->getThumbImageFile());

        // Modify attributes then save
        $colOriginal=$this->_originalColumnName;
        $colMedium=$this->_mediumColumnName;
        $colThumb=$this->_thumbColumnName;
        $this->owner->$colOriginal = null;
        $this->owner->$colMedium = null;
        $this->owner->$colThumb = null;


        return true;
    }

    /**
     * @param $uploadedImage \yii\web\UploadedFile
     * @return bool
     */
    public function saveUploadedImage($uploadedImage){
        return $this->saveImage($uploadedImage->tempName,$uploadedImage->extension);
    }

    /**
     * @param $filePath string full file name
     * @return bool
     */
    public function saveImage($filePath,$extension)
    {
        // Get sizes
        list($width, $height) = getimagesize($filePath);

        //using GD library with imagine
        $imagine = new Imagine();
        $imagineImage = $imagine->open($filePath);

        $upload_dir = Yii::getAlias('@uploads/');

        // Set Model Properties
        $colOriginal=$this->_originalColumnName;
        $colMedium=$this->_mediumColumnName;
        $colThumb=$this->_thumbColumnName;

        $this->owner->$colOriginal = implode('_', [$this->id, Yii::$app->security->generateRandomString(5)]) . '.' . $extension;
        $this->owner->$colMedium = implode('_', [$this->id, Yii::$app->security->generateRandomString(5), 'm']) . '.' . $extension;
        $this->owner->$colThumb = implode('_', [$this->id, Yii::$app->security->generateRandomString(5), 't']) . '.' . $extension;

        // Now save model and save photos then return true
        if ($this->save()) {
            // filename with path
            $originalFile = $upload_dir . $this->owner->$colOriginal;
            $mediumFile = $upload_dir . $this->owner->$colMedium;
            $thumbFile = $upload_dir . $this->owner->$colThumb;

            //original
            $imagineImage->save($originalFile);
            Gps::addGpsInfo($originalFile, $originalFile, $this->description, null, null, 27, 56, 0, null);

            // Medium size
            $medium = $imagineImage->save($mediumFile);
            Gps::addGpsInfo($mediumFile, $mediumFile, $this->description, null, null, 27, 56, 0, null);
            if ($height > 300 || $width > 400) {
                $medium->resize(new Box(400, 300), ImageInterface::FILTER_UNDEFINED)->save($mediumFile);
            }


            // Thumbnail
            $thumb = $imagineImage->save($thumbFile);
            Gps::addGpsInfo($thumbFile, $thumbFile, $this->description, null, null, 27, 56, 0, null);
            if ($height > 59) {
                switch ($height > $width) {
                    case true:
                        $mode = ImageInterface::THUMBNAIL_INSET;
                        break;
                    case false:
                        $mode = ImageInterface::THUMBNAIL_OUTBOUND;
                        break;
                    default:
                        break;
                }
                $thumb->thumbnail(new Box(89, 59), $mode)->save($thumbFile);
            }
            return true;
        }
    }

    /**
     * @param $uploadedImage \yii\web\UploadedFile
     * @return bool
     */
    public function replaceUploadedImage($uploadedImage){
        return $this->replaceImage($uploadedImage->tempName,$uploadedImage->extension);
    }

    /**
     * @param $filePath string
     * @param $extension string
     * @return bool
     */
    public function replaceImage($filePath,$extension)
    {
        $this->deleteImage();
        $this->saveImage($filePath,$extension);
    }
    //Static Functions
    /**
     * fetch stored image file name with complete path
     * @param string $version
     * @return null|string
     * @throws Exception
     */
    public static function getImagePath($id, $version = self::IMAGE_VERSION_ORIGINAL)
    {
        if (!isset($id)) {
            throw new \yii\base\Exception('Id must be specified');
        }
        $model = self::findOne($id);
        switch ($version) {
            case self::IMAGE_VERSION_ORIGINAL:
                return $model->getOriginalImageFile();
                break;
            case self::IMAGE_VERSION_MEDIUM:
                return $model->getMediumImageFile();
                break;
            case self::IMAGE_VERSION_THUMB:
                return $model->getThumbImageFile();
                break;
            default:
                throw new Exception('Could not find the specified image version <' . $version . '> Following versions are available:<br/><ul><li>IMAGE_VERSION_ORIGINAL="original"</li><li>IMAGE_VERSION_MEDIUM="medium"</li><li>IMAGE_VERSION_THUMB="thumb"</li></ul>');
        }
    }

    public static function getImageUrl($id, $version = self::IMAGE_VERSION_ORIGINAL)
    {
        if (!isset($id)) {
            throw new \yii\base\Exception('Id must be specified');
        }
        $model = self::findModel($id);
        switch ($version) {
            case self::IMAGE_VERSION_ORIGINAL:
                return $model->getOriginalImageUrl();
                break;
            case self::IMAGE_VERSION_MEDIUM:
                return $model->getMediumImageUrl();
                break;
            case self::IMAGE_VERSION_THUMB:
                return $model->getThumbImageUrl();
                break;
            default:
                throw new Exception('Could not find the specified image version <' . $version . '> Following versions are available:<br/><ul><li>IMAGE_VERSION_ORIGINAL="original"</li><li>IMAGE_VERSION_MEDIUM="medium"</li><li>IMAGE_VERSION_THUMB="thumb"</li></ul>');
        }
    }

    public function getNewRank()
    {
        return $this->building->getPhotos()->count() + 1;
    }

    public function setRank($rank)
    {
        if (!$rank) {
            throw new \yii\base\Exception('Rank not Specified');
        }
        $this->rank = $rank;
    }
    //}}}

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    public function beforeValidate($event)
    {
        $this->deleteImage();
    }
}