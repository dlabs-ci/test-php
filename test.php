<?php

$cars = array
  (
  array("Volvo",NULL,18),
  array("BMW",15,13),
  array("Saab",Null,2),
  array("Land Rover",17,15)
  );


function ArrayReplace($Array, $Replace){
  if(is_array($Array)){
 foreach($Array as $Key=>$Val) {
if(is_array($Array[$Key])){
   $Array[$Key] = ArrayReplace($Array[$Key], $Replace);
}else{
   if(is_null($Val)) {
  $Array[$Key] = $Replace;
   }
}
 }
  }
  return $Array;
}
  
  
  print_r( ArrayReplace($cars,'N/A') );