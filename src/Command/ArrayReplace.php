<?php
/**
 * Created by PhpStorm.
 * User: Jure
 * Date: 10. 05. 2018
 * Time: 13:24
 */

namespace BOF\Command;


class ArrayReplace
{

    /**
     * @param $array                Array to modify
     * @param $replace string       String to replace
     * @return array                Return modified Array
     */
    public static function arrayReplace($array, $replace)
    {
        if(is_array($array)){
            foreach($array as $Key=>$Val) {
                if(is_array($array[$Key])){
                    $array[$Key] = self::arrayReplace($array[$Key], $replace);
                }else{
                    if(is_null($Val)) $array[$Key] = $replace;
                }
            }
        }
        return $array;
    }

}