<?php
namespace monolitum\frontend\form;

use monolitum\core\GlobalContext;
use monolitum\core\panic\DevPanic;
use monolitum\frontend\html\HtmlElement;

class FormControl_File extends FormControl
{

    /**
     * @param callable|null $builder
     */
    public function __construct(callable $builder = null)
    {
        parent::__construct(new HtmlElement("input"), $builder);
        $this->getElement()->setAttribute("type", "file");
    }

    public function convertToHidden($value = true)
    {
        // TODO Files cannot be hidden!!
        if($value)
            throw new DevPanic("Files cannot be hidden (for now)");
    }

    /**
     * @param callable $builder
     * @return FormControl_File
     */
    public static function add($builder)
    {
        $fc = new FormControl_File($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}

