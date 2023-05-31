<?php
namespace monolitum\entity;

use monolitum\entity\AttrExt_Validate;

class AttrExt_Validate_String extends AttrExt_Validate
{

    /**
     * @var int|null
     */
    private $maxChars = null;

    /**
     * @param int $maxChars
     */
    public function maxChars($maxChars)
    {
        $this->maxChars = $maxChars;
    }

    /**
     * @return int|null
     */
    public function getMaxChars()
    {
        return $this->maxChars;
    }

    public static function of(){
        return new AttrExt_Validate_String();
    }
    
}

