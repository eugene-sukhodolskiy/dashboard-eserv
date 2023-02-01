<header class="header">
	<div class="row">
		<div class="col-lg-8 col-md-12">
			<div class="row">
				<div class="col-lg-5 col-md-12">
					<img src="./Dashboard/Resources/imgs/logo.png" class="logo">
				</div>
				<div class="col-lg-7 col-md-12">
					<div class="search-wrap">
						<input type="text" class="inp search" placeholder="Search">
						<button class="search-cancel"></button>
					</div>
				</div>
			</div>
		</div>

		<div class="col-lg-4 col-md-12 dnone">

			<div class="row">
				<div class="col-12 aside-popups-container">
					<?= $this -> join("layouts/settings.popup.php", [
						'settings' => $settings,
						'settings_variants' => $settings_variants
					]) ?>
					<?= $this -> join('layouts/hidden.list'); ?>
				</div>
			</div>

		</div>
	</div>
	<div class="row sub">
		<!-- <div class="col-8">
			<div style="margin-top: 20px;">
				<strong class="status-label">Status:</strong>
				<span class="status-control" data-status="any">
					<a href="#" class="button small-button" data-status="open">Open</a>
					<a href="#" class="button small-button" data-status="closed">Closed</a>
					<a href="#" class="button small-button" data-status="any" style="display: none">Any</a>
				</span>
			</div>
		</div> -->

		<div class="sys-monitor-short dnone">
			<div class="uptime">
				<span class="mdi mdi-clock-time-eight-outline"></span>
				<span class="val">00:00:00</span>
			</div>
			<div class="cpu">
				<span class="mdi mdi-cpu-64-bit"></span>
				<span class="val">0%</span>
			</div>
			<div class="ram">
				<span class="mdi mdi-memory"></span>
				<span class="val">0%</span>
			</div>
			<div class="disk">
				<span class="mdi mdi-harddisk"></span>
				<span class="val">0%</span>
			</div>
		</div>

		<div class="total-wrap">
			<h3 class="total float-right">Total: <?= count($projects) ?></h3>
		</div>
	</div>
</header>

<div class="popup-mini-bg hidden-list-bg"></div>
<div class="popup-mini-bg hidden-settings-bg"></div>

<button class="close-project-description">&times;</button>