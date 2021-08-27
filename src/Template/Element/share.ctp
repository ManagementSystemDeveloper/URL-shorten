<?php
/**
 * @var \App\View\AppView $this
 * @var string $short_link
 */
?>
<!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style addthis_32x32_style" addthis:url="<?= $short_link; ?>">
    <a class="addthis_button_facebook"></a>
    <a class="addthis_button_twitter"></a>
    <a class="addthis_button_pinterest_share"></a>
    <a class="addthis_button_google_plusone_share"></a>
    <a class="addthis_button_linkedin"></a>
    <a class="addthis_button_compact"></a>
</div>
<script type="text/javascript">
    var addthis_config = {
        data_track_clickback: false
    };
</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js" async="async"></script>
<!-- AddThis Button END -->
