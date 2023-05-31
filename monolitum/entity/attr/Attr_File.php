<?php
namespace monolitum\entity\attr;

use monolitum\entity\ValidatedValue;
use monolitum\entity\values\File;

class Attr_File extends Attr
{

    const ERROR_NO_ERROR = UPLOAD_ERR_OK;
    const ERROR_INI_SIZE = UPLOAD_ERR_INI_SIZE;
    const ERROR_FORM_SIZE = UPLOAD_ERR_FORM_SIZE;
    const ERROR_PARTIAL = UPLOAD_ERR_PARTIAL;
    const ERROR_NO_FILE = UPLOAD_ERR_NO_FILE;
    const ERROR_NO_TMP_DIR = UPLOAD_ERR_NO_TMP_DIR;
    const ERROR_CANT_WRITE = UPLOAD_ERR_CANT_WRITE;
    const ERROR_EXTENSION = UPLOAD_ERR_EXTENSION;
    const ERROR_BAD_FORMAT = 9;
    const ERROR_MULTIPLE_NOT_SUPPORTED = 10;
    const ERROR_MAX_SIZE = 11;

    private $maxSize = 1000000;

    /**
     * @return Attr_File
     */
    public static function of(){
        return new Attr_File();
    }

    /**
     * Max size in bytes
     * @param int $maxSize
     * @return $this
     */
    public function maxSize($maxSize)
    {
        $this->maxSize = $maxSize;
        return $this;
    }

    public function validate($value)
    {
        // TODO $value is an array of attributes
        if(is_array($value)){

            $name = $value['name'];
            if(is_array($name) && count($name) > 0){
                // Multiple is not supported
                return new ValidatedValue(false, null, Attr_File::ERROR_MULTIPLE_NOT_SUPPORTED);
            }
            $type = $value['type'];
            if(is_array($type) && count($type) > 0){
                // Multiple is not supported
                return new ValidatedValue(false, null, Attr_File::ERROR_MULTIPLE_NOT_SUPPORTED);
            }
            $temp_name = $value['tmp_name'];
            if(is_array($temp_name) && count($temp_name) > 0){
                // Multiple is not supported
                return new ValidatedValue(false, null, Attr_File::ERROR_MULTIPLE_NOT_SUPPORTED);
            }
            $size = $value['size'];
            if(is_array($size) && count($size) > 0){
                // Multiple is not supported
                return new ValidatedValue(false, null, Attr_File::ERROR_MULTIPLE_NOT_SUPPORTED);
            }

            if ($this->maxSize !== null && $size > $this->maxSize) {
                return new ValidatedValue(false, null, Attr_File::ERROR_MAX_SIZE);
            }

            return new ValidatedValue(true, new File($name, $type, $size, $temp_name));

        }

        return new ValidatedValue(false);
    }
}

