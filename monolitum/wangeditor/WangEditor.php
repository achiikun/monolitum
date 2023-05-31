<?php

namespace monolitum\wangeditor;

use monolitum\backend\globals\Active_NewId;
use monolitum\backend\params\Path;
use monolitum\bootstrap\BSPage;
use monolitum\core\Find;
use monolitum\core\GlobalContext;
use monolitum\frontend\component\CSSLink;
use monolitum\frontend\component\JSScript;
use monolitum\frontend\ElementComponent;
use monolitum\frontend\html\HtmlElement;
use monolitum\frontend\html\HtmlElementContent;
use monolitum\frontend\Rendered;

class WangEditor extends ElementComponent
{

    /**
     * @var string
     */
    private $editor_id;
    /**
     * @var string
     */
    private $html_content;

    /**
     * @var string
     */
    private $style = 'simple';

    /**
     * @var string
     */
    private $toolbar_id;
    /**
     * @var string
     */
    private $container_id;

    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement("input"), $builder);
        $this->getElement()->setAttribute("type", "hidden");
    }

    public function setValue($content)
    {
        $this->html_content = $content;

        $element = $this->getElement();
        $element->setAttribute("value", $content);

        return $this;
    }

    public function setContent($content)
    {
        $this->setValue($content);
        return $this;
    }

    public function setName($name)
    {
        $this->getElement()->setAttribute("name", $name);
    }

    public function setDisabled($disabled = true)
    {
        $this->getElement()->setAttribute("disabled", $disabled ? "disabled" : null);
    }

    protected function buildComponent()
    {
        parent::buildComponent();

    }

    protected function afterBuildNode()
    {
        parent::afterBuildNode();

        /** @var BSPage $page */
        $page = Find::sync(BSPage::class);
        if(!$page->getConstant("wangeditor-js-css")){
            CSSLink::addLocal(Path::ofRelativeToClass(WangEditor::class,"res", "style.css"));
            JSScript::addLocal(Path::ofRelativeToClass(WangEditor::class,"res", "index.js"));
            $page->setConstant("wangeditor-js-css");
        }

        $this->editor_id = $this->getId();
        if($this->editor_id === null){
            $this->editor_id = Active_NewId::go_newId();
            $this->setId($this->editor_id);
        }

        $this->toolbar_id = Active_NewId::go_newId();
        $this->container_id = Active_NewId::go_newId();

    }

    /**
     * @return Rendered
     */
    public function render()
    {

        $isDisabled = $this->getElement()->getAttribute("disabled") != null;

        return Rendered::of([
            parent::render(),
            (new HtmlElement("div"))
                ->setAttribute("style", "border: 1px solid #ccc;z-index: 100;")
                ->addChildElement((new HtmlElement("div"))
                    ->setId($this->toolbar_id)
                    ->setAttribute("style", "border-bottom: 1px solid #ccc;")
                    ->setRequireEndTag(true)
                )
                ->addChildElement((new HtmlElement("div"))
                    ->setId($this->container_id)
                    ->setAttribute("style", "height: 500px;")
                    ->setRequireEndTag(true)
                )
                ->addChildElement((new HtmlElement("script"))
                    ->setContent((new HtmlElementContent("
                        const { createEditor, createToolbar, i18nChangeLanguage } = window.wangEditor
                        
                        i18nChangeLanguage('en')
                        
                        const editorConfig = {
                            placeholder: 'Type here...',
                            onCreated(editor) {
                                " . ($isDisabled ? "editor.disable()" : "") . "
                                editor.setHtml(document.getElementById('" . $this->editor_id . "').value)
                            },
                            onChange(editor) {
                              const html = editor.getHtml()
                              document.getElementById('" . $this->editor_id . "').value = html;
                            },
                            MENU_CONF: {},
                        }
                        
                        editorConfig.MENU_CONF['uploadImage'] = {
                            fieldName: 'your-fileName',
                            base64LimitSize: 10 * 1024 * 1024 // 10M 以下插入 base64
                        }
                        
                        const editor = createEditor({
                            selector: '#" . $this->container_id . "',
                            html: '<p><br></p>',
                            config: editorConfig,
                            mode: '" . $this->style . "', // 'default' or 'simple'
                        })
                        
                        console.log(editor.getMenuConfig('uploadImage'));
                        
                        const toolbarConfig = {}
                        
                        const toolbar = createToolbar({
                            editor,
                            selector: '#" . $this->toolbar_id . "',
                            config: toolbarConfig,
                            mode: '" . $this->style . "', // 'default' or 'simple'
                        })
                    "))->setRaw(true))
                )
        ]);

    }

    /**
     * @param callable $builder
     * @return WangEditor
     */
    public static function add($builder = null)
    {
        $fc = new WangEditor($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}