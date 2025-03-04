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
            return new ValidatedValue(true, true, intval(intval($value) * pow(10, $this->decimals)), null, $value);
        } else if(is_string($value)){
            try{
                $floatValue = floatval($value);
                $intValue = intval($floatValue * pow(10, $this->decimals));
                return new ValidatedValue(true, true, $intValue, null, $value);
            }catch (Exception $e){
                return new ValidatedValue(false);
            }
        }
        return new ValidatedValue(false);
    }
}

