<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= isset($title) ? $title : 'Website'; ?></title>

    <!-- Base styles -->
    <link rel="stylesheet" href="/assets/css/base.css">

    <!-- Bootstrap + SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <?php if (!empty($css)): ?>
        <?php foreach ((array)$css as $uri): ?>
            <?= Asset::css($uri); ?>
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body>
    <!-- Header / Navbar -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="/">MyShop</a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" href="/">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/products">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/cart">Cart</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container" style="padding-top: 80px;">
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="text-center text-muted py-3 border-top">
        <div class="container">
            &copy; <?= date('Y') ?> My Shop. All rights reserved.
        </div>
    </footer>

    <!-- JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php if (!empty($js)): ?>
        <?php foreach ((array)$js as $uri): ?>
            <?= Asset::js($uri); ?>
        <?php endforeach; ?>
    <?php endif; ?>
</body>

</html>