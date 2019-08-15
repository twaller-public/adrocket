<?php

	require_once "../../assets/_app_init.php";


  $return = array();
  if(@$_REQUEST['term']) {
    
    $industries = mysql_select('industries', " title LIKE '%" . mysql_escape($_REQUEST['term']) . "%' ");
    
    foreach($industries as $industry) {
      $return[] = array(  'id'    => $industry['num'],
                          'label' => $industry['title'],
                          'value' => $industry['title']
                       );
      
    }
    
    
  }

  echo json_encode($return);
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  