    <script src="<?= ($basePath ?? '.') ?>/js/data.js"></script>
    <script src="<?= ($basePath ?? '.') ?>/js/app.js"></script>
    <script src="<?= ($basePath ?? '.') ?>/ajax/common.js"></script>
    <?php if (!empty($pageJs)): ?>
    <script src="<?= ($basePath ?? '.') ?>/<?= $pageJs ?>"></script>
    <?php elseif (!empty($pageScripts)): ?>
    <script><?= $pageScripts ?></script>
    <?php endif; ?>
</body>
</html>
