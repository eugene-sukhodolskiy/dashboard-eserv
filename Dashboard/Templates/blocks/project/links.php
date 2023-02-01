<? $rendered_links = $this -> join('blocks/project/links.list', [
	'links' => $links
]) ?>

<? if(strlen(trim($rendered_links))): ?>
	<div class="line links-container">
		<span class="heading">
			<span class="mdi mdi-link"></span>
			Links:
		</span>
		<table class="links">
			<tbody>
				<?= $rendered_links ?>
			</tbody>
		</table>
	</div>
<? endif ?>