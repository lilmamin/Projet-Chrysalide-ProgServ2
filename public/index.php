<?php
require_once __DIR__ . '/../src/i18n.php';
include __DIR__ . '/templates/header.php';
?>
<h1><?= t('welcome'); ?></h1>
<p><?= sprintf(t('home_intro'), BASE_PATH . 'discover.php'); ?></p>
<?php include __DIR__ . '/templates/footer.php';
