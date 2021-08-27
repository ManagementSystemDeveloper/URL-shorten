<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $logged_user
 * @var \App\Model\Entity\Plan $logged_user_plan
 * @var \App\Model\Entity\User $user
 */
?>
<?php
$this->assign('title', __('Profile'));
$this->assign('description', '');
$this->assign('content_title', __('Profile'));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($user); ?>

        <?= $this->Form->hidden('id'); ?>

        <div class="row">
            <div class="col-sm-6">
                <?=
                $this->Form->control('first_name', [
                    'label' => __('First Name'),
                    'class' => 'form-control'
                ])
                ?>
            </div>
            <div class="col-sm-6">
                <?=
                $this->Form->control('last_name', [
                    'label' => __('Last Name'),
                    'class' => 'form-control'
                ])
                ?>
            </div>
        </div>

        <?php
        $redirects = get_allowed_redirects();

        if (count($redirects) > 1) {
            echo $this->Form->control('redirect_type', [
                'label' => __('Redirect Type'),
                'options' => $redirects,
                'default' => get_option('member_default_redirect', 1),
                //'empty'   => __( 'Choose' ),
                'class' => 'form-control input-sm'
            ]);
        } else {
            echo $this->Form->hidden('redirect_type', ['value' => get_option('member_default_redirect', 1)]);
        }
        ?>

        <?php if ($logged_user_plan->comments) : ?>
            <?=
            $this->Form->control('disqus_shortname', [
                'label' => __('Disqus Shortname'),
                'class' => 'form-control',
                'type' => 'text',
                'templateVars' => [
                    'help' => __("To display comment box, you must create an account at Disqus website by " .
                        "signing up from <a href='https://disqus.com/profile/signup/' target='_blank'>here</a> " .
                        "then add your website their from <a href='https://disqus.com/admin/create/' " .
                        "target='_blank'>here</a> and get your shortname.")
                ]
            ]);
            ?>
        <?php endif; ?>

        <?php if ($logged_user_plan->feed) : ?>
            <?=
            $this->Form->control('feed', [
                'label' => __('Your RSS Feed URL'),
                'class' => 'form-control',
                'type' => 'url'
            ])
            ?>
        <?php endif; ?>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary btn-lg']); ?>

        <?= $this->Form->end() ?>

    </div>
</div>
