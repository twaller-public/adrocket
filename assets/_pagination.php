<?php

	/**
	* This is the pagination template file.
	* Set the $meta variable in the calling program and require this file where
	* You want the pagination to display.	
	*/


	if(!@$meta) return false;
	
	$page  = $meta["page"];
	$pages = $meta["totalPages"];
	$first = $meta["firstPageLink"];
	$last  = $meta["lastPageLink"];
	$prev  = $meta["prevPageLink"];
	$next  = $meta["nextPageLink"];
	

?>




<div class="row">
	<div class="pages text-center">
	
		<ul class="pagination">
		
		
			<li title="First Page">
				<a href="<?php echo $first; ?>">&laquo;</a>
			</li>
			
			
			<?php if($page != 1) : ?>
				<li title="Previous Page">
					<a href="<?php echo $prev; ?>">&lt;</a>
				</li>
			<?php endif; ?>
			
			
			
			<?php for($i = 1; $i <= $pages; $i++) : ?>
			
				<li <?php if($i == $page) echo "class='active'"; ?> title="Page <?php echo $i; ?>">
					<a href="<?php echo $first . "?page=$i"; ?>"><?php echo $i; ?></a>
				</li>
			
			
			<?php endfor; ?>

			
			
			<?php if($page != $pages) : ?>
				<li title="Next Page">
					<a href="<?php echo $next; ?>">&gt;</a>
				</li>
			<?php endif; ?>
			
			
			
			<li title="Last Page">
				<a href="<?php echo $last; ?>">&raquo;</a>
			</li>
		</ul>
	
	
	</div>
</div>