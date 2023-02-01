<div class="description custom-scroll">
	<?= $this -> join('blocks/project/title', ['project' => $project]) ?>
	<?= $this -> join('blocks/project/about', ['project' => $project]) ?>

	<?php if($project['project']): ?>
		<?= $this -> join('blocks/project/authors', ['project' => $project]) ?>
		<?= $this -> join('blocks/project/tags', ['project' => $project]) ?>
		<?= $this -> join('blocks/project/links', [
			'links' => array_merge([
				'release_url' => isset($project['project']['release_url']) ? $project['project']['release_url'] : '',
				'git_url' => (isset($project['project']['repository']) and isset($project['project']['repository']['url'])) ? $project['project']['repository']['url'] : ''
			], $project['project']['links'])
		]) ?>
	<?php endif ?>

	<div class="project-control">
		<? if(isset($project['project']['type']) and $project['project']['type'] == 'web'): ?>
			<a 
				class="button open-project" 
				href="<?= isset($project['project']['release_url']) ? $project['project']['release_url'] : $project['project']['links']['release_url'] ?>"
			>Open</a>
		<? endif ?>
		<button class="button" data-project-name="<?= $project['name'] ?>" data-change-visibility="false">
			<span class="mdi mdi-eye-off-outline"></span>
			Hidden
		</button>
	</div>
</div>