<? if(
	isset($project['project']['tags']) 
	and is_array($project['project']['tags']) 
	and count($project['project']['tags'])): ?>

	<div class="project-card-info">
		<span class="heading">
			<span class="mdi mdi-tag"></span>
			Tags:
		</span>
		<? if(isset($project['project']['tags'])): ?>
			<? foreach($project['project']['tags'] as $tag): ?>
				<a href="#" class="tag"><?= $tag ?></a>
			<? endforeach ?>
		<? endif ?>
	</div>
	
<? endif ?>