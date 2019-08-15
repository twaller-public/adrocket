<?php

	require_once "../assets/_app_init.php";
	
	
	$page = mysql_get("about", 1);
	
	require_once "../assets/_header.php";
	
	
?>




<div class="container general-page" style="padding-top:4rem; background-color:#FFF;">

	<div class="row page-content">
		<div class="col-md-12"><h1><?php echo $page["title"]; ?></h1></div>
		<div class="col-md-12"><?php echo $page["content_top"]; ?></div>
		<div class="col-md-12"><?php echo $page["content_mid"]; ?></div>
	</div>
</div>






<?php require_once "../assets/_footer.php"; ?>







<script>
$(function(){
	
	$(".page-content img").addClass("img-responsive");
});
</script>






</body>
</html>