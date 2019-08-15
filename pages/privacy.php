<?php

	require_once "../assets/_app_init.php";
	
	$page = new SingleRecordType("privacy_policy");
	
	$pTitle   = $page->get("title");
	$pContent = $page->get("content");
	
	
	require_once "../assets/_header.php";
	
	
?>




<div class="container general-page">

	<div class="row" style="background-color:white;">
	
		<div class="col-md-10 col-md-offset-1" style="padding:0px 30px; margin-top:40px;">
		
			<h1><?php echo $pTitle; ?></h1>
			<br />
			<br />
			<?php echo $pContent; ?>
		</div>
	
	
	</div>

	

</div>






<?php require_once "../assets/_footer.php"; ?>







<script>
$(function(){
	
	
});
</script>






</body>
</html>