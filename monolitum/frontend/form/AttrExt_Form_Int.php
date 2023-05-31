<?php
namespace monolitum\frontend\form;

use monolitum\entity\ValidatedValue;

class AttrExt_Form_Int extends AttrExt_Form
{

    private $min = null;
    private $max = null;

    /**
     * @param int $int
     * @return $this
     */
    public function min($int)
    {
        $this->min = $int;
        return $this;
    }

    /**
     * @param int $int
     * @return $this
     */
    public function max($int)
    {
        $this->max = $int;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @return int|null
     */
    public function getMax()
    {
        return $this->max;
    }


    public function revalidate(ValidatedValue $validated)
    {
        $error = false;

        if(!$this->isNullable() && $validated->isNull())
            $error = true;

        if(!$validated->isNull()){

            $val = $validated->getValue();

            if($this->min !== null && $val < $this->min)
                $error = true;

            if($this->max !== null && $val > $this->max)
                $error = true;

        }

        if($error){
            return $this->makeDefault(new ValidatedValue(false, $validated->getValue()));
        }else{
            return $validated;
        }

    }

    static function of(){
        return new AttrExt_Form_Int();
    }

}

