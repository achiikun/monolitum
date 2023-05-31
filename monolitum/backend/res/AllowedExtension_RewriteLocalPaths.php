<?php

namespace monolitum\backend\res;

use monolitum\core\GlobalContext;
use monolitum\backend\params\Path;

class AllowedExtension_RewriteLocalPaths extends AllowedExtension
{

    public function readLineByLine()
    {
        return true;
    }

    function startsWith ($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    /**
     * @param Path $path
     * @return callable|null
     */
    public function getRewriter(&$path)
    {
        return function ($line) use ($path) {

            return preg_replace_callback(

                    '/url\((["\'])(((?!(:\/\/)|"|\').)*)(["\'])\)/',
                    function ($matches) use ($path) {

                        $matchedString = $matches[2];

                        if($this->startsWith($matchedString, "data:")){
                            // Do nothing
                            return 'url(' . $matchedString . ')';
                        }else{

                            $matchedStringSplitBySlash = preg_split('/\//', $matchedString);

                            if($matchedStringSplitBySlash[0] === ""){
                                $currentPath = [];
                            }else{
                                $currentPath = $path->getPath();
                                // Remove the file name
                                array_pop($currentPath);
                            }

                            foreach ($matchedStringSplitBySlash as $s){
                                if($s !== ""){
                                    if($s === ".."){
                                        if(count($currentPath) > 0)
                                            array_pop($currentPath);
                                    }else{
                                        $currentPath[] = $s;
                                    }
                                }
                            }

                            $active = new Active_Resolve_Res(Path::of(...$currentPath));
                            $active->setEncodeUrl(false);
                            GlobalContext::add($active, $this->getManager());
                            $resolvedString = $active->getResResolver()->resolve();

                            return 'url(' . $matches[1] . $resolvedString . $matches[5] . ')';
                        }

                    },
                $line
            );

            /*$htmlString = preg_replace_callback_array(
                [
                    '/(href="?)(\S+)("?)/i' => function (&$matches) {
                        return $matches[1] . urldecode($matches[2]) . $matches[3];
                    },
                    '/(href="?\S+)(%24)(\S+)?"?/i' => function (&$matches) {
                        return urldecode($matches[1] . '$' . $matches[3]);
                    }
                ],
                $htmlString
            );*/


        };
    }

}