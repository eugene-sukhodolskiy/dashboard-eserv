<div class="about">

	<p class="line last-update">
		<span class="heading">
			<span class="mdi mdi-update"></span>
			Updated:
		</span> 
		<?= date("d.m.Y H:i", $project['last_update']); ?>
		<? if(isset($project['project']['status'])): ?>
			<span class="status s-<?= strtolower($project['project']['status']) ?>"><?= $project['project']['status'] ?></span>
		<? endif ?>
	</p>

	<p class="line path-info">
		<span class="heading">
			<span class="mdi mdi-folder-outline"></span>
			Path to folder:
		</span>
		<?= $project['path'] ?>
	</p>

	<p class="line project-counters">
		<span class="heading">
			<span class="mdi mdi-harddisk"></span>
			Total:
		</span>
		<?= $project['project']['scan']['list']['filtered']['total']['files'] ?> files
		in <?= $project['project']['scan']['list']['filtered']['total']['folders']+1 ?> folders. (Size <?= $project['project']['scan']['fsize'] ?>)
		<br>
	</p>

	<? if(isset($project['project']) 
				and is_array($project['project']) 
				and count($project['project'])): ?>

		<? if(isset($project['project']['ver'])): ?>
			<p class="line ver">
				<span class="heading">
					<span class="mdi mdi-clover"></span>
					Version:
				</span>
				<?= $project['project']['ver'] ?>
			</p>
		<? endif ?>
		
		<? if(isset($project['project']['type'])): ?>
			<p class="line type">
				<span class="heading">Project type:</span>
				<? 
					$mdi_icons = [
						'web' => 'web', 
						'console' => 'console', 
						'app' => 'application',
						'application' => 'application',
						'docs' => 'file-document-outline',
						'documents' => 'file-document-outline'
					];
					$type_name = trim(strtolower($project['project']['type']));
					$icon = isset($mdi_icons[$type_name]) ? $mdi_icons[$type_name] : 'alien-outline';
				?>
				<span class="mdi mdi-<?= $icon ?>"></span>
				<?= $project['project']['type'] ?>
			</p>
		<? endif ?>

		<? if(isset($project['project']['description']) and strlen($project['project']['description'])): ?>
			<div class="line text-description">
				<div class="text custom-scroll">
					<span class="heading">
						<span class="mdi mdi-text"></span>
						Description:
					</span><br>
					<?= $project['project']['description'] ?>
				</div>
			</div>
		<? endif ?>

	<? endif ?>

</div>