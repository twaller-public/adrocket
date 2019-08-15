<?php
	require_once "_view_ctrl.php";
	require_once "../assets/_header.php";
	
	//showme($campaigns[0]);
	//echo count($campaigns);
?>


<!-- main page content -->
<div class="container-fluid">

	<div class="col-sm-3 left-sidebar" style="position:sticky; top:0;">
	
		<!-- SIDEBAR LINKS -->
		<br />
		<br />
		<br />
		<br />
		<ul class="dashboard-side-menu" style="font-size:16px;">
			<li><a href="/campaign/create.php">New Campaign</a></li>
			<li><a href="#">Purchase History</a></li>
			<li><a href="#">Learning Center</a></li>
			<li><a href="#">ROI Calculator</a></li>
		</ul>
	</div>
	
	
	<div class="col-sm-7 main-content dashboard">
	
		<div style="color: #C00; font-weight: bold; font-size: 13px;">
			<?php echo $alerts; ?><br/>
		</div>
		
		<?php if($showCpn) include "../assets/_campaign_summary.php"; //include the campaign summary ?>
		
	</div>
</div>



<?php require_once "../assets/_footer.php"; ?>

<script>

	var adwords_created = [<?php echo $adwords_json; ?>];
	var recur_label  = '<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["Recurring Modal Label"] ; ?>';
	var notset_label = '<?php echo $GLOBALS["ADROCKET_DEFINITIONS"]["No Selection Text"] ; ?>';

	var m_names = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];

	var d_names = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"];


	function dateToText(date){
	
		if(!date || date == "00/00/0000") return notset_label;
		date = date.split("/");
		date = date[1] + "/" + date[0] + "/" + date[2];
		
		var d = new Date(date);
		var m = m_names[d.getMonth()];
		var w = d_names[d.getDay()];
		
		var result = w + ", " + m + " " + d.getDate() + " " + d.getFullYear()
		//console.log(result);
		return result;
	}
	

	function fillOutAdwordsPreviews(){
	
		console.log("PREVIEWS:");
		console.log(adwords_created);
		
		var preview_wrap = $("div.adwords-previews-wrap");
		var preview_temp = $("div.adwords-preview-template");
		
		$.each(adwords_created, function(index, elt){
			
			var preview = preview_temp.clone();
			
			preview.attr("data-num", index + 1);
			
			$.each(elt, function(index, val){
				
				var e = preview.find("[data-val='" + index + "']");
				
				if(e.hasClass("extension")){ 
					var ext_spl  = index.split("_");
					var ext_link = ext_spl[0] + "_" + ext_spl[2];
					
					e.attr("title", "extension link: /" + elt[ext_link]);
				}
				else if(index == "disp_url") e.attr("title", elt.url_prefix + "://www." + elt.dest_url);
				
				e.text(val);
			});
			
			preview.removeClass("adwords-preview-template");
			preview.addClass("adwords-preview");
			preview_wrap.append(preview);
			if(index == 0){
				
				$(".adwords-previews .preview-badges span[data-num='1']").removeClass("label-primary");
				$(".adwords-previews .preview-badges span[data-num='1']").addClass("label-success");
				$(".adwords-previews .preview-badges span[data-num='1']").addClass("current");
				preview.show();
			} 
		});
	}




	function adwordsPreviewNavs(){
		
		$("body").on("click", ".adwords-previews .preview-badges span", function(elt){
			
			//alert();
			if($(this).hasClass("label-success")) return false;
			
			var current = $(".adwords-previews .preview-badges span.current");
			var num     = $(this).attr("data-num");
			
			$("div.adwords-previews-wrap .adwords-preview").hide("slow");
			
			current.removeClass("label-success");
			current.removeClass("current");
			current.addClass("label-primary");
			
			$(this).removeClass("label-primary");
			$(this).addClass("label-success");
			$(this).addClass("current");
			
			$("div.adwords-previews-wrap .adwords-preview[data-num='"+num+"']").show("slow");
		});
	}



	$(function(){
		
		
		fillOutAdwordsPreviews();
		adwordsPreviewNavs();
		
		$(".summary-sections button.btn-success").remove();
		
		
		$("form#admin-form button[type='submit']").on("click", function(e){
			
			e.preventDefault();
			if(confirm("Caution: Changing this information could cause errors\n\nAre you sure you wish to continue?")){
				
				console.log($("form#admin-form"));
				$("form#admin-form")[0].submit();
			}

		})
	});
</script>


</body>
</html>