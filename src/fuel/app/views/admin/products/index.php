<div class="d-flex justify-content-between align-items-center">
	<h2>Product List</h2>
	<div>
		<button class="btn btn-primary" onclick="handleCreateProduct()">Create product</button>
	</div>
</div>
<div class="mt-4">
	<form action="/admin/products" method="get" class="d-flex align-items-end" style="gap: 24px;">
		<div class="form-group mr-3" style="width: 250px;">
			<label for="name" class="mr-2">Name:</label>
			<input type="text" name="name" id="name" class="form-control" value="<?= Input::get('name') ?>" placeholder="Search by name">
		</div>
		<div class="form-group mr-3" style="width: 190px;">
			<label for="categories" class="mr-2">Categories:</label>
			<select name="category_id" id="categories" class="form-select">
				<option value="">-- All Category --</option>
				<?php foreach ($categories as $category): ?>
					<option value="<?= $category->id ?>" <?= Input::get('category_id') == $category->id ? 'selected' : '' ?>>
						<?= e($category->name) ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group mb-1">
			<button type="submit" class="btn btn-primary" style="width: 130px;">Search</button>
		</div>
	</form>
</div>
<div class="mt-4">
	<div>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Image</th>
					<th>Name</th>
					<th>Price</th>
					<th>Quantity</th>
					<th>Category</th>
					<th>Created at</th>
					<th>Updated at</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($products)): ?>
					<?php foreach ($products as $product): ?>
						<tr data-id="<?= $product->id ?>">
							<td><?= $product->id ?></td>
							<td style="width: 90px;">
								<img src="<?= get_file_url($product->image_path) ?>" alt="" style="width: 100%; height: 100; object-fit: cover;">
							</td>
							<td>
								<a href="/admin/products/<?= $product->id ?>/edit"><?= e($product->name) ?></a>
							</td>
							<td><?= number_format($product->price ?? 0) ?></td>
							<td><?= $product->quantity ?? 0 ?></td>
							<td><?= e($product->category->name) ?></td>
							<td><?= !empty($product->created_at) ? date('Y-m-d H:i', $product->created_at) : 'N/A' ?></td>
							<td><?= !empty($product->updated_at) ? date('Y-m-d H:i', $product->updated_at) : 'N/A' ?></td>
							<td>
								<button class="btn btn-primary" onclick="handleEditProduct(<?= $product->id ?>)">
									Edit
								</button>
								<button class="btn btn-danger" onclick="handleDeleteProduct(<?= $product->id ?>)">
									Delete
								</button>
							</td>
						</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="8" class="text-center text-muted">No data available.</td>
					</tr>
				<?php endif; ?>
			</tbody>

		</table>

		<?php if (!empty($products)): ?>
			<div class="pagination-wrapper">
				<div class="pagination-total">
					Showing <?= $pagination['from'] ?> – <?= $pagination['to'] ?> of <?= $pagination['total'] ?> items
				</div>
				<div class="pagination-main">
					<?= $pagination['links'] ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>