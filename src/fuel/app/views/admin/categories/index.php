<div class="d-flex justify-content-between align-items-center">
	<h2>Category List</h2>
	<div>
		<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
			Create category
		</button>
	</div>
</div>
<div class="mt-5">
	<div>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
					<th>Created at</th>
					<th>Updated at</th>
					<th style="width: 160px;">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php if (!empty($categories)): ?>
					<?php foreach ($categories as $category): ?>
						<tr data-id="<?= $category->id ?>">
							<td><?= $category->id ?></td>
							<td><?= htmlspecialchars($category->name) ?></td>
							<td><?= !empty($category->created_at) ? date('Y-m-d H:i', $category->created_at) : 'N/A' ?></td>
							<td><?= !empty($category->updated_at) ? date('Y-m-d H:i', $category->updated_at) : 'N/A' ?></td>
							<td>
								<button class="btn btn-primary" onclick="handleOpenFormEdit(<?= $category->id ?>)">
									Edit
								</button>
								<button class="btn btn-danger" onclick="handleDeleteCategory(<?= $category->id ?>)">
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

		<?php if (!empty($categories)): ?>
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

<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form id="create-category-form" class="modal-content">
			<?= Form::csrf(); ?>

			<div class="modal-header">
				<h5 class="modal-title" id="createCategoryLabel">Create Category</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<div class="mb-3">
					<label for="category-name" class="form-label">Category Name</label>
					<input type="text" name="name" class="form-control" id="category-name">
					<span id="error-name" class="error-message"></span>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Create</button>
			</div>
		</form>
	</div>
</div>
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form id="edit-category-form" class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editCategoryLabel">Create Category</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>

			<div class="modal-body">
				<div class="mb-3">
					<label for="category-name" class="form-label">Category Name</label>
					<input type="text" name="name" class="form-control" id="category-name">
					<span id="error-name" class="error-message"></span>
				</div>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="submit" class="btn btn-primary">Update</button>
			</div>
		</form>
	</div>
</div>