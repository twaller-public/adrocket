<?php

/*
Plugin Name: Campaign Anaylics
Description: Functions for campaign analytics from google
Version: 1.00
CMS Version Required: 3.00
*/

addFilter('adminUI_args', 'ca_addImportButton');

pluginAction_addHandler("ca_importPage", 'admins');
pluginAction_addHandler("ca_importProcess", 'admins');


// show "Save & Copy" button
function ca_addImportButton($adminUI_args, $tableName, $action) {

  // Insert button after [Save] like this "[Save] [Save & Copy] [Cancel]" to match button order in: Admin > Section Editors > Field Editor
  if($tableName != 'campaign_component' || @$_REQUEST['action'] != 'edit') {
    return $adminUI_args;

  }
  

     $adminUI_args['BUTTONS'][] = [
       'label'   => t('Import Google Ads Stats'),
       'name'    => '_pluginAction=ca_import',
       'type'    => 'button',
       'onclick' => 'window.location = "?_pluginAction=ca_importPage&campaign=' . @$_REQUEST['num'] . '"',
     ];

  
   //
   return $adminUI_args;

}

function ca_importPage() {
  

  $campaign = mysql_get('campaign_component', coalesce(@$_REQUEST['campaign'],@$_REQUEST['campaignNum']));
  
  if(!@$campaign) {
    redirectBrowserToURL('admin.php?menu=campaign_component');
  }
  
  $thisYear = date("Y");
  
  $years = range($thisYear-5, $thisYear);
  
  $month = coalesce(@$_REQUEST['month'], date("m")-1);

  $monthOptions    = "<option value=''>".t('Month')."</option>\n";
  $shortMonthNames = preg_split("/\s*,\s*/", t('Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec'));
  foreach (array('01','02','03','04','05','06','07','08','09','10','11','12') as $num) {
    $selectedAttr   = selectedIf($num, $month, true);
    $shortMonthName = @$shortMonthNames[$num-1];
    $monthOptions .= "<option value=\"$num\" $selectedAttr>$shortMonthName</option>\n";
  }




  
  mysqlStrictMode(false);
  set_time_limit(15000);
  ini_set('memory_limit', '20000M');
  

  
  echo plugin_header("Import Analytics");
  
?>

  </form>
<form enctype="multipart/form-data" action="?" method="POST">
  <input type="hidden" name="_pluginAction" value="ca_importProcess">
  <input type="hidden" name="campaignNum" value="<?php echo $campaign['num']; ?>">
  
  <h3>Import Google CSV</h3>
  <p>Importing to: <a href="?menu=campaign_component&action=edit&num=<?php echo $campaign['num']; ?>"><?php echo $campaign['title']; ?></a></p>

  <p>Year:<br>
    <select name="year">
      <?php
        echo getSelectOptions(coalesce(@$_REQUEST['year'],$thisYear),$years);
      ?>
    </select>
  </p>
  
  <p>Month:<br>
    <select name="month">
      <?php echo $monthOptions; ?>
    </select>
  </p>

  <b>Import File</b>

  <p>Choose a csv file to import. File needs to be .csv.</p>

  <input type="file" name="importFile">

  <br>
  <input type="submit" class="btn" name="import" value="Import" style="background-color: #0062A6; color: #ffffff;"> 




  
  
  
  
  
  
  
  
  
  
  
  
  
  
<?php
  echo plugin_footer();
  exit;
}


function ca_importProcess() {
  
  error_reporting(0);
  
  $campaignNum = @$_REQUEST['campaignNum'];
  $month = @$_REQUEST['month'];
  $year = @$_REQUEST['year'];
  $totalImported = 0;

  if(!@$_FILES['importFile']['tmp_name']) {
    alert("Please select an import file");
    ca_importPage();
    die();
  }
  
  
  
  $filePath = $_FILES['importFile']['tmp_name'];
  ini_set('auto_detect_line_endings', TRUE);
  
  $columnNamesToDB = array ( 
        'Search keyword'  => 'keyword',
        'Ad group'        => 'ad_group',
        'Currency'        => 'currency',
        'Clicks'          => 'clicks',
        'Impressions'     => 'impressions',
        'Cost'            => 'cost',
        'CTR'             => 'ctr',
        'Avg. CPC'        => 'avg_cpc',
        'Avg. position'   => 'avg_position',
        );   
  
  
  
  
  
  $file = fopen($filePath, 'r');
  $fileContents = array();
  while($fileLine = fgetcsv($file)) {
    $fileContents[] = $fileLine;
  }
  fclose($file);
  
  foreach($fileContents as $key => $fileLine) {
    if(($fileLine[0]) != 'Search keyword') {
      unset($fileContents[$key]);
    }
    else {
      break;
    }
  }
  
  reset($fileContents);
  $firstKey = key($fileContents);
  //Column Names:
  foreach($fileContents[$firstKey] as $key => $name) {
    $headingsToColumns[$key] = trim(($name));
  }
  unset($fileContents[$firstKey]);


  $importData = array();
  $lineCount = 0;
  foreach($fileContents as $fileLine) {
    $lineCount++;
    foreach($fileLine as $key => $value) {
      if(!@$headingsToColumns[$key]) { continue; }
      if(in_array($columnNamesToDB[$headingsToColumns[$key]], array('Impressions', 'Clicks', 'impressions', 'clicks'))) {
        $importData[$lineCount][$columnNamesToDB[$headingsToColumns[$key]]] = preg_replace('#[^\d.]#', '', $value);
        //echo $key . " " . $value . " " . preg_replace('#[^\d.]#', '', $value) . "<br>";
      }
      else {
        $importData[$lineCount][$columnNamesToDB[$headingsToColumns[$key]]] = trim(($value));
      }
    }
  }
  


  foreach($importData as $importLine) {

    $importLine['campaign'] = $campaignNum;
    $importLine['analytics_month'] = $month;
    $importLine['analytics_year'] = $year;
    $importLine['createdDate'] = date("Y-m-d H:i:s");
  
    $insertNum = mysql_insert('campaign_google_stats', $importLine);
    
    if($insertNum) {
      $totalImported++;
    }
      
    
    

  }
  

  notice($totalImported . " records imported");
  
  
  ca_importPage();
  
  
  
  
  
  
}





?>