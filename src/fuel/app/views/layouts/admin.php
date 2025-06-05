<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? $title : 'Admin Panel'; ?></title>
    <link rel="stylesheet" href="/assets/css/base.css">
    <link rel="stylesheet" href="/assets/css/admin/style.css">
    <link rel="stylesheet" href="/assets/css/admin/form.css">
    <link rel="stylesheet" href="/assets/css/admin/pagination.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body>
    <header class="admin-header">
        <div class="container">
            <div class="header-left">
                <h1 class="logo">Admin Panel</h1>
            </div>
            <nav class="header-nav">
                <a href="/admin/logout" class="btn-logout">Logout</a>
            </nav>
        </div>
    </header>

    <?php
    $menus = [
        [
            'active' => 'products',
            'label' => '📦 Products',
            'url' => '/admin/products',
        ],
        [
            'active' => 'categories',
            'label' => '⚙️ Category',
            'url' => '/admin/categories',
        ],
    ]
    ?>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar py-4">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <?php foreach ($menus as $menu): ?>
                            <li class="nav-item mb-2">
                                <a class="nav-link <?= (($active_menu ?? '') === $menu['active']) ? 'active' : '' ?>" href="<?= $menu['url'] ?>">
                                    <?= $menu['label'] ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-10 ms-sm-auto main px-md-4 py-4">
                <?= $content ?>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php
    if (isset($js)) {
        if (is_array($js)) {
            foreach ($js as $uri) {
                echo Asset::js($uri);
            }
        } else {
            echo Asset::js($js);
        }
    }
    ?>
</body>

</html>