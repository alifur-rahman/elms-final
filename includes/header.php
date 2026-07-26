<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= getPageTitle($pageTitle ?? 'Dashboard') ?></title>
    <link rel="stylesheet" href="<?= ($basePath ?? '.') ?>/css/style.css">
    <?php if (!empty($pageCss)): ?>
    <link rel="stylesheet" href="<?= ($basePath ?? '.') ?>/<?= $pageCss ?>">
    <?php elseif (!empty($pageStyles)): ?>
    <style><?= $pageStyles ?></style>
    <?php endif; ?>
</head>
<body>
