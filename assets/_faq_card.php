<div class="card faq-card">
	<div class="card-header" id="heading<?php echo $r->num(); ?>">
		<h5 class="mb-0">
			<button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $r->num(); ?>" aria-expanded="false" aria-controls="collapse<?php echo $r->num(); ?>">
			<?php echo $r->get("question"); ?>
			</button>
		</h5>
	</div>

	<div id="collapse<?php echo $r->num(); ?>" class="collapse" aria-labelledby="heading<?php echo $r->num(); ?>" data-parent="#accordion">
		<div class="card-body">
		<hr />
		<?php echo $r->get("answer"); ?>
		</div>
	</div>
</div>