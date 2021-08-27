<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $browsers
 * @var mixed $date_from_str
 * @var mixed $date_to_str
 */
$this->assign('title', __('Browser Statistics'));
$this->assign('description', '');
$this->assign('content_title', __('Browser Statistics'));
?>

<div class="box box-solid">
    <div class="box-body">
        <?php
        // The base url is the url where we'll pass the filter parameters
        $base_url = [
            'controller' => $this->request->params['controller'],
            'action' => $this->request->params['action']
        ];

        echo $this->Form->create(null, [
            'url' => $base_url,
            'type' => 'get',
            'class' => 'form-inline'
        ]);
        ?>

        <?=
        $this->Form->control('Filter.user_id', [
            'label' => false,
            'class' => 'form-control',
            'type' => 'number',
            'step' => 1,
            'min' => 1,
            'placeholder' => __('User ID'),
            'value' => (isset($this->request->query['Filter']['user_id'])) ?
                $this->request->query['Filter']['user_id'] : ''
        ]);
        ?>

        <?=
        $this->Form->control('Filter.from', [
            'label' => __("Date From"),
            'class' => 'form-control',
            'type' => 'date',
            'year' => [
                'class' => 'form-control',
            ],
            'month' => [
                'class' => 'form-control',
            ],
            'day' => [
                'class' => 'form-control',
            ],
            'default' => $date_from_str
        ]);
        ?>

        <?=
        $this->Form->control('Filter.to', [
            'label' => __("Date To"),
            'class' => 'form-control',
            'type' => 'date',
            'year' => [
                'class' => 'form-control',
            ],
            'month' => [
                'class' => 'form-control',
            ],
            'day' => [
                'class' => 'form-control',
            ],
            'default' => $date_to_str
        ]);
        ?>

        <?= $this->Form->button(__('Filter'), ['class' => 'btn btn-default btn-sm']); ?>

        <?= $this->Html->link(__('Reset'), $base_url, ['class' => 'btn btn-link btn-sm']); ?>

        <?= $this->Form->end(); ?>

    </div>
</div>

<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title"><?= __("Browsers") ?></h3>
    </div>
    <div class="box-body">
        <table class="table table-hover">
            <thead>
            <tr>
                <th><?= __("Name") ?></th>
                <th><?= __("Clicks") ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($browsers as $browser) : ?>
                <tr>
                    <td><?= $browser->browser; ?></td>
                    <td><?= $browser->clicks; ?></td>
                </tr>
            <?php endforeach;
            unset($browser); ?>
            </tbody>
        </table>
    </div>
</div>


<?php $this->start('scriptBottom'); ?>

<?php $this->end(); ?>
