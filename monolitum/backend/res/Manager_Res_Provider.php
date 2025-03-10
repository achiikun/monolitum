<?php

namespace monolitum\backend\res;

use Exception;
use monolitum\core\GlobalContext;
use monolitum\entity\ValidatedValue;
use monolitum\backend\Manager;
use monolitum\backend\params\Active_Path2UrlPath;
use monolitum\backend\params\Active_Url2Path;
use monolitum\backend\params\Param;
use monolitum\backend\params\Path;
use monolitum\core\panic\DevPanic;

class Manager_Res_Provider extends Manager
{

    /**
     * @var Param
     */
    private $readResourceParam;

    /**
     * @var array<string, AllowedExtension>
     */
    private $allowedExtensions = [];

    /**
     * @var Path
     */
    private $filePath;

    /**
     * @var false|string
     */
    private $fileMime;

    /**
     * @var false|string
     */
    private $fileContents;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var false|int
     */
    private $fileLastModified;

    /**
     * @var string
     */
    private $fileLastModifiedString;

    /**
     * @var bool
     */
    private $notModifiedFlag = false;

    /**
     * @var AllowedExtension
     */
    private $fileAllowedExtension;

    /**
     * @param callable|null $builder
     */
    public function __construct($builder = null)
    {
        parent::__construct($builder);
    }

    /**
     * @param Param $readResourceParam
     */
    public function setReadResourceParam($readResourceParam)
    {
        $this->readResourceParam = $readResourceParam;
    }

    /**
     * @param string[] $allowedExtensions
     */
    public function addAllowedExtensions($allowedExtensions)
    {
        foreach ($allowedExtensions as $allowedExtension){
            $this->allowedExtensions[$allowedExtension] = new AllowedExtension();
        }
    }

    /**
     * @param string $extension
     * @param AllowedExtension $allowedExtension
     */
    public function addAllowedExtension($extension, $allowedExtension)
    {
        $this->allowedExtensions[$extension] = $allowedExtension;
    }

    protected function afterBuildNode()
    {

        /** @var ValidatedValue $validatedValue */
        $validatedValue = $this->readResourceParam->getValidatedValue();
        if($validatedValue->isValid() && !$validatedValue->isNull()){

            $active = new Active_Url2Path($validatedValue->getValue());
            GlobalContext::add($active);
            $this->filePath = $active->getPath();
            $path = $this->filePath->getPath();

            $len = count($path);
            if($len > 0){
                $fileName = $path[$len-1];

                foreach ($this->allowedExtensions as $string => $allowedExtension){
                    if($this->endsWith($fileName, $string)){
                        $this->fileAllowedExtension = $allowedExtension;
                        break;
                    }
                }

                if(!$this->fileAllowedExtension)
                    throw new DevPanic("Resource not found.");

                $active = new Active_Path2UrlPath($this->filePath, false);
                GlobalContext::add($active);
                $resolvedUrl = $active->getUrl();

                $this->fileName = GlobalContext::getResourcesAddressResolver()->resolve($resolvedUrl);

                try{

                    $this->fileMime = mime_content_type($this->fileName);
                    $this->fileLastModified = filemtime($this->fileName);

                    $this->fileLastModifiedString = gmdate('D, d M Y H:i:s ',  $this->fileLastModified) . 'GMT';

                    if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
                        //echo 'set modified header';
                        if(intval($this->fileLastModified) >= intval(strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']))) {
                            $this->notModifiedFlag = true;
                        }
                    }

                }catch (Exception $e){
                    throw new DevPanic("Resource not found.");
                }

            }


        }else{
            throw new DevPanic("Resource not found.");
        }

//        parent::afterBuildNode();
    }

    function endsWith( $haystack, $needle ) {
        $length = strlen( $needle );
        if( !$length ) {
            return true;
        }
        return substr( $haystack, -$length ) === $needle;
    }

    protected function executeManager()
    {
        $etag = md5($this->fileLastModified);

        header("Last-Modified: $this->fileLastModifiedString");
        header("ETag: \"{$etag}\"");

        // Expires must be set, because browser flashes when a css is request and it's load after html.
        $expires = gmdate('D, d M Y H:i:s ', time() + 3600 * 24) . 'GMT';
        header("Expires: $expires");

        if ($this->notModifiedFlag) {
            header('HTTP/1.1 304 Not Modified');
            return;
        }

        $mimeType = $this->fileAllowedExtension->getMimeType();

        if ($mimeType !== null) {
            header('Content-Type: ' . $mimeType);
        } else if ($this->fileMime) {
            header('Content-Type: ' . $this->fileMime);
        }
        //echo $this->fileContents;

        $rewriter = $this->fileAllowedExtension->getRewriter($this->filePath);

        if($rewriter === null){
            readfile($this->fileName);
        }else if($this->fileAllowedExtension->readLineByLine()){

            $handle = fopen($this->fileName, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    // process the line read.
                    echo $rewriter($line);
                }

                fclose($handle);
            }

        }else{
            echo $rewriter(file_get_contents($this->fileName));
        }




        parent::executeManager(); // TODO: Change the autogenerated stub
    }

    /**
     * @param callable|null $builder
     */
    public static function add($builder)
    {
        GlobalContext::add(new Manager_Res_Provider($builder));
    }

}
