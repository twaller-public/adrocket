<?php

	require_once "../assets/_app_init.php";
	
	
	
	
	
	function getFAQsHTML(){
	
		$html = "<div id='accordion'>";
		$faqs = new MultiRecordType("faqs", array(), true);
		$faqs = $faqs->records();
		
		foreach($faqs as $faq){
		
			$html .= getFAQHTML($faq->num());
		}
		
		$html .= "</div>";
		
		return $html;
	}



	function getFAQHTML($rNum){
		
		$r = new Record("faqs", $rNum, null);
		
		$html = ob_capture(function($r){ include $_SERVER["DOCUMENT_ROOT"]."/assets/_faq_card.php"; }, $r);
		
		
		return $html;
	}
	
	$faqsHTML = getFAQsHTML();
	
	require_once "../assets/_header.php";
	
	
?>




<div class="container general-page" style="padding-top:4rem; background-color:#FFF;">

	<div class="row">
		<div class="col-md-12"><h1>Frequently Asked Questions</h1></div>
		<div class="col-md-12">
			<?php echo $faqsHTML; ?>
			
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