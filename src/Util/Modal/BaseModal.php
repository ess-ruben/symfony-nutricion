<?php

namespace App\Util\Modal;

abstract class BaseModal {

    protected function lineToEntity($line,$entity,$replace = []){
        $array = explode("\\",get_class($entity));
        $name = $array[count($array) - 1];
  
        foreach ($line as $key => $value) {
          if (isset($replace[$key])) {
            $key = $replace[$key];
          }
  
          if ($key == "") {
            continue;
          }
  
          $key = $this->attrToSetFun($key);
          if (!method_exists($entity,$key)) {
            continue;
          }
  
          if (is_float($value)) {
            $value = floatval($value);
          }
  
          if ($value == "no") {
            $value = false;
          }
  
          if ($value == "yes") {
            $value = true;
          }
  
          $entity->{$key}($value);
        }
  
        return $entity;
      }
  
      protected function attrToSetFun(string $str){
        $fun = "set";
        $array = explode("_",$str);
        foreach ($array as $value) {
          $fun .= ucfirst($value);
        }
        return $fun;
      }
  
      protected function setStringToTime($line,string $key){
        if (
          isset($line[$key]) &&
          $line[$key] != null &&
          $line[$key] != "0000-00-00 00:00:00" &&
          $line[$key] != "0000-00-00"
        ) {
          return new \DateTime($line[$key]);
        }
        return NULL;
      }
}