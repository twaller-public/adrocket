<?php

/*

	The viewer page for the keywords selection in the Campaign Creation Wizard Process
	
*/
	
	require_once "_additional_ctrl.php";
	require_once "../../assets/_header.php";

	
	//showme($keywordData);
	//showme($additional_default_keywords);

?>

<!-- main page content -->
<div class="jumbotron ec-process">

	<?php require "../../assets/_create_breadcrumbs.php"; ?>
	<div class="container" id="main_content">
		<div class="row">


			<div class="col-md-12 err-box">
			
				<form id="kwForm" action="?" method="POST">
				
					<input type="hidden" name="Continue"            value="Continue" />
					<input type="hidden" name="num"                 value="<?php echo $cpnNum; ?>" />
					<input type="hidden" name="keywords"            value="<?php //echo implode(",", $keywords);             ?>" />
					<input type="hidden" name="negative_keywords"   value="<?php //echo implode(",", $negative_keywords);    ?>" />
					<input type="hidden" name="default_keywords"    value="<?php //echo implode(",", $default_keywords);     ?>" />
					<input type="hidden" name="unused_defaults"     value="<?php //echo implode(",", $unused_defaults);      ?>" />

					
					<!-- section header -->
					<div class="col-md-12" id="ec-process-header-1" style="opacity:0; height:0px;">
						<h2 style="font-size:35px;">Choose Your Keywords</h2>
					</div>
					
					
					<!-- Industry select (disabled)
					<label>CATEGORY/HEADING/INDUSTRY <span class="red-ast">*</span></label><br/>
					<label>&nbsp;</label>
					
					
					<div class="col-md-6">
						<select class="form-control lead-calc" name="industry">
							<option value="0"> -- Please Select -- </option>
						</select>
					</div> -->
					
					
					<!-- Keyword fields -->
					<div class="col-md-12">
						
						<!-- keywords section -->
						<div class="col-md-12 keyword-sec well" style="margin-top:5px; border-radius:4px; ">
							
							<h3 style="margin-top:0;">Keywords for this Industry: </h3>
							
							<!-- keyword type legend -->
							<div class='keywordLegend' style="padding:10px; border-radius:4px; margin:10px; background-color:white;">
							
								<div style="display:inline-block; margin-right:8px;">
									<div class="alert alert-info keyword-check">
										<span class="glyphicon glyphicon-minus-sign"></span>
										&nbsp;<span>Selected Default</span>
									</div>
								</div>
								
								<div style="display:inline-block; margin-right:8px;">
									<div class="keyword-check alert alert-warning">
										<span class="glyphicon glyphicon-plus-sign"></span>
										&nbsp;<span>Unselected Default</span>
									</div>
								</div>
								
								<div style="display:inline-block; margin-right:8px;">
									<div class="keyword-check alert alert-success">
										<span class="glyphicon glyphicon-minus-sign"></span>
										&nbsp;<span>Custom</span>
									</div>
								</div>
								
								
							
							</div>

							<!-- selected keywords section -->
							<div class='keywordElts'>
								
							
								<div class="default-kw">
									<br />
									<h4>Top Suggested Keywords (by search volume)</h4>
									<p class="no-entires" style="font-size:14px;<?php if(!empty($default_keywords)) echo "display:none;"; ?>">Not using any default keywords</p>
									<?php foreach($default_keywords as $dkw) : ?>
									
										<div style="display:inline-block; margin-right:8px;">
											<div class="alert alert-info keyword-check">
												<span class="glyphicon glyphicon-minus-sign"></span>
												&nbsp;<span class="kw"><?php echo $dkw; ?></span>
											</div>
										</div>
									
									<?php endforeach; ?>
								</div>
								
								
								
								
								<?php //if(!empty($unused_defaults)) : ?>
									<div class="unused-kw">
										<br />
										<h4>Unselected Default Keywords</h4>
										<p class="no-entires" style="font-size:14px;<?php if(!empty($unused_defaults)) echo "display:none;"; ?>">Using All Default Keywords</p>
										<?php foreach($unused_defaults as $dkw) : ?>
										
											<?php if(!$dkw) continue; ?>
											<div style="display:inline-block; margin-right:8px;">
												<div class="alert alert-warning keyword-check">
													<span class="glyphicon glyphicon-plus-sign"></span>
													&nbsp;<span class="kw"><?php echo $dkw; ?></span>
												</div>
											</div>
										
										<?php endforeach; ?>
									</div>
								<?php //endif; ?>
								
								
								<div class="custom-kw">
									<br />
									<h4>Your Custom Keywords</h4>
									<p class="no-entires" style="font-size:14px;<?php if(!empty($keywords)) echo "display:none;"; ?>">No Custom Keywords</p>
									<?php foreach($keywords as $kw) : ?>
									
										<div style="display:inline-block; margin-right:8px;">
											<div class="alert alert-success keyword-check">
												<span class="glyphicon glyphicon-minus-sign"></span>
												&nbsp;<span class="kw"><?php echo $kw; ?></span>
											</div>
										</div>
									
									<?php endforeach; ?>
								</div>
								
								
							</div>
							
							<!-- custom kw input -->
							<div class="input-group">
								<input type="text" class="form-control" placeholder="Add your custom keywords" value="" name="custom-keywords" aria-describedby="customkw" style="margin-top:0px; padding:5px 18px;" />
								<span class="input-group-btn" id="customkw"><button class="btn btn-success btn-lg"  name="Continue" value="Custom">Add</button></span>
							</div>
						</div>
						
						<!-- negative keywords section -->
						<div class="col-md-12 neg-keyword-sec well" style="margin-top:5px; border-radius:4px; ">
							
							<h3 style="margin-top:0;">Negative keywords for this Industry: </h3>
							
							<!-- <div class='negkeywordLegend' style="padding:10px; border-radius:4px; margin:10px; background-color:white;"></div> -->

							<div class='negkeywordElts'>
							
								<?php if(!empty($negative_keywords)) : ?>
									<div class="negative-kw">
										<br />
										<h4>Negative Keywords</h4>
										<p class="no-entires" style="font-size:14px;<?php if(!empty($negative_keywords)) echo "display:none;"; ?>">Not Using Any Negative Keywords</p>
										<?php foreach($negative_keywords as $nkw) : ?>
										
											<div style="display:inline-block; margin-right:8px;">
												<div class="alert alert-danger keyword-check">
													<span class="glyphicon glyphicon-minus-sign"></span>
													&nbsp;<span class="kw"><?php echo $nkw; ?></span>
												</div>
											</div>
										
										<?php endforeach; ?>
									</div>
								<?php endif; ?>
							
							</div>
							
							<div class="input-group">
								<input type="text" class="form-control" placeholder="Add Keywords You DON'T want to come up for on Google" value="" name="negative-keywords" aria-describedby="negativekw" style="margin-top:0px; padding:5px 18px;" />
								<span class="input-group-btn" id="negativekw"><button class="btn btn-success btn-lg" name="Continue" value="Negative">Add</button></span>
							</div>
						</div>
					
					</div>
					
					
					<!-- Form submit -->
					<div class="col-md-12">
						<div class="form-group text-center" style="padding-top:30px;">
							<input class="btn btn-lg btn-danger" type="submit" name="Continue" value="Continue" />
						</div>
					</div>
				
				</form>
			</div>

		</div>
	</div>
</div>

<?php 
	
	//footer
	require_once "../../assets/_footer.php"; 
	
?>

<script src="/js/wizard_common_temp.js"></script>
<script>


	$(function(){
		
		animateHeader1_show(true);
		handleKWClicks();
		handleSubmit();
	});
	
	
	function handleKWClicks(){
		
		handleDKWClick();
		handleUKWClick();
		handleCKWClick();
		handleNKWClick();
	}
	
	
	//default keyword click
	function handleDKWClick(){
		
		$("body").on("click", "div.default-kw div.keyword-check", function(elt){
			
			var e         = $(this);
			var e2        = e.parent().clone();
			var eK        = e2.find(".keyword-check");
			var gl        = e2.find("span.glyphicon");
			var thisWrap  = $("div.default-kw");
			var otherWrap = $("div.unused-kw");
			var noEntryP  = thisWrap.find("p.no-entires");
			var otEntryP  = otherWrap.find("p.no-entires");
			var divChdrn  = null;
			var othChdrn  = null;
			
			e.parent().remove();
			eK.removeClass("alert-info");
			eK.addClass("alert-warning");
			gl.removeClass("glyphicon-minus-sign");
			gl.addClass("glyphicon-plus-sign");
			
			otherWrap.append(e2);
			
			divChdrn = thisWrap.children("div");
			othChdrn = otherWrap.children("div");
			
			if(!divChdrn.length) noEntryP.show();
			else noEntryP.hide();
			
			if(!othChdrn.length) otEntryP.show();
			else otEntryP.hide();
			
		});
	}
	
	
	
	//unused keyword click
	function handleUKWClick(){
		
		$("body").on("click", "div.unused-kw div.keyword-check", function(elt){
			
			var e         = $(this);
			var e2        = e.parent().clone();
			var eK        = e2.find(".keyword-check");
			var gl        = e2.find("span.glyphicon");
			var thisWrap  = $("div.unused-kw");
			var otherWrap = $("div.default-kw");
			var noEntryP  = thisWrap.find("p.no-entires");
			var otEntryP  = otherWrap.find("p.no-entires");
			var divChdrn  = null;
			var othChdrn  = null;
			
			e.parent().remove();
			eK.removeClass("alert-warning");
			eK.addClass("alert-info");
			gl.removeClass("glyphicon-plus-sign");
			gl.addClass("glyphicon-minus-sign");
			
			otherWrap.append(e2);
			
			divChdrn = thisWrap.children("div");
			othChdrn = otherWrap.children("div");
			
			if(!divChdrn.length) noEntryP.show();
			else noEntryP.hide();
			
			if(!othChdrn.length) otEntryP.show();
			else otEntryP.hide();
			
		});
	}
	
	
	
	//custom keyword click
	function handleCKWClick(){
		
		$("body").on("click", "div.custom-kw div.keyword-check", function(elt){
			
			var e         = $(this);
			var e2        = e.parent().clone();
			var eK        = e2.find(".keyword-check");
			var gl        = e2.find("span.glyphicon");
			var thisWrap  = $("div.custom-kw");
			var noEntryP  = thisWrap.find("p.no-entires");
			var divChdrn  = null;
			
			e.parent().remove();
			
			divChdrn = thisWrap.children("div");
			
			if(!divChdrn.length) noEntryP.show();
			else noEntryP.hide();
		});
	}
	
	
	
	//negative keyword click
	function handleNKWClick(){
		
		$("body").on("click", "div.negative-kw div.keyword-check", function(elt){
			
			var e         = $(this);
			var e2        = e.parent().clone();
			var eK        = e2.find(".keyword-check");
			var gl        = e2.find("span.glyphicon");
			var thisWrap  = $("div.negative-kw");
			var noEntryP  = thisWrap.find("p.no-entires");
			var divChdrn  = null;
			
			e.parent().remove();
			
			divChdrn = thisWrap.children("div");
			
			if(!divChdrn.length) noEntryP.show();
			else noEntryP.hide();
		});
	}
	
	
	
	function handleSubmit(){
		
		
		$("[name='Continue']").on("click", function(e){
			
			e.preventDefault();
			
			var e = $(e.target);
			var dkw = $("div.default-kw div.keyword-check span.kw");
			var ckw = $("div.custom-kw div.keyword-check span.kw");
			var ukw = $("div.unused-kw div.keyword-check span.kw");
			var nkw = $("div.negative-kw div.keyword-check span.kw");
			
			var newCkw = $("input[name='custom-keywords']").val();
			var newNkw = $("input[name='negative-keywords']").val();
			
			$("input[name='custom-keywords']").val("");
			$("input[name='negative-keywords']").val("");
			$("input[name='Continue']:hidden").val($(this).val());
			
			var kw = {
				default_keywords : "",
				keywords         : "",
				unused_defaults  : "",
				negative_keywords: ""
			};
			
			$.each(dkw, function(index, elt){
				kw.default_keywords += $(elt).text() + ",";
			});
			
			$.each(ckw, function(index, elt){
				kw.keywords += $(elt).text() + ",";
			});
			
			$.each(ukw, function(index, elt){
				kw.unused_defaults += $(elt).text() + ",";
			});
			
			$.each(nkw, function(index, elt){
				kw.negative_keywords += $(elt).text() + ",";
			});
			
			console.log(e.val());
			
			if     (e.val() == "Custom"   && newCkw) kw.keywords          += newCkw;
			else if(e.val() == "Negative" && newNkw) kw.negative_keywords += newNkw;
			
			
			console.log(kw);
			
			$.each(kw, function(index, elt){
				
				$("input[name='"+index+"']:hidden").val(elt);
			});
			
			$("#kwForm").submit();
		});
	}







</script>