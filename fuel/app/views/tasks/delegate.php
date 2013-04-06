<div class="row">
	<div class="span12">
		<h1>Choose a slave</h1>
	</div>
</div>
<div class="row">
	<div class="span12">
		<h3>Your Slaves</h3>
		<ul>
		<?php foreach($current_user->slaves as $slave): ?>
			<li><a href="/tasks/delegateto/<?php echo $slave->username; ?>"><?php echo $slave->username; ?></a></li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>