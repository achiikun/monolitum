<?php

namespace monolitum\bootstrap\datatable;

use monolitum\bootstrap\style\BSVerticalAlign;
use Iterator;
use monolitum\backend\params\Link;
use monolitum\backend\params\Manager_Params;
use monolitum\backend\params\Path;
use monolitum\backend\res\Active_Create_HrefResolver;
use monolitum\backend\res\HrefResolver;
use monolitum\core\GlobalContext;
use monolitum\core\Renderable_Node;
use monolitum\database\Query_Result;
use monolitum\entity\attr\Attr;
use monolitum\entity\Model;
use monolitum\frontend\Component;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\Rendered;

class DataTable extends ElementComponent
{

    /**
     * @var DataTable_Col[]
     */
    private $columns = [];

    /**
     * @var HrefResolver[]
     */
    private $columnHrefResolvers = [];

    /**
     * @var callable
     */
    private $rowRetriever;

    /**
     * @var array<array<Component|Rendered|string>>
     */
    private $rowComponents = [];

    /**
     * @var Model|string
     */
    private $sortable_model = null;

    /**
     * @var Attr|string
     */
    private $sortable_attr_sort = null;

    /**
     * @var Attr|string
     */
    private $sortable_attr_desc = null;

    /**
     * @var Link
     */
    private $sortable_base_link = null;

    /**
     * @var DataTable_Col
     */
    private $sortedColumn = null;

    /**
     * @var bool
     */
    private $sortedColumnDesc = null;

    /**
     * @param callable $builder
     */
    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement("table"), $builder);
        $this->addClass("table");
        $this->push(BSVerticalAlign::middle());
    }

    /**
     * @param callable $rowRetriever
     * @return void
     */
    public function retrieveRows($rowRetriever){
        $this->rowRetriever = $rowRetriever;
    }

    /**
     * @param class-string|Model $class
     * @param string|Attr $sort
     * @param string|Attr $desc
     * @return void
     */
    public function setSortableParams($class, $sort, $desc=null)
    {
        $this->sortable_model = $class;
        $this->sortable_attr_sort = $sort;
        $this->sortable_attr_desc = $desc;
    }

    /**
     * @param Link|null $sortable_base_link
     */
    public function setSortableBaseLink($sortable_base_link)
    {
        $this->sortable_base_link = $sortable_base_link;
    }

    /**
     * @return string|null
     */
    public function getSortedColumnId()
    {
        return $this->sortedColumn !== null ? $this->sortedColumn->getSortableId() : null;
    }

    /**
     * @return bool|null
     */
    public function getSortedColumnDesc()
    {
        return $this->sortedColumnDesc;
    }

    private function detectSorting()
    {
        if ($this->sortable_model !== null && $this->sortable_attr_sort !== null) {
            // Detect if it is sorted

            $sortValidatedValue = Manager_Params::go_findValidatedValue($this->sortable_model, $this->sortable_attr_sort);

            $sortedColumnName = null;
            if ($sortValidatedValue->isValid()) {
                $sortedColumnName = $sortValidatedValue->getValue();
            }

            if($sortedColumnName === null)
                return;

            $sortedColumn = null;
            foreach ($this->columns as $column){
                if($column->isSortable()){
                    if($column->getSortableId() === $sortedColumnName) {
                        $sortedColumn = $column;
                        break;
                    }
                }
            }

            if($sortedColumn === null)
                return;

            $descValidatedValue = $this->sortable_attr_desc !== null ? Manager_Params::go_findValidatedValue($this->sortable_model, $this->sortable_attr_desc) : null;

            $desc = false;
            if ($descValidatedValue->isValid() && !$descValidatedValue->isNull()) {
                $desc = $descValidatedValue->getValue();
            }

            $this->sortedColumn = $sortedColumn;
            $this->sortedColumnDesc = $desc;

        }
    }

    protected function receiveActive($active)
    {
        if($active instanceof DataTable_Col){
            $this->columns[] = $active;
            return true;
        }
        return parent::receiveActive($active);
    }

    protected function afterBuildNode()
    {
        $this->detectSorting();

        $baseLink = $this->sortable_base_link;
        if($baseLink === null)
            $baseLink = Link::of(Path::ofRelative())->setCopyAllParams();

        foreach ($this->columns as $column){
            if($column->isSortable()){

                $myLink = $baseLink->copy();
                $myLink->addParams([
                    $this->sortable_attr_sort => $column->getSortableId()
                ]);

                if($column === $this->sortedColumn && !$this->sortedColumnDesc){
                    $myLink->addParams([
                        $this->sortable_attr_desc => true
                    ]);
                }else{
                    $myLink->removeParams(
                        $this->sortable_attr_desc
                    );
                }

                $active = new Active_Create_HrefResolver($myLink);
                GlobalContext::add($active);
                $this->columnHrefResolvers[] = $active->getHrefResolver();
            }else{
                $this->columnHrefResolvers[] = null;
            }
        }

        // Build header

        if($this->rowRetriever != null){

            $callable = $this->rowRetriever;

            /** @var Query_Result $iterator */
            $iterator = $callable($this);

            while ($iterator->hasNext()){
                $entity = $iterator->next();
                
                $row = [];

                foreach($this->columns as $column){

                    $renderer = $column->getRenderer();

                    if($renderer instanceof CellRenderer){
                        $rendered = $renderer->render($entity);
                    }else if(is_callable($renderer)){
                        $rendered = $renderer($entity);
                    }else{
                        $rendered = Rendered::ofEmpty();
                    }

                    if(is_array($rendered)){
                        foreach ($rendered as $item) {
                            if($item instanceof Renderable_Node)
                                $this->buildChild($item);
                        }
                        $rendered = Rendered::of($rendered);
                    }else{

                        if($rendered instanceof Renderable_Node)
                            $this->buildChild($rendered);

                    }

                    $row[] = $rendered;

                }

                $this->rowComponents[] = $row;

            }

            $iterator->close();

        }

        parent::afterBuildNode();
    }

    public function render()
    {
        $element = $this->getElement();

        $thead = new HtmlElement("thead");
        $theadrow = new HtmlElement("tr");

        // Render header

        $i = 0;
        foreach($this->columns as $column){

            $th = new HtmlElement("th");
            if ($this->sortable_model !== null
                && $this->sortable_attr_sort !== null
                && $column->isSortable()){

                if($column === $this->sortedColumn){
                    if($this->sortedColumnDesc){
                        $th->addClass("sorting","sorting_desc");
                    }else{
                        $th->addClass("sorting","sorting_asc");
                    }
                }else{
                    $th->addClass("sorting","sorting_asc_disabled","sorting_desc_disabled");
                }

                $a = new HtmlElement("a");
                $a->setContent($column->getName());
                $a->setAttribute("href", $this->columnHrefResolvers[$i]->resolve());

                $th->addChildElement($a);
            }else{
                $th->setContent($column->getName());
            }
            $theadrow->addChildElement($th);

            $i++;
        }

        $thead->addChildElement($theadrow);
        $element->addChildElement($thead);

        $tbody = new HtmlElement("tbody");

        foreach($this->rowComponents as $row){

            $tbodyrow = new HtmlElement("tr");

            foreach ($row as $cell) {

                $td = new HtmlElement("td");
                if(is_string($cell)){
                    $td->setContent($cell);
                }else {
                    Renderable_Node::renderRenderedTo($cell, $td);
                }
                $tbodyrow->addChildElement($td);

            }

            $tbody->addChildElement($tbodyrow);

        }

        $element->addChildElement($tbody);

        return parent::render();
    }

    public static function add($builder)
    {
        $t = new DataTable($builder);
        GlobalContext::add($t);
        return $t;
    }


}