<?php

namespace monolitum\backend\params;

use monolitum\core\GlobalContext;
use monolitum\backend\Manager;
use monolitum\core\panic\BreakExecution;

class Manager_Redirect extends Manager
{

    /**
     * @var Link
     */
    private $redirectLink = null;

    /**
     * @var Active_SetResourceData
     */
    private $resourceData = null;

    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    protected function receiveActive($active)
    {

        if($active instanceof Active_SetRedirectPath){
            $activePath = $active->getPathOrLink();
            if($activePath instanceof Path){
                if(!$this->redirectLink)
                    $this->redirectLink = new Link();
                $this->redirectLink->setPath($activePath);
            }else{
                $this->redirectLink = $activePath;
            }
            $this->resourceData = null;

            return true;
        }else if($active instanceof Active_SetResourceData){
            $this->resourceData = $active;
            $this->redirectLink = null;

            return true;
        }
        return parent::receiveActive($active);
    }

    /**
     * @throws BreakExecution
     */
    protected function executeNode()
    {
        if($this->redirectLink !== null){

            $active = new Active_Make_Url($this->redirectLink);
            GlobalContext::add($active);

            $url = $active->getUrl();

            header("HTTP/1.1 303 See Other");
            header("Location: " . $url);

            // NOTE: Execution is finished here
            throw new BreakExecution();

        }else if($this->resourceData !== null){

            $base64Data = $this->resourceData->getDataBase64();
            if($base64Data !== null){
                header('Content-Type: ' . "application/octet-stream");
                echo $base64Data;
            }else{
                $callable = $this->resourceData->getWriterFunction();
                if(is_callable($callable)){
                    header('Content-Type: ' . "application/octet-stream");
                    $callable();
                }
            }

            // NOTE: Execution is finished here
            throw new BreakExecution();

        }

        parent::executeNode();
    }

    /**
     * @param callable|null $builder
     */
    public static function add($builder)
    {
        GlobalContext::add(new Manager_Redirect($builder));
    }

}
