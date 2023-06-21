<?php

namespace monolitum\bootstrap\datatable;

use monolitum\backend\params\Link;
use monolitum\backend\params\Path;
use monolitum\bootstrap\A;
use monolitum\bootstrap\BSElementComponent;
use monolitum\bootstrap\Div;
use monolitum\bootstrap\Ext_Form_InputGroup;
use monolitum\bootstrap\FormControl_Select;
use monolitum\bootstrap\FormControl_Select_Option;
use monolitum\bootstrap\FormSubmit;
use monolitum\bootstrap\Li;
use monolitum\bootstrap\values\BSDisplay;
use monolitum\core\GlobalContext;
use monolitum\core\panic\DevPanic;
use monolitum\frontend\css\CSSSize;
use monolitum\frontend\form\Form;
use monolitum\frontend\html\HtmlElement;

class Pagination extends BSElementComponent
{

    /**
     * @var string
     */
    private $prevText = null;

    /**
     * @var string
     */
    private $nextText = null;

    /**
     * @var string|null
     */
    private $firstText = null;

    /**
     * @var string|null
     */
    private $lastText = null;

    private $maxDisplayedPages = 5;

    /**
     * @var int
     */
    private $page;
    /**
     * @var int
     */
    private $items_per_page;
    /**
     * @var int
     */
    private $total;

    /**
     * @var string
     */
    private $param_page;

    private $comboboxButtonText;
    /**
     * @var int
     */
    private $max_pages;

    public function __construct($builder)
    {
        parent::__construct(new HtmlElement("div"), $builder);
    }

    public static function correctPageNumber($page, $items_per_page, $total)
    {

        $max_pages = intval($total/$items_per_page);
        if($total % $items_per_page > 0)
            $max_pages++;

        if($max_pages === 0)
            return 1;

        if($page > $max_pages)
            $page = $max_pages;
        else if($page < 1)
            $page = 1;

        return $page;

    }

    /**
     * @param string|null $nextText
     * @return $this
     */
    public function nextText($nextText)
    {
        $this->nextText = $nextText;
        return $this;
    }

    /**
     * @param string|null $prevText
     * @return $this
     */
    public function prevText($prevText)
    {
        $this->prevText = $prevText;
        return $this;
    }

    /**
     * @param string|null $firstText
     * @return $this
     */
    public function firstText($firstText)
    {
        $this->firstText = $firstText;
        return $this;
    }

    /**
     * @param string|null $lastText
     * @return $this
     */
    public function lastText($lastText)
    {
        $this->lastText = $lastText;
        return $this;
    }

    /**
     * @param int $page
     * @param int $items_per_page
     * @param int $total
     * @return void
     */
    public function setValues($page, $items_per_page, $total)
    {
        $this->page = $page;
        $this->items_per_page = $items_per_page;
        $this->total = $total;

    }

    /**
     * @param string $page
     * @return void
     */
    public function setParam($page)
    {
        $this->param_page = $page;
    }

    public function enableCombobox($string)
    {
        $this->comboboxButtonText = $string;
        return $this;
    }

    protected function afterBuildNode()
    {
        // check values
        if($this->items_per_page <= 0)
            $this->items_per_page = 10;

        $max_pages = intval($this->total/$this->items_per_page);
        if($this->total % $this->items_per_page > 0)
            $max_pages++;
        $this->max_pages = $max_pages;

        if($this->page >= $max_pages)
            $this->page = $max_pages;
        else if($this->page < 1)
            $this->page = 1;

        $hasFirst = $this->page > 1;
        $hasPrevious = $this->page > 2;

        $hasNext = $this->page < $max_pages-1;
        $hasLast = $this->page < $max_pages;

        $ul = new BSElementComponent(new HtmlElement("ul"));
        $ul->addClass("pagination");
        $ul->bsStyle()->display(BSDisplay::inline_flex());

        if($hasFirst){
            $ul->append($this->makeItem(
                $this->firstText !== null ? $this->firstText : "<<",
                Link::of(Path::ofRelative())
                    ->setCopyAllParams()
                    ->addParams([
                        $this->param_page => 0
                    ])
            ));
        }

        if($hasPrevious){
            $ul->append($this->makeItem(
                $this->prevText !== null ? $this->prevText : "<",
                Link::of(Path::ofRelative())
                    ->setCopyAllParams()
                    ->addParams([
                        $this->param_page => $this->page-1
                    ])
            ));
        }

        // pages
        $nPages = min($max_pages, $this->maxDisplayedPages);

        $halfPages = intval($nPages/2);

        if($this->page <= $halfPages){
            $first = 1;
            $last = $first + $nPages - 1;
        }else if($this->page >= $max_pages-($halfPages)){
            $last = $max_pages;
            $first = $last - $nPages + 1;
        }else{
            $first = $this->page - ($halfPages);
            $last = $first + $nPages - 1;
        }

        for($i = $first; $i <= $last; $i++){
            $ul->append($this->makeItem(
                strval($i),
                Link::of(Path::ofRelative())
                    ->setCopyAllParams()
                    ->addParams([
                        $this->param_page => $i
                    ]),
                $this->page === $i
            ));
        }

        if($hasNext){
            $ul->append($this->makeItem(
                $this->nextText !== null ? $this->nextText : ">",
                Link::of(Path::ofRelative())
                    ->setCopyAllParams()
                    ->addParams([
                        $this->param_page => $this->page+1
                    ])
            ));
        }

        if($hasLast){
            $ul->append($this->makeItem(
                $this->lastText !== null ? $this->lastText : ">>",
                Link::of(Path::ofRelative())
                    ->setCopyAllParams()
                    ->addParams([
                        $this->param_page => $max_pages
                    ])
            ));
        }

        $this->append($ul);

        if($this->comboboxButtonText !== null)
            $this->append($this->makeCombo());

        parent::afterBuildNode(); // TODO: Change the autogenerated stub
    }

    function onNotReceived()
    {
        throw new DevPanic();
    }

    /**
     * @return Pagination
     */
    public static function add($builder)
    {
        $p = new Pagination($builder);
        GlobalContext::add($p);
        return $p;
    }

    /**
     * @param string|BSElementComponent $param
     * @param Link $link
     * @param bool $isActive
     * @return Li
     */
    private function makeItem($param, $link, $isActive = false)
    {
        $li = new Li();
        $li->addClass("page-item");

        $a = new A();
        $a->addClass("page-link");
        if($isActive)
            $a->addClass("active");
        $a->append($param);
        $a->setHref($link);

        $li->append($a);

        return $li;
    }

    /**
     * @return BSElementComponent
     */
    private function makeCombo()
    {

        return new Div(function (Div $it) {
            $it->bsStyle()->display(BSDisplay::inline_flex());

            Form::add(null, function (Form $it) {

                $it->setMethodGET();
                $it->setDefaultValue($this->param_page, $this->page);
                $it->setLink(Link::of(Path::ofRelative())->setCopyParamsExcept($this->param_page));

                Div::add(function (Div $it) {

                    Ext_Form_InputGroup::add();

                    $it->bsStyle()->paddingLeft(2);

                    FormControl_Select::add(function (FormControl_Select $it) {

                        $it->style()->width(CSSSize::px(80));
                        $it->setName($this->param_page);

                        for ($i = 1; $i <= $this->max_pages; $i++) {

                            FormControl_Select_Option::add(function (FormControl_Select_Option $it) use ($i) {
                                $it->setContent(strval($i));
                                $it->setValue(strval($i));
                                if ($this->page === $i)
                                    $it->setSelected();
                            });

                        }

                    });

                    FormSubmit::add(function (FormSubmit $it) {
                        $it->setContent($this->comboboxButtonText);
                    });

                });

            });

        });
    }

}