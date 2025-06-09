<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? $title : 'Admin Panel'; ?></title>
    <link rel="stylesheet" href="/assets/css/base.css">
    <link rel="stylesheet" href="/assets/css/admin/form.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <?php
    if (isset($css)) {
        if (is_array($css)) {
            foreach ($css as $uri) {
                echo Asset::css($uri);
            }
        } else {
            echo Asset::css($css);
        }
    }
    ?>
</head>

<body>
    <main class="main">
        <?= $content ?>
    </main>

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