<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Post[]|\Cake\Collection\CollectionInterface $posts
 */
$this->assign('title', __('Blog'));
$this->assign('description', __('Discover all the latest news and tips about our service.'));
$this->assign('content_title', __('Blog'));

?>

<!-- Header -->
<header>
    <div class="section-inner">
        <div class="container">
            <div class="intro-text">
                <div class="intro-lead-in"><?= __('Blog') ?></div>
            </div>
        </div>
    </div>
</header>

<section class="blog">
    <div class="container">

        <?php foreach ($posts as $post) : ?>
            <div class="blog-item clearfix">
                <div class="page-header">
                    <h3><a href="<?= $post->permalink() ?>"><i class="fa fa-file-text-o"></i> <?= h($post->title) ?></a>
                    </h3>
                </div>
                <div class="blog-content"><?= $post->short_description ?></div>
                <hr>
                <div class="text-muted" style="overflow: hidden;">
                    <div class="pull-left">
                        <?= __("Published on") ?>: <?= $post->created ?>
                    </div>
                    <div class="pull-right">
                        <a class="popup"
                           href="http://www.facebook.com/sharer.php?u=<?= urlencode($post->permalink()) ?>&amp;t=<?= urlencode($post->title) ?>"
                           target="_blank" title="FaceBook"><span class="fa-stack"><i
                                    style="color:#3B5998 !important; text-shadow:-1px 0 #3B5998, 0 1px #3B5998, 1px 0 #3B5998, 0 -1px #3B5998 !important;"
                                    class="fa fa-stop fa-stack-2x"></i><i style="color:#ffffff !important;"
                                                                          class="fa fa-facebook fa-stack-1x"></i></span></a>

                        <a class="popup"
                           href="https://twitter.com/share?text=<?= urlencode($post->title) ?>&amp;url=<?= urlencode($post->permalink()) ?>"
                           target="_blank" title="Twitter"><span class="fa-stack"><i
                                    style="color:#00aced !important; text-shadow:-1px 0  #00aced, 0 1px  #00aced, 1px 0  #00aced, 0 -1px  #00aced !important;"
                                    class="fa fa-stop fa-stack-2x"></i><i style="color:#ffffff !important;"
                                                                          class="fa fa-twitter fa-stack-1x"></i></span></a>

                        <a class="popup"
                           href="http://pinterest.com/pin/create/button/?url=<?= urlencode($post->permalink()) ?>&amp;description=<?= urlencode($post->title) ?>"
                           title="Pinterest"><span class="fa-stack"><i
                                    style="color:#cb2027 !important; text-shadow:-1px 0 #cb2027, 0 1px #cb2027, 1px 0 #cb2027, 0 -1px #cb2027 !important;"
                                    class="fa fa-stop fa-stack-2x"></i><i style="color:#ffffff !important;"
                                                                          class="fa fa-pinterest fa-stack-1x"></i></span></a>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>

        <ul class="pagination">
            <?php
            $this->Paginator->setTemplates([
                'ellipsis' => '<li><a href="javascript: void(0)">...</a></li>',
            ]);

            if ($this->Paginator->hasPrev()) {
                echo $this->Paginator->prev('«');
            }

            echo $this->Paginator->numbers([
                'modulus' => 4,
                'first' => 2,
                'last' => 2,
            ]);

            if ($this->Paginator->hasNext()) {
                echo $this->Paginator->next('»');
            }
            ?>
        </ul>

    </div>
</section>
