<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $logged_user
 */
$this->assign('title', __('Full Page Script'));
$this->assign('description', '');
$this->assign('content_title', __('Full Page Script'));
?>

<div class="box box-primary">
    <div class="box-body">
        <p><?= __(
            "If you have a website with 100's or 1000's of links you want to change over to {0} then please use the script below.",
            get_option('site_name')
        ) ?></p>

        <h3 class="page-header"><?= __('Full Page Script Code Generator') ?></h3>

        <?= $this->Form->create(null, [
            'id' => 'full-page-script-generator',
        ]); ?>

        <?php
        $allowed_ads = get_allowed_redirects();
        ?>

        <?php if (count($allowed_ads) > 1) : ?>
            <div class="row">
                <div class="col-sm-2">
                    <label><?= __('Redirect Type') ?></label>
                </div>
                <div class="col-sm-10">
                    <?= $this->Form->control('redirect_type', [
                        'label' => false,
                        'options' => $allowed_ads,
                        'default' => get_option('member_default_redirect', 1),
                        'class' => 'form-control',
                        'required' => true,
                    ]); ?>
                </div>
            </div>
        <?php else : ?>
            <?= $this->Form->hidden('redirect_type', ['value' => get_option('member_default_redirect', 1)]); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-sm-2">
                <label><?= __('Domains selection type') ?></label>
            </div>
            <div class="col-sm-10">
                <?=
                $this->Form->control('domains_type', [
                    'label' => false,
                    'options' => [
                        'include' => 'Include',
                        'exclude' => 'Exclude',
                    ],
                    'class' => 'form-control',
                    'required' => true,
                    'templateVars' => [
                        'help' => __(
                            'Include: Use this option if you want to short only links from the following domains list.<br>' .
                            'Exclude: Use this option if you wish to short every link on your website but exclude only the links ' .
                            'from the following domains list.'
                        ),
                    ],
                ]);
                ?>
            </div>
        </div>

        <?=
        $this->Form->control('domains', [
            'label' => __('Domains'),
            'type' => 'textarea',
            'class' => 'form-control',
            'required' => true,
            'templateVars' => [
                'help' => __(
                        'Add each domain into a new domain. Also wildcard domains are allowed. Please check the following example:'
                    ) . "<br>mega.nz<br>*.zippyshare.com<br>depositfiles.com",
            ],
        ]);
        ?>

        <p><?= __("Simply click on the below button then copy-and-paste the generated code below on to your " .
                "webpage or blog and the links will be updated automatically!") ?></p>

        <div class="form-group">
            <?= $this->Form->button(__('Generate'), ['class' => 'btn btn-primary']); ?>
        </div>

        <?= $this->Form->end(); ?>

        <?php
        $script_url = str_replace(['http://', 'https://'], ['//', '//'], $this->Url->build('/', true));
        ?>
        <textarea id="code_template" style="display: none;">
<script type="text/javascript">
    var app_url = '<?= $this->Url->build('/', true); ?>';
    var app_api_token = '<?= $logged_user->api_token ?>';
    var app_redirect = {redirect_type};
    var {app_domains} = {domains};
</script>
<script src='<?= $script_url; ?>js/full-page-script.js'></script>
</textarea>

        <pre id="generated_code"></pre>

    </div>
</div>

<?php $this->start('scriptBottom'); ?>
<style>
    #generated_code:empty {
        display: none;
    }
</style>
<script>
    $('#full-page-script-generator').on('change', function(e) {
        $('#generated_code').text('');
    });

    $('#full-page-script-generator').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(e.target);

        var redirect_type = formData.get('redirect_type');
        var domains_type = formData.get('domains_type');
        var domains = formData.get('domains').split(/\n/);

        var generated_code = $('#code_template');

        var code = generated_code.val();

        code = code.replace('{redirect_type}', redirect_type);

        if (domains_type === 'include') {
            code = code.replace('{app_domains}', 'app_domains');
        } else {
            code = code.replace('{app_domains}', 'app_exclude_domains');
        }

        var domainsText = [];
        for (var i = 0; i < domains.length; i++) {
            // only push this line if it contains a non whitespace character.
            if (/\S/.test(domains[i])) {
                domainsText.push('"' + $.trim(domains[i]) + '"');
            }
        }

        code = code.replace('{domains}', '[' + domainsText + ']');

        $('#generated_code').text(code);

        //var form = $(this);
        //alert($('#ad-type').val());
    });
</script>
<?php $this->end(); ?>
