<?php

namespace monolitum\entity\values;

use balukova\entities\ContacteEtiqueta;

class Color
{

    /** @var int */
    private $red, $green, $blue, $alpha = null;

    /**
     * @param int $red
     * @param int $green
     * @param int $blue
     * @param int $alpha
     */
    public function __construct($red=0, $green=0, $blue=0, $alpha=null)
    {
        $this->red = self::fix_rgb_value($red);
        $this->green = self::fix_rgb_value($green);
        $this->blue = self::fix_rgb_value($blue);
        if($alpha !== null)
            $this->alpha = self::fix_rgb_value($alpha);

    }

    /**
     * @return int|mixed
     */
    public function getRed()
    {
        return $this->red;
    }

    /**
     * @param int|mixed $red
     */
    public function setRed($red)
    {
        $this->red = self::fix_rgb_value($red);
    }

    /**
     * @return int|mixed
     */
    public function getGreen()
    {
        return $this->green;
    }

    /**
     * @param int|mixed $green
     */
    public function setGreen($green)
    {
        $this->green = self::fix_rgb_value($green);
    }

    /**
     * @return int|mixed
     */
    public function getBlue()
    {
        return $this->blue;
    }

    /**
     * @param int|mixed $blue
     */
    public function setBlue($blue)
    {
        $this->blue = self::fix_rgb_value($blue);
    }

    /**
     * @return int|mixed|null
     */
    public function getAlpha()
    {
        return $this->alpha;
    }

    /**
     * @param int|mixed|null $alpha
     */
    public function setAlpha($alpha)
    {
        $this->alpha = self::fix_rgb_value($alpha);
    }



    /**
     * Set the colour's red, green and blue values
     *
     * @access public
     * @param integer|bool|null $red [optional] The red value of the colour (between 0 and 255). Default value is 0.
     * @param integer|bool|null $green [optional] The green value of the colour (between 0 and 255). Default value is 0.
     * @param integer|bool|null $blue [optional] The blue value of the colour (between 0 and 255). Default value is 0.
     * @param integer|bool|null $alpha [optional] The blue value of the colour (between 0 and 255). Default value is 0.
     */
    public function set($red=0, $green=0, $blue=0, $alpha=null){

        // add values
        if($red !== null) {
            if($this->red === false)
                $this->red = 0;
            else
                $this->red = self::fix_rgb_value($red);
        }
        if($green !== null) {
            if($this->green === false)
                $this->green = 0;
            else
                $this->green = self::fix_rgb_value($green);
        }
        if($blue !== null) {
            if($this->blue === false)
                $this->blue = 0;
            else
            $this->blue = self::fix_rgb_value($blue);
        }

        if($alpha !== null){
            if($this->alpha === false)
                $this->alpha = null;
            else
                $this->alpha = self::fix_rgb_value($alpha);
        }

    }

    /**
     * Modify the colour's red, green and blue values.
     *
     * @access public
     * @param integer $red [optional] The amount to modify the red value of the colour (between -255 and 255). Default value is 0.
     * @param integer $green [optional] The amount to modify the green value of the colour (between -255 and 255). Default value is 0.
     * @param integer $blue [optional] The amount to modify the blue value of the colour (between -255 and 255). Default value is 0.
     */
    public function add($red=0, $green=0, $blue=0, $alpha=null){

        // add values
        if($red !== null) $this->red = self::fix_rgb_value($this->red + (int)$red);
        if($green !== null) $this->green = self::fix_rgb_value($this->green + (int)$green);
        if($blue !== null) $this->blue = self::fix_rgb_value($this->blue + (int)$blue);

        if($alpha !== null){
            if($this->alpha === null)
                $this->alpha = 255;
            $this->alpha = self::fix_rgb_value($this->alpha + (int)$alpha);
        }

    }

    public static function ofRandom(){
        return new Color(rand(0, 255),rand(0, 255),rand(0, 255));
    }

    /**
     * Get the HEX code that represents the colour.
     *
     * @access public
     * @param boolean $hash Whether to prepend the HEX code with a '#' character. Default value is FALSE.
     * @return string Returns the HEX code.
     */
    public function getHexValue($hash=false){

        // convert rgb to hex
        $red = str_pad(dechex(self::fix_rgb_value($this->red)), 2, '0', STR_PAD_LEFT);
        $green = str_pad(dechex(self::fix_rgb_value($this->green)), 2, '0', STR_PAD_LEFT);
        $blue = str_pad(dechex(self::fix_rgb_value($this->blue)), 2, '0', STR_PAD_LEFT);

        if($this->alpha !== null){
            $alpha = str_pad(dechex(self::fix_rgb_value($this->alpha)), 2, '0', STR_PAD_LEFT);
            // concat and return
            return ($hash ? '#' : '') . $red . $green . $blue . $alpha;
        }else{
            // concat and return
            return ($hash ? '#' : '') . $red . $green . $blue;
        }

    }


    static function black($alpha=255){
        return new Color(0,0,0, $alpha);
    }

    static function white($alpha=255){
        return new Color(255,255,255, $alpha);
    }

    static function ofHex($hex){

        // trim the '#' character
        $hex = ltrim((string)$hex, '#');

        $red = null;
        $green = null;
        $blue = null;
        $alpha = null;

        // what kind of code do we have?
        if (strlen($hex)==8){

            // parse 6-character code into array
            $red = $hex[0] . $hex[1];
            $green = $hex[2] . $hex[3];
            $blue = $hex[4] . $hex[5];
            $alpha = $hex[6] . $hex[7];

        }
        else if (strlen($hex)==6){

            // parse 6-character code into array
            $red = $hex[0] . $hex[1];
            $green = $hex[2] . $hex[3];
            $blue = $hex[4] . $hex[5];

        }
        else if (strlen($hex)==3){

            // parse 3 character code into array
            $red = $hex[0] . $hex[0];
            $green = $hex[1] . $hex[1];
            $blue = $hex[2] . $hex[2];

        }
        else{

            // invalid code... oops
            return new Color();

        }

        return new Color(
            hexdec($red),
            hexdec($green),
            hexdec($blue),
            hexdec($alpha)
        );

    }


    /**
     * Fix a colour value (round and keep between 0 and 255).
     *
     * @access protected
     * @param integer $value The value to fix.
     */
    protected static function fix_rgb_value($value){

        // returned fixed value
        return max(min(round((int)$value), 255), 0);

    }


}