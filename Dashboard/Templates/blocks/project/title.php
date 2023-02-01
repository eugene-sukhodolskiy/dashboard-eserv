<h3 class="project-title">
	<? if(isset($project['project']['favicon']) and strlen($project['project']['favicon'])): ?>
		<img 
			src="<?= $project['project']['favicon'] ?>" 
			<? if(isset($project['project']['favicon_path'])): ?> data-favicon-path="<?= $project['project']['favicon_path'] ?>" <? endif ?>
			class="favicon app-favicon"
		>
	<? endif ?>
	<? if(isset($project['project']['main_lang'])): ?>
		<img src="./Dashboard/Resources/imgs/langs/file_type_<?= $project['project']['main_lang'] ?>@3x.png" class="favicon" title="<?= $project['project']['main_lang'] ?>">
	<? endif ?>
	<? if(isset($project['project']['repository']) 
			and isset($project['project']['repository']['url']) 
			and $project['project']['repository']['url']
			and $project['project']['repository']['type'] == 'git'): ?>
		<a href="<?= $project['project']['repository']['url'] ?>" target="_blank" class="repository-link">
			<?
				$icon = "./Dashboard/Resources/imgs/icons/git.png";
				$repo_icons_relations = ["github", "gitlab", "bitbucket"];
				foreach($repo_icons_relations as $key => $item){
					if(strpos($project['project']['repository']['url'], $item) !== false){
						$icon = './Dashboard/Resources/imgs/icons/' . $item . '.png';
						break;
					}
				}
			?>
			<img src="<?= $icon ?>" class="favicon">
		</a>
	<? endif ?>
	<?= isset($project['project']['name']) ? $project['project']['name'] : $project['name'] ?>
</h3>