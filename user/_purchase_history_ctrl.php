<?php
/**
The user campaign purchase history program control file
*/


require_once "../assets/_app_init.php";




/*
* Permissions
*/

//deterine access permissions
blockAgentType("GUEST", "/user/signin.php");
blockAgentType("MANAGER", "/manager/dashboard.php");
blockAgentType("ADMIN", "/admin/dashboard.php");
blockAgentType("CEO", "/ceo/dashboard.php");





  // load records from 'purchases'
  list($purchases, $purchasesMetaData) = getRecords(array(
    'tableName'   => 'purchases',
    'loadUploads' => true,
    'allowSearch' => false,
    'where'       => " user = '" . mysql_escape($CURRENT_USER['num']) . "' ",
    'orderBy'     => " createdDate DESC "
  ));









?>