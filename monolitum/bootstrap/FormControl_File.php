<?php
namespace monolitum\bootstrap;

use monolitum\core\GlobalContext;

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

