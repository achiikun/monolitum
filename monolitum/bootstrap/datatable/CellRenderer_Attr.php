<?php

namespace monolitum\bootstrap\datatable;

use monolitum\bootstrap\FormControl_CheckBox;
use monolitum\core\GlobalContext;
use monolitum\entity\attr\Attr_Bool;
use monolitum\entity\attr\Attr_Date;
use monolitum\entity\attr\Attr_Decimal;
use monolitum\entity\attr\Attr_Int;
use monolitum\entity\attr\Attr_String;
use monolitum\frontend\component\Reference;
use monolitum\frontend\component\Text;
use monolitum\core\panic\DevPanic;

class CellRenderer_Attr implements CellRenderer
{

    private $attr;

    /**
     * @var string
     */
    private $format = null;

    public function __construct($attr)
    {
        $this->attr = $attr;
    }

    /**
     * @param string $format
     * @return $this
     */
    public function format($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * @inheritDoc
     */
    function prepare($datatable)
    {
        // TODO: Implement prepare() method.
    }

    /**
     * @inheritDoc
     */
    function render($entity)
    {
        if($entity == null){
            return Reference::ofEmpty();
        } else {
            $attr = $entity->getAttr($this->attr);
            if($attr instanceof Attr_String){
                return Text::of($entity->getString($attr));
            }else if($attr instanceof Attr_Int){
                return Text::of(strval($entity->getInt($attr)));
            }else if($attr instanceof Attr_Decimal){
                return Text::of(strval($entity->getInt($attr) / pow(10, $attr->getDecimals())));
            }else if($attr instanceof Attr_Date){
                $val = $entity->getDate($attr);
                return Text::of($val !== null ? strftime(
                    ($this->format !== null ? $this->format : "%Y-%m-%d"), $val->getTimestamp()
                ) : "");
            }else if($attr instanceof Attr_Bool){
                $ch = new FormControl_CheckBox();
                $ch->setDisabled(true);
                $ch->setValue($entity->getBool($attr)); // TODO intermediate
                return $ch;
            }else{
                throw new DevPanic("Not recognized col type");
            }
        }
    }

    function onNotReceived()
    {
        throw new DevPanic("No table found");
    }

    public static function of($attr)
    {
        return new CellRenderer_Attr($attr);
    }

}