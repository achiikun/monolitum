<?php
namespace monolitum\frontend\form;

use monolitum\backend\globals\Active_NewId;
use monolitum\backend\params\Path;
use monolitum\bootstrap\BS;
use monolitum\bootstrap\BSPage;
use monolitum\core\Find;
use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\frontend\component\CSSLink;
use monolitum\frontend\component\JSInlineScript;
use monolitum\frontend\component\JSScript;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\Rendered;

class FormControl_Select extends FormControl
{

    /**
     * @var bool
     */
    private $picker = false;

    private $searchable = false;

    /**
     * @param callable|null $builder
     */
    public function __construct(callable $builder = null)
    {
        parent::__construct(new HtmlElement("select"), $builder, "form-select");
    }

    public function setPicker($picker=true)
    {
        $this->picker = $picker;
    }

    public function setSearchable($searchable=true)
    {
        $this->searchable = $searchable;
    }

    protected function buildComponent()
    {

        if($this->picker){
            /** @var BSPage $page */
            $page = Find::sync(BSPage::class);
            $page->includeBootstrapSelect2IfNot();

            parent::buildComponent();

            $this->append((new JSInlineScript())
                ->addScript("
$( '#" . $this->getId() . "' ).select2( {
    theme: \"bootstrap-5\",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
" .
                    ($this->searchable ? "" : "minimumResultsForSearch: Infinity,")
. "
    // allowClear: true
} );
            "));
        }else{
            parent::buildComponent();
        }
    }

    public function render()
    {
        // No childs are rendered if it is hidden
        if($this->getElement()->getAttribute("type") !== "hidden"){
            Renderable_Node::renderRenderedTo($this->renderChilds(), $this->getElement());
        }
        return Rendered::of($this->getElement());
    }

    /**
     * @param callable $builder
     * @return FormControl_Select
     */
    public static function add($builder)
    {
        $fc = new FormControl_Select($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}

