		<div class="hero-unit">
			<h1>Welcome to Task Master</h1>
		</div>
		<div class="row">
			<div class="span8">
			<?php if (Auth::check()): ?>
				<h3>You</h3>
				<dl>
					<dt>Username:</dt>
					<dl><?php echo $current_user->username; ?></dl>
				</dl>
 
				<?php if (count($current_user->masters) > 0): ?>
				<h3>Your Masters</h3>
				<ul>
				<?php foreach($current_user->masters as $master): ?>
					<li><?php echo $master->username; ?>
				<?php endforeach; ?>
				</ul>
				<?php else: ?>
					<h3>You have no Master - You are the Task Master</h3>
				<?php endif; ?>

				<?php if (count($current_user->slaves) > 0) : ?>
				<h3>Your Slaves</h3>
				<ul>
				<?php foreach($current_user->slaves as $slave): ?>
					<li><?php echo $slave->username; ?></li>
				<?php endforeach; ?>
				</ul>
				<?php endif; ?>

			<?php endif; ?>
			</div>
			<div class="span4">
				<?php if (Auth::check()) : ?>
				<p>Welcome Back!</p>
				<p><a href="/welcome/logout" class="btn">Logout</a></p>
				<?php else: ?>
				<form method="post" action="/welcome/login">
					<label for="login_email">Username or Email</label>
					<input type="email" class="email" name="email" id="login_email">

					<label for="login_password">Password</label>
					<input type="password" name="password" id="login_password">

					<input type="submit" value="Log In">
				</form>
				<?php endif; ?>
			</div>
		</div>

		<footer>
			<p class="pull-right">Page rendered in {exec_time}s using {mem_usage}mb of memory.</p>
		</footer>
