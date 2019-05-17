<?php
namespace BOF\Domain;

use Symfony\Component\Console\Helper\Table;

class TableDisplay{
  private $headers;
  
  function __construct($headers){
    $this->headers = $headers;    
  }

  function display($data, $io){
    $table = new Table($io);
    $table->setHeaders($this->headers);
    // append each row to the table for display
    foreach($data as $row){
      // transform column values
      $row = array_map(function ($value) {        
        if (empty($value)){ // return n/a for empty values
          return 'n/a';
        }
        if (is_numeric($value)){
          return number_format($value, 0, '.', ',');
        }
        // if it is enither empty or number, return whatever 
        // was passed
        return $value;
      }, $row);
      // add transformed rows to the table
      $table->addRow($row);
    }
    $table->render();
  }  
} 