<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $disqus_identifier
 * @var mixed $disqus_shortname
 * @var mixed $disqus_title
 * @var mixed $disqus_url
 */
?>
<div class="box box-solid box-primary">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-comments-o"></i> <?= __("Comments") ?></h3>
    </div>
    <div class="box-body">
        <div id="disqus_thread"></div>
        <script>
            var disqus_config = function () {
                this.language = "<?= locale_get_primary_language(null) ?>";
                this.page.url = '<?= h($disqus_url); ?>';
                this.page.identifier = '<?= h($disqus_identifier); ?>';
                this.page.title = "<?= h($disqus_title); ?>";
            };

            (function () { // DON'T EDIT BELOW THIS LINE
                var d = document, s = d.createElement('script');

                s.src = '//<?= h($disqus_shortname); ?>.disqus.com/embed.js';

                s.setAttribute('data-timestamp', +new Date());
                ( d.head || d.body ).appendChild(s);
            })();
        </script>
    </div>
</div>
