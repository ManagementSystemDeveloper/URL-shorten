<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', __('View User #{0}', $user->id));
$this->assign('description', '');
$this->assign('content_title', __('View User #{0}', $user->id));
?>

<?php
$statuses = [
    1 => __('Active'),
    2 => __('Pending'),
    3 => __('Inactive')
];
$roles = [
    1 => __('Admin'),
    2 => __('Member')
];
?>

<div class="box box-primary">
    <div class="box-body">

        <legend><?= __('Account Info.') ?></legend>
        <table class="table table-striped table-hover">
            <tr>
                <td><?= __('ID') ?></td>
                <td><?= $user->id ?></td>
            </tr>
            <tr>
                <td><?= __('role') ?></td>
                <td><?= h($roles[$user->role]) ?></td>
            </tr>
            <tr>
                <td><?= __('Status') ?></td>
                <td><?= $statuses[$user->status] ?></td>
            </tr>
            <tr>
                <td><?= __('Username') ?></td>
                <td><?= h($user->username) ?></td>
            </tr>
            <tr>
                <td><?= __("Plan ID") ?></td>
                <td><?= h($user->plan_id) ?></td>
            </tr>
            <tr>
                <td><?= __('Plan Expiration Date') ?></td>
                <td><?= display_date_timezone($user->expiration) ?></td>
            </tr>
            <tr>
                <td><?= __('Email') ?></td>
                <td><?= h($user->email) ?></td>
            </tr>
            <tr>
                <td><?= __('Temp Email') ?></td>
                <td><?= h($user->tempEmail) ?></td>
            </tr>
            <tr>
                <td><?= __('First Name') ?></td>
                <td><?= h($user->first_name) ?></td>
            </tr>
            <tr>
                <td><?= __('last Name') ?></td>
                <td><?= h($user->last_name) ?></td>
            </tr>
            <tr>
                <td><?= __('Api Token') ?></td>
                <td><?= h($user->api_token) ?></td>
            </tr>
            <tr>
                <td><?= __('URLs') ?></td>
                <td><?= h($user->urls) ?></td>
            </tr>
            <tr>
                <td><?= __('Feed') ?></td>
                <td><?= h($user->feed) ?></td>
            </tr>
            <tr>
                <td><?= __('Redirect Type') ?></td>
                <td><?= h(get_allowed_redirects()[$user->redirect_type]) ?></td>
            </tr>
            <tr>
                <td><?= __('Disqus Shortname') ?></td>
                <td><?= h($user->disqus_shortname) ?></td>
            </tr>
            <tr>
                <td><?= __('Login IP') ?></td>
                <td><?= h($user->login_ip) ?></td>
            </tr>
            <tr>
                <td><?= __('Registration IP') ?></td>
                <td><?= h($user->register_ip) ?></td>
            </tr>
            <tr>
                <td><?= __('Last Login') ?></td>
                <td><?= display_date_timezone($user->last_login) ?></td>
            </tr>
            <tr>
                <td><?= __('Modified') ?></td>
                <td><?= display_date_timezone($user->modified) ?></td>
            </tr>
            <tr>
                <td><?= __('Created') ?></td>
                <td><?= display_date_timezone($user->created) ?></td>
            </tr>
        </table>

        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id], ['class' => 'btn btn-primary']); ?>

        <?= $this->Form->postLink(
            __('Deactivate'),
            ['action' => 'deactivate', $user->id],
            ['confirm' => __('Are you sure?'), 'class' => 'btn btn-danger']
        );
        ?>
    </div>
</div>
