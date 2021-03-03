<?php
    $presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
?>

<div class="pagination">
	<ul class="pull left">
		<li>
		Showing
		<?php echo $paginator->getFrom(); ?>
		-
		<?php echo $paginator->getTo(); ?>
		of
		<?php echo $paginator->getTotal(); ?>
		items
		</li>
	</ul>

	<ul class="float-right">
		<?php echo $presenter->render(); ?>
	</ul>
</div>
