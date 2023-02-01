<div class="project" 
	data-name="<?= $project['name'] ?>"
	data-title="<?= isset($project['project']['name']) ? $project['project']['name'] : $project['name'] ?>"
	data-tags='<?= (isset($project["project"]["tags"]) and is_array($project["project"]["tags"])) ? json_encode($project["project"]["tags"]) : json_encode([])  ?>'
	data-status="<?= isset($project['project']['status']) ? strtolower($project['project']['status']) : 'undefined' ?>"
	data-color="<?= isset($project['project']['project_color']) ? $project['project']['project_color'] : 'undefined' ?>"
	data-type="<?= isset($project['project']['type']) ? $project['project']['type'] : 'undefined' ?>"
>
	<?= $this -> join('blocks/project/title', ['project' => $project]) ?>
	<div class="project-card-info">
		<? if(isset($project['project']['status'])): ?>
			<span class="status s-<?= strtolower($project['project']['status']) ?> dnone"><?= $project['project']['status'] ?></span>
		<? endif ?>
		<? if(isset($project['project']['tags'])): ?>
			<? foreach($project['project']['tags'] as $i => $tag): ?>
				<? if($i == 5) break; ?>
				<a href="#" class="tag"><?= $tag ?></a>
			<? endforeach ?>
		<? endif ?>
	</div>

	<!-- DESCRIPTION FOR POPUP -->
	<?= $this -> join('layouts/project.description.php', [
		'project' => $project
	]) ?>
	<!-- END DESCRIPTION -->

	<div class="project-control root">
		<? if(isset($project['project']['type']) and $project['project']['type'] == 'web'): ?>
			<a 
				class="button open-project" 
				href="<?= isset($project['project']['release_url']) ? $project['project']['release_url'] : $project['project']['links']['release_url'] ?>"
			>Open</a>
		<? endif ?>
		<span class="short-path-to-project-folder" title="<?= $project['path'] ?>">...<?= substr($project['path'], -20, 20) ?></span>
	</div>
</div>