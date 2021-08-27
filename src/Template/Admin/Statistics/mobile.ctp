<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $date_from_str
 * @var mixed $date_to_str
 * @var mixed $device_brands
 * @var mixed $device_names
 */
$this->assign('title', __('Mobile Statistics'));
$this->assign('description', '');
$this->assign('content_title', __('Mobile Statistics'));
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

<div class="row">
    <div class="col-sm-6">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title"><?= __("Mobile Device Brands") ?></h3>
            </div>
            <div class="box-body" style="height: 500px;overflow: auto;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th><?= __("Name") ?></th>
                        <th><?= __("Clicks") ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($device_brands as $device_brand) : ?>
                        <tr>
                            <td><?= $device_brand->device_brand; ?></td>
                            <td><?= $device_brand->clicks; ?></td>
                        </tr>
                    <?php endforeach;
                    unset($device_brand); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title"><?= __("Mobile Device Names") ?></h3>
            </div>
            <div class="box-body" style="height: 500px;overflow: auto;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th><?= __("Name") ?></th>
                        <th><?= __("Clicks") ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($device_names as $device_name) : ?>
                        <tr>
                            <td><?= $device_name->device_name; ?></td>
                            <td><?= $device_name->clicks; ?></td>
                        </tr>
                    <?php endforeach;
                    unset($device_name); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<?php $this->start('scriptBottom'); ?>

<?php $this->end(); ?>
