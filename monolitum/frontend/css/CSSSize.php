<?php
namespace monolitum\frontend\css;

class CSSSize implements SizeAutoProperty
{

    /**
     * @var numeric
     */
    private $number;

    /**
     * @var CSSUnit
     */
    private $unit;

    /**
     * @param float|int|string $number
     * @param CSSUnit $unit
     */
    public function __construct($number, CSSUnit $unit)
    {
        $this->number = $number;
        $this->unit = $unit;
    }

    public static function px($number){
        return new CSSSize($number, CSSUnit::px());
    }

    public static function pct($number){
        return new CSSSize($number, CSSUnit::pct());
    }

    function write()
    {
        return $this->number . $this->unit->write();
    }
}