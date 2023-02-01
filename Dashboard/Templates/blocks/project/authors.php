<? if(isset($project['project']['authors']) 
and is_array($project['project']['authors']) 
and count($project['project']['authors'])): ?>

	<div class="authors-list">
		<span class="heading">
			<span class="mdi mdi-account"></span>
			Author<? if(count($project['project']['authors']) > 1) echo "s"; ?>:
		</span>
		<ul class="authors">
			<?php foreach ($project['project']['authors'] as $i => $author): ?>

				<li class="author">
					<div class="name"><?= $author['name'] ?></div>
					<? if(isset($author['email']) and $author['email']): ?>
						<div class="email">
							<a href="mailto:<?= $author['email'] ?>">
								<span class="mdi mdi-email-outline"></span>
								&lt;<?= $author['email'] ?>&gt;
							</a>
						</div>
					<? endif ?>
					<?php if (isset($author['url']) and $author['url']): ?>
						<div class="link">
							<a href="<?= $author['url'] ?>" class="author-link" target="_blank">
								<span class="mdi mdi-web"></span>
								<?= $author['url'] ?>
							</a>
						</div>
					<?php endif ?>
				</li>

			<?php endforeach ?>
		</ul>
	</div>

<? endif ?>