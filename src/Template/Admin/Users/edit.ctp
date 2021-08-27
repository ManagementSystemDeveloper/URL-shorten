<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var mixed $plans
 */
?>
<?php
$this->assign('title', __('Edit User #{0}', $user->id));
$this->assign('description', '');
$this->assign('content_title', __('Edit User #{0}', $user->id));
?>

<div class="box box-primary">
    <div class="box-body">

        <?= $this->Form->create($user); ?>

        <?= $this->Form->hidden('id') ?>

        <?=
        $this->Form->control('role', [
            'label' => __('Role'),
            'options' => [
                2 => __('Member'),
                1 => __('Admin')
            ],
            'class' => 'form-control'
        ]);
        ?>

        <?=
        $this->Form->control('status', [
            'label' => __('Status'),
            'options' => [
                1 => __('Active'),
                2 => __('Pending'),
                3 => __('Inactive')
            ],
            'class' => 'form-control'
        ]);
        ?>

        <?=
        $this->Form->control('username', [
            'label' => __('Username'),
            'class' => 'form-control',
            "autocomplete" => "false"
        ])
        ?>

        <?=
        $this->Form->control('email', [
            'label' => __('Email'),
            'type' => 'email',
            'class' => 'form-control',
            "autocomplete" => "false"
        ])
        ?>

        <div class="row">
            <div class="col-sm-6">
                <?=
                $this->Form->control('plan_id', [
                    'label' => __('Plan'),
                    'options' => $plans,
                    'empty' => __('Choose Plan'),
                    'class' => 'form-control'
                ]);
                ?>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="exampleInputEmail1"><?= __('Plan Expiration Date') ?></label>
                    <div><?= $this->Form->date('expiration'); ?></div>
                </div>

            </div>
        </div>

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
                'options' => get_allowed_redirects(),
                'default' => get_option('member_default_redirect', 1),
                //'empty'   => __( 'Choose' ),
                'class' => 'form-control input-sm'
            ]);
        } else {
            echo $this->Form->hidden('redirect_type', ['value' => get_option('member_default_redirect', 1)]);
        }
        ?>

        <?=
        $this->Form->control('disqus_shortname', [
            'label' => __('Disqus Shortname'),
            'class' => 'form-control',
            'type' => 'text',
            'templateVars' => ['help' => __("To display comment box, you must create an account at Disqus ".
                "website by signing up from <a href='https://disqus.com/profile/signup/' target='_blank'>here</a> ".
                "then add your website their from <a href='https://disqus.com/admin/create/' target='_blank'>here</a> ".
                "and get your shortname.")]
        ]);
        ?>

        <?=
        $this->Form->control('feed', [
            'label' => __('Your RSS Feed URL'),
            'class' => 'form-control',
            'type' => 'url'
        ])
        ?>

        <?= $this->Form->button(__('Submit'), ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->end(); ?>
    </div>
</div>
