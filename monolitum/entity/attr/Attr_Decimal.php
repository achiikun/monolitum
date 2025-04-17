<?php
namespace monolitum\entity\attr;

use Exception;
use monolitum\entity\ValidatedValue;

class Attr_Decimal extends Attr
{
    /** @var int */
    private $decimals;

    /**
     * @param int $decimals
     */
    public function __construct($decimals = 0)
    {
        $this->decimals = $decimals;
    }

    /**
     * @return int
     */
    public function getDecimals()
    {
        return $this->decimals;
    }

    /**
     * @return Attr_Decimal
     */
    public static function from($decimals = 0){
        return new Attr_Decimal($decimals);
    }

    public function validate($value)
    {
        if(is_numeric($value)){
            $withoutPoint = intval(intval($value) * pow(10, $this->decimals));
            return new ValidatedValue(true, true, $withoutPoint, null, $this->stringValue($value));
        } else if(is_string($value)){
            try{
                $floatValue = floatval($value);
                $intValue = intval($floatValue * pow(10, $this->decimals));
                return new ValidatedValue(true, true, $intValue, null, $this->stringValue($intValue));
            }catch (Exception $e){
                return new ValidatedValue(false);
            }
        }
        return new ValidatedValue(false);
    }

    public function stringValue($value)
    {
        if(is_int($value)){
            $zeros = pow(10, $this->decimals);
            $integerPart = intval($value / $zeros);
            $floatingPart = $value - ($integerPart * $zeros);
            return $integerPart . "." . $floatingPart;
        }
        return "";
    }

}

