<?php

	require_once "../assets/_app_init.php";
	require_once "../assets/_header.php";
	
	
  
  // load records from 'purchases'
  list($learning_centerRecords, $lcMetaData) = getRecords(array(
    'tableName'   => 'learning_center',
    'loadUploads' => true,
    'allowSearch' => false,
    'where'       => " 1=1 ",
  ));
  $learning_center = @$learning_centerRecords[0];
  
  
  
?>




<div class="container general-page">

	<h1><?php echo $learning_center['title']; ?></h1>
	<p><?php echo $learning_center['content']; ?></p>

</div>






<?php require_once "../assets/_footer.php"; ?>







<script>
$(function(){
	
	
});
</script>






</body>
</html>