<?php

namespace monolitum\bootstrap\style;

use monolitum\core\GlobalContext;
use monolitum\frontend\ElementComponent_Ext;

class BSRow extends ElementComponent_Ext
{

    /**
     * @var int
     */
    private $gap = null;

    public function __construct()
    {
        parent::__construct(function (BSRow $it){
            $it->addClass("row");

            if($it->gap !== null)
                $it->addClass("gap-" . $it->gap);

        });
    }

    /**
     * @param int $value
     * @return $this
     */
    public function gap($value)
    {
        $this->gap = $value;
        return $this;
    }

    /**
     * @return BSRow
     */
    public static function add(){
        /** @var BSRow $active */
        $active = GlobalContext::add(new BSRow());
        return $active;
    }

}