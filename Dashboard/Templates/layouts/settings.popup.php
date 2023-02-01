<script>
	const SETTINGS = JSON.parse('<?= json_encode($settings) ?>');
</script>

<div class="popup-mini-container settings-popup-container">
	<button class="button settings-list-open">Settings</button>
	<div class="popup-mini-content settings-list">
		<h3>Dashboard settings</h3>

		<div class="setting-item">
			<label for="color-schema">Theme</label>
			<select name="color-schema" id="color-schema" class="input">
				<?php foreach ($settings_variants['color-schema'] as $i => $val): ?>
					<option value="<?= $val ?>" <? if($val == $settings['color-schema']) echo "selected" ?>><?= $val ?></option>
				<?php endforeach ?>
			</select>
		</div>

	</div>
</div>