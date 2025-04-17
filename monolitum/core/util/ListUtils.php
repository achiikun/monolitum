<?php

namespace monolitum\core\util;

class ListUtils
{

    /**
     * @param array $array
     * @param mixed $element
     * @param int|null $idx
     * @return void
     */
    public static function insertAnElementIntoAnArray(&$array, $element, $idx = null)
    {
        if ($idx !== null) {
            if ($array === null) {
                $array = $element;
            } else {
                if (!is_array($array))
                    $array = [$array];
                array_splice($array, $idx, 0, [$element]);
            }
        } else {
            if ($array === null) {
                $array = $element;
            } else if (!is_array($array)) {
                $array = [$array, $element];
            } else {
                $array[] = $element;
            }
        }
    }
}
