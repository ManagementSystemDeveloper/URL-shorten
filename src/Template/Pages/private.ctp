<?php
/**
 * @var \App\View\AppView $this
 */
?>
<!DOCTYPE HTML>
<html lang="<?= locale_get_primary_language(null) ?>">
<head>
    <title><?= h(get_option('site_name')); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= h(get_option('site_description')); ?>">
</head>
<body>
<?= $this->Flash->render() ?>
<h3><?= __('This is a private service.') ?></h3>
</body>
</html>
