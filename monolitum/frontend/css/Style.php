<?php
namespace monolitum\frontend\css;

// https://github.com/phax/ph-css/blob/master/ph-css/src/main/java/com/helger/css/property/CCSSProperties.java

class Style
{

    /**
     * @var array<string, CSSProperty>
     */
    private $properties = [];

    /**
     * @param SizeAutoProperty $width
     * @return $this
     */
    public function width(SizeAutoProperty $width)
    {
        $this->properties["width"] = $width;
        return $this;
    }

    /**
     * @param SizeAutoProperty $width
     * @return $this
     */
    public function maxWidth(SizeAutoProperty $width)
    {
        $this->properties["max-width"] = $width;
        return $this;
    }

    /**
     * @param SizeAutoProperty $width
     * @return $this
     */
    public function minWidth(SizeAutoProperty $width)
    {
        $this->properties["min-width"] = $width;
        return $this;
    }

    /**
     * @param SizeAutoProperty $height
     * @return $this
     */
    public function height(SizeAutoProperty $height)
    {
        $this->properties["height"] = $height;
        return $this;
    }

    /**
     * @param SizeAutoProperty $height
     * @return $this
     */
    public function maxHeight(SizeAutoProperty $height)
    {
        $this->properties["max-height"] = $height;
        return $this;
    }

    /**
     * @param SizeAutoProperty $height
     * @return $this
     */
    public function minHeight(SizeAutoProperty $height)
    {
        $this->properties["min-height"] = $height;
        return $this;
    }

    /**
     * @return string
     */
    public function write(){
        $declarations = [];

        foreach ( $this->properties as $key => $value ) {
            $declarations[] = trim( $key ) . ': ' . trim( $value->write() ) . ';';
        }

        return implode( '', $declarations );

    }


}