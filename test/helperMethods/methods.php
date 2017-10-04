<?php
namespace noAjax\test\helperMethods;
use noAjax\Exception\ArgumentMismatchException;

class NoAjaxClass{
    public static function returnSuccess(){
        return 'success';
    }
    
    public static function returnNothing(){
        $a = 2 + 2;
        // Some server side actions are done
        // return nothing
    }
    
    public static function concatenateString($s1, $s2){
        if(!$s1 || !$s2)
            throw new \Exception('2 arguments are required');
        return $s1 . $s2;
    }
    
    public static function add3Numbers($n1, $n2, $n3){
        if(!isset($n1) || !isset($n2) || !isset($n3))
             throw new ArgumentMismatchException('3 arguments are required');
        return $n1 + $n2 + $n3;
    }   
}
