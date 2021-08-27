<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link $link
 * @var mixed $ads_area1
 * @var mixed $ads_area2
 * @var mixed $comments
 * @var mixed $destination_url
 * @var mixed $feed
 * @var mixed $plan_link_password
 * @var mixed $short_link
 * @var mixed $timer
 */
$this->assign('title', get_option('site_name'));
$this->assign('description', get_option('site_description'));
$this->assign('content_title', get_option('site_name'));
$this->assign('og_title', $link->title);
$this->assign('og_description', $link->description);
$this->assign('og_image', $link->image);
?>

<?php $this->start('scriptTop'); ?>
<script type="text/javascript">
    if (window.self !== window.top) {
        window.top.location.href = window.location.href;
    }
</script>
<?php $this->end(); ?>

<section class="content">

    <div class="container">

        <?php if ($ads_area1) : ?>
            <div class="ads_area1 text-center">
                <?= get_option('ads_area1'); ?>
            </div>
        <?php endif; ?>

        <div class="box box-info">
            <div class="row">
                <div class="col-sm-8">
                    <div class="box-header">
                        <h3 class="box-title">
                            <img
                                src="<?= 'data:image/png;base64,' . base64_encode(curlRequest('https://www.google.com/s2/favicons?domain=' . urlencode($link->url) . '&h=16&w=16')->body); ?>">
                            <?= h($link->title); ?>
                        </h3>
                    </div>
                    <div class="box-body">
                        <?= h($link->description); ?>
                        <hr>
                        <div class="row" style="margin-bottom: 10px;">
                            <div class="col-sm-8">
                                <?php if ($plan_link_password && !empty($link->password)) : ?>
                                    <?= $this->Form->create(null); ?>
                                    <?= $this->Form->control('password', [
                                        'label' => false,
                                        'placeholder' => __("Password"),
                                        'type' => 'password',
                                        'class' => 'form-control',
                                    ]); ?>
                                    <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>
                                    <?= $this->Form->end() ?>
                                <?php else : ?>
                                    <div id='timer'>
                                        <button class='btn btn-success btn-lg' disabled>
                                            <i class='fa fa-refresh fa-spin'></i>
                                            <b><span></span></b> <?= __("seconds") ?>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-sm-4">
                                <?= $this->element('share', [
                                    'short_link' => $short_link,
                                ])
                                ?>
                            </div>
                        </div>

                        <?php if ($ads_area2) : ?>
                            <div class="ads_area2 text-center">
                                <?= get_option('ads_area2');
                                ?>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
                <div class="col-sm-4 text-center">
                    <img
                        src="<?= 'data:image/png;base64,' . base64_encode(curlRequest('http://api.miniature.io/?width=360&height=240&screen=1024&url=' . urlencode($link->url))->body); ?>"
                        alt="<?= h($link->title); ?>" title="<?= h($link->title); ?>">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-8">
                <?php if (!empty($comments)) : ?>
                    <?= $this->element('disqus', [
                        'disqus_shortname' => $comments,
                        'disqus_url' => $short_link,
                        'disqus_identifier' => $link->id . '-' . $link->alias,
                        'disqus_title' => $link->title,
                    ]);
                    ?>
                <?php endif; ?>
            </div>
            <div class="col-sm-4">
                <?php if (!empty($feed)) : ?>
                    <?= $this->element('feed', [
                        'feed_url' => $feed,
                    ]);
                    ?>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<?php $this->start('scriptBottom'); ?>
<?php if (empty($link->password)) : ?>
    <script type="text/javascript">
        $(document).ready(function () {
            var count = <?= h($timer); ?>;
            var counter = setInterval(timer, 1000); //1000 will run it every 1 second
            function timer()
            {
                if (count <= 0) {
                    clearInterval(counter);
                    $('#timer').html("<a class='btn btn-success btn-lg flip animated' rel='nofollow'" +
                        "href='<?= h($destination_url); ?>'><?= __("Click here to proceed") ?></a>");
                    return;
                }

                $('#timer button span').html(count);
                count = count - 1;
            }
        });
    </script>
<?php endif; ?>
<?php $this->end(); ?>
