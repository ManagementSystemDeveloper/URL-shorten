<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $feed_url
 */
$feed = new \SimplePie();
$feed->set_feed_url($feed_url);
$feed->set_cache_location(CACHE . 'simplepie' . DS);
$feed->set_cache_duration(1800); // The number of seconds to cache for
$feed->init();
$feed->handle_content_type();
if (!$feed->error()) : ?>
    <div class="box box-solid box-primary">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-file-text-o"></i> <?= __("Latest Posts") ?></h3>
        </div>
        <div class="box-body">
            <div class="list-group">
                <?php foreach ($feed->get_items(0, 5) as $item) : ?>
                    <a target="_blank" href="<?= h($item->get_permalink()); ?>" rel="nofollow" class="list-group-item">
                        <h4 class="list-group-item-heading"><?= h($item->get_title()); ?></h4>
                        <p class="list-group-item-text"><h6><?= h($item->get_date('j F Y | g:i a')); ?></h6></p>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>
