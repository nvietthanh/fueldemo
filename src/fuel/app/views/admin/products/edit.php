<div class="d-flex justify-content-between align-items-center">
	<h2>Edit Product</h2>
</div>

<div class="mt-5">
	<form id="product-form" action="/admin/products/<?= $product->id ?>" enctype="multipart/form-data" method="post">
		<div class="mb-3">
			<label for="image">Image <span>*</span></label>
			<div class="mb-2">
				<input type="file" name="image_file" id="image" accept="image/png, image/jpeg, image/jpg" hidden>
				<button class="btn btn-primary" onclick="handleUploadImage(event)">Choose image</button>
			</div>
			<div id="imagePreview" style="width: 300px; min-height: 200px; border: 1px solid #000;">
				<img id="previewImg" src="<?= Uri::base(false) . $product->image_path ?>" alt="Preview" class="w-100" style="display: block;" />
			</div>
			<span id="error-image_file" class="error-message"></span>
		</div>
		<div class="mb-3">
			<label for="name" class="form-label">Name</label>
			<input type="text" name="name" id="name" value="<?= $product->name ?>" class="form-control">
			<span id="error-name" class="error-message"></span>
		</div>
		<div class="mb-3 d-flex" style="gap: 32px;">
			<div>
				<label for="price" class="form-label">Price</label>
				<input type="number" name="price" id="price" value="<?= $product->price ?>" class="form-control">
				<span id="error-price" class="error-message"></span>
			</div>
			<div>
				<label for="quantity" class="form-label">Quantity</label>
				<input type="number" name="quantity" id="quantity" value="<?= $product->quantity ?>" class="form-control">
				<span id="error-quantity" class="error-message"></span>
			</div>
		</div>
		<div class="mb-3">
			<label for="category_id" class="form-label">Category</label>
			<select name="category_id" id="category_id" class="form-select" style="width: 200px;">
				<option value="">-- Select Category --</option>
				<?php foreach ($categories as $category): ?>
					<option value="<?= e($category->id) ?>" <?= ($product->category_id == $category->id) ? 'selected' : '' ?>>
						<?= e($category->name) ?>
					</option>
				<?php endforeach; ?>
			</select>
			<span id="error-category_id" class="error-message"></span>
		</div>
		<div class="mb-3">
			<label for="description" class="form-label">Description</label>
			<textarea name="description" id="description" class="form-control" rows="4"><?= $product->description ?></textarea>
			<span id="error-description" class="error-message"></span>
		</div>

		<div class="mt-5">
			<button type="submit" class="btn btn-primary">Update Product</button>
		</div>
	</form>
</div>