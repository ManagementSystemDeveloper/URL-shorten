<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link[]|\Cake\Collection\CollectionInterface $links
 * @var string $ads_area1
 * @var string $ads_area2
 * @var \App\Model\Entity\Bundle $bundle
 */
$this->assign('title', $bundle->title);
$this->assign('description', $this->Text->truncate(strip_tags($bundle->description), 160));
$this->assign('content_title', $bundle->title);
?>

<section class="content">
    <div class="container">

        <?php if ($ads_area1) : ?>
            <div class="ads_area1 text-center">
                <?= $ads_area1 ?>
            </div>
        <?php endif; ?>

        <div class="page-header">
            <h1><?= h($bundle->title); ?></h1>
        </div>

        <div class="lead">
            <?= nl2br(h($bundle->description)); ?>
        </div>

        <?php if ($ads_area2) : ?>
            <div class="ads_area2 text-center">
                <?= $ads_area2 ?>
            </div>
        <?php endif; ?>

        <hr>

        <div class="row">
            <div class="col-sm-12">
                <h3><?= __("Bundle's Links") ?></h3>
                <div class="list-group">
                    <?php foreach ($links as $link) : ?>
                        <?php
                        $short_url = get_short_url($link->alias, $link->domain);
                        $title = $link->alias;
                        if (!empty($link->title)) {
                            $title = $link->title;
                        }
                        ?>
                        <a href="<?= h($short_url); ?>" target="_blank" class="list-group-item">
                            <h4 class="list-group-item-heading">
                                <i class="fa fa-link"></i> <?= h($title); ?>
                            </h4>
                            <small class="list-group-item-text">
                                <small class="text-muted">
                                    <i class="fa fa-calendar"></i>
                                    <?= display_date_timezone($link->created); ?> -
                                    <?= strtoupper(parse_url($link->url, PHP_URL_HOST)); ?>
                                </small>
                                <br>
                                <small class="text-muted"><?= nl2br(h($link->description)); ?></small>
                            </small>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
