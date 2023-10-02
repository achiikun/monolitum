<?php

namespace monolitum\bootstrap;

interface Menu_Item_Holder
{

    /**
     * @return bool
     */
    function openToLeft();

    /**
     * @return bool
     */
    function isSubmenu();

    /**
     * @return bool
     */
    function isNav();

}