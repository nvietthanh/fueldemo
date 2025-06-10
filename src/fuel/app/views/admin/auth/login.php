<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin login</title>
	<link rel="stylesheet" href="/assets/css/base.css">
	<link rel="stylesheet" href="/assets/css/admin/style.css">
	<link rel="stylesheet" href="/assets/css/admin/form.css">
	<link rel="stylesheet" href="/assets/css/admin/pagination.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
	<div class="container d-flex justify-content-center align-items-center vh-100">
		<div class="card p-4 shadow" style="max-width: 600px; width: 400px; margin: 0 auto;">
			<h2 class="text-center mb-4">Admin Login</h2>

			<?php $errors = Session::get_flash('errors'); ?>

			<form action="/admin/login" method="post">
				<?= Form::csrf(); ?>

				<input type="hidden" name="previous_url" value="<?= Input::get('previous_url') ?>">

				<div class="mb-3">
					<label for="email" class="form-label">Email</label>
					<input type="text" name="email" id="email" value="<?= e(Input::post('email', '')) ?>" class="form-control">
					<?php if (!empty($errors['email'])): ?>
						<span class="error-message"><?= e($errors['email']->get_message()) ?></span>
					<?php endif; ?>
					<?php if (Session::get_flash('msg_error')): ?>
						<span class="error-message"><?= Session::get_flash('msg_error') ?></span>
					<?php endif; ?>
				</div>

				<div class="mb-3">
					<label for="password" class="form-label">Password</label>
					<input type="password" name="password" id="password" value="<?= e(Input::post('password', '')) ?>" class="form-control">
					<?php if (!empty($errors['password'])): ?>
						<span class="error-message"><?= e($errors['password']->get_message()) ?></span>
					<?php endif; ?>
				</div>

				<div class="d-grid">
					<button type="submit" class="btn btn-primary">Submit</button>
				</div>
			</form>
		</div>
	</div>
</body>

</html>