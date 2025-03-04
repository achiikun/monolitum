<?php

namespace monolitum\backend\params;

use monolitum\entity\Model;

class Active_Make_Url_WithPushParameters extends Active_Make_Url
{

    /**
     * @var array<string, Model>
     */
    private $pushedParams = [];

    public function __construct($link, $obtainParamsAlone = false)
    {
        parent::__construct($link, $obtainParamsAlone);
    }

    /**
     * @param Model[] $pushedParams
     * @return Active_Make_Url_WithPushParameters
     */
    public function addPushedParams($pushedParams)
    {
        $this->pushedParams += $pushedParams;
        return $this;
    }

    /**
     * @return array<string, Model>
     */
    public function getPushedParams()
    {
        return $this->pushedParams;
    }


}
