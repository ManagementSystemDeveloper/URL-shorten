<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link[]|\Cake\Collection\CollectionInterface $links
 */
?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php foreach ($links as $link) : ?>
        <url>
            <loc><?= $link->permalink() ?></loc>
        </url>
    <?php endforeach; ?>
</urlset>
