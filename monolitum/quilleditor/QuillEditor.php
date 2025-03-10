<?php

namespace monolitum\quilleditor;

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
use monolitum\wangeditor\WangEditor;

class QuillEditor extends ElementComponent
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
    private $style = 'snow';

    /**
     * @var string
     */
    private $container_id;

    /**
     * @var string
     */
    private $placeholder;

    public function __construct($builder = null)
    {
        parent::__construct(new HtmlElement("input"), $builder);
        $this->getElement()->setAttribute("type", "hidden");
    }

    public function setValue($content)
    {
        $this->html_content = $content;

        $element = $this->getElement();
        $element->setAttribute("value", $content, true);

        return $this;
    }

    public function setContent($content, $raw=false)
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

    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;
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
        if(!$page->getConstant("quilleditor-js-css")){
            CSSLink::addLocal(Path::ofRelativeToClass(QuillEditor::class,"res", "quill.snow.css"));
            JSScript::addLocal(Path::ofRelativeToClass(QuillEditor::class,"res", "quill.js"));
            $page->setConstant("quilleditor-js-css");
        }

        $this->editor_id = $this->getId();
        if($this->editor_id === null){
            $this->editor_id = Active_NewId::go_newId();
            $this->setId($this->editor_id);
        }

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
//                ->setAttribute("style", "border: 1px solid #ccc;z-index: 100;")
                ->addChildElement((new HtmlElement("div"))
                    ->setId($this->container_id)
                    ->setAttribute("style", "height: 500px;")
                    ->setRequireEndTag(true)
                )
                ->addChildElement((new HtmlElement("script"))
                    ->setContent((new HtmlElementContent("
                    
                        const BlockEmbed = Quill.import('blots/block/embed');
                        
//                        class Hr extends BlockEmbed {
//                          static blotName = 'divider';
//                          static className = 'my-hr';
//                          static tagName = 'hr';
//                            static create(value) {
//                                let node = super.create(value);
//                                // give it some margin
//                                node.setAttribute('style', \"height:0px; margin-top:10px; margin-bottom:10px;\");
//                                return node;
//                            }
//                        }
//                        var customHrHandler = function(){
//                            // get the position of the cursor
//                            var range = quill.getSelection();
//                            if (range) {
//                                // insert the <hr> where the cursor is
//                                quill.insertEmbed(range.index,\"hr\",\"null\")
//                            }
//                        }
//                        
//                        Quill.register({
//                            'formats/hr': Hr
//                        });
                        class DividerBlot extends BlockEmbed {
                            static blotName = 'divider';
                            static tagName = 'hr';
                        }
                        Quill.register(DividerBlot);
                        
                        var icons = Quill.import('ui/icons');
//                        icons['divider'] = '<i class=\"fa fa-grip-lines\" aria-hidden=\"true\"></i>';
                        icons['divider'] = '<i class=\"fa fa-slash\" style=\"-webkit-transform: rotate(142deg);transform: rotate(142deg);\" aria-hidden=\"true\"></i>';

                        const toolbarOptions = [
                          ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                          ['blockquote', 'code-block', {'divider': 'hr'}],
                          ['link', 'image'],//, 'video', 'formula'],
                        
                          [{ 'header': 1 }, { 'header': 2 }],               // custom button values
                          [{ 'list': 'ordered'}, { 'list': 'bullet' }, { 'list': 'check' }],
                          [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
                          [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
                          [{ 'direction': 'rtl' }],                         // text direction
                        
                          [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
                          [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        
                          [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
                          [{ 'font': [] }],
                          [{ 'align': [] }],
                        
                          ['clean']                                         // remove formatting button
                        ];
                            const options = {
                            " . (
                                $this->placeholder !== null
                                    ? "placeholder: \""
                                        . addslashes($this->placeholder)
                                        . "\","
                                    : ""
                                ) . "
                              readOnly: " . ($isDisabled ? "true" : "false") . ",
                              modules: {
                                toolbar: toolbarOptions
                              },
                              theme: 'snow'
                            };
                          const quill = new Quill('#" . $this->container_id . "', options);
                          
                          quill.getModule('toolbar').handlers.divider = (value) => {
                            const selection = quill.getSelection(focus = true);
                            let position = 0;
                            // divider will replace any selected text
                            if (!!selection.length) {
                                quill.deleteText(selection);
                            }
                            // if last position in editor, add newline after divider (caret will not be after hr otherwise)
                            if (selection.index === quill.getLength() - 1) {
                                quill.insertText(selection.index, '\\n')
                            }
                            // if at end of block, insert hr after newline character (quill will add a newline after divider otherwise)
//                            if (JSON.stringify(quill.getContents(selection.index, 1)) == JSON.stringify({ ops: [{ insert: \"\\n\" }] })) {
//                                position = selection.index + 1;
//                            } else {
                                position = selection.index;
//                            }
                            quill.insertEmbed(position, 'divider', true);
                            // move selection after divider
                            quill.setSelection(selection.index + 1);
                        }
                        
                         quill.on('text-change', (delta, oldDelta, source) => {
                              if (source == 'api') {
                                console.log('An API call triggered this change.');
                              } else if (source == 'user') {
                                document.getElementById('" . $this->editor_id . "').value = JSON.stringify(quill.getContents().ops);
                                console.log('A user action triggered this change.');
                                console.log(JSON.stringify(quill.getContents().ops));
                              }
                            });
                            var contents = document.getElementById('" . $this->editor_id . "').value;
                            console.log(contents);
                            if(contents) quill.setContents(//new Delta(
                                JSON.parse(contents)
                            //)
                            );
                    "))->setRaw(true))
                )
        ]);

    }

    /**
     * @param callable $builder
     * @return QuillEditor
     */
    public static function add($builder = null)
    {
        $fc = new QuillEditor($builder);
        GlobalContext::add($fc);
        return $fc;
    }

}