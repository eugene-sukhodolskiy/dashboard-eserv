<? $this -> extends_from('base') ?>
<canvas id="service-canv" width="1000" height="1000" style="display: none"></canvas>

<div class="row">
	<?php foreach($projects as $project): ?>
		<div class="col-12 col-md-6 col-lg-4 col-xl-3 col-xxl">
			<?= $this -> join('project.card', compact('project')); ?>
		</div>
	<?php endforeach; ?>
</div>