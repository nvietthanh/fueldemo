<div id="listProduct" class="container">
	<h2 class="text-center mb-5">Product List</h2>

	<div class="row">
		<?php foreach ($products as $product): ?>
			<div class="col-md-3 mb-4">
				<div class="card shadow-sm border-light rounded">
					<div class="card-img-top">
						<img src="<?= Uri::base(false) . $product->image_path ?>" alt="<?php echo e($product->name); ?>">
					</div>
					<div class="card-body">
						<div class="d-flex justify-content-between align-items-start">
							<h5 class="card-title product-title"><?php echo e($product->name); ?></h5>
							<p class="category text-muted mb-0"><?php echo e($product->category->name); ?></p>
						</div>
						<p class="card-text"><strong><?php echo e(number_format($product->price, 0, '.', ',')); ?> VND</strong></p>
						<div class="d-flex" style="gap: 12px;">
							<button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#showProduct"
								onclick="showProductDetail(<?= $product->id ?>)">
								View Details
							</button>
							<button class="btn btn-success btn-sm" onclick="addProductCart(<?= $product->id ?>)">
								Add to Cart
							</button>
						</div>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<?php if (!empty($products)): ?>
		<div class="d-flex justify-content-center mt-4">
			<?= $pagination['links'] ?>
		</div>
	<?php endif; ?>
</div>

<div class="modal fade" id="showProduct" tabindex="-1" aria-labelledby="showProductLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="loading-spinner" style="min-height: 450px; display: none;">
					<div class="spinner-border text-primary" role="status">
						<span class="visually-hidden">Loading...</span>
					</div>
				</div>
				<div id="product-details" style="display: none;">
					<div class="d-flex">
						<div class="product-image-container me-4">
							<img class="product-image" src="" alt="Product Image">
						</div>
						<div class="product-info">
							<h4 class="product-name">Product Name</h4>
							<p class="product-category">Category Name</p>
							<p class="product-description">No description available.</p>
							<p class="product-price">0 VND</p>
							<button class="btn-buy btn btn-success mt-3" onclick="addProductCart()">
								Add to Cart
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>