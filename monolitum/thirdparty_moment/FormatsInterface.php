<?php

namespace monolitum\thirdparty_moment;

/**
 * Interface FormatsInterface
 * @package Moment
 */
interface FormatsInterface
{
    /**
     * @param string $format
     *
     * @return FormatsInterface
     */
    public function format($format);

    /**
     * @param array $customFormats
     *
     * @return FormatsInterface
     */
    public function setTokens(array $customFormats);
}