<?php
/**
 * @var \App\View\AppView $this
 * @var mixed $chartLinksStats
 * @var mixed $cities
 * @var mixed $continents
 * @var mixed $countries
 * @var mixed $date_from_str
 * @var mixed $date_to_str
 * @var object $logged_user_plan
 * @var mixed $states
 */
$this->assign('title', __('Location Statistics'));
$this->assign('description', '');
$this->assign('content_title', __('Location Statistics'));
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


<!-- Info box -->
<div class="box box-info">
    <div class="box-header">
        <i class="glyphicon glyphicon-stats"></i>
        <h3 class="box-title"><?= __("Clicks") ?></h3>
    </div>
    <div class="box-body">
        <div class="chart" id="chart_div" style="position: relative; height: 300px; width: 100%;"></div>
    </div><!-- /.box-body -->
</div><!-- /.box -->

<hr>

<?php if ($logged_user_plan->stats == 2) : ?>
    <div class="box box-success">
        <div class="box-header">
            <h3 class="box-title"><?= __("Countries") ?></h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-8">
                    <div id="countries_geochart" style="position: relative; height: 300px; width: 100%;"></div>
                </div>
                <div class="col-sm-4" style="height: 300px;overflow: auto;">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th><?= __("Name") ?></th>
                            <th><?= __("Clicks") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($countries as $country) : ?>
                            <tr>
                                <td><?= $country->country; ?></td>
                                <td><?= $country->clicks; ?></td>
                            </tr>
                        <?php endforeach;
                        unset($country); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <hr>
<?php endif; ?>

<?php if ($logged_user_plan->stats == 3) : ?>
    <div class="row">
        <div class="col-sm-4">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?= __("Continents") ?></h3>
                </div>
                <div class="box-body" style="height: 300px;overflow: auto;">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th><?= __("Name") ?></th>
                            <th><?= __("Clicks") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($continents as $continent) : ?>
                            <tr>
                                <td><?= $continent->continent; ?></td>
                                <td><?= $continent->clicks; ?></td>
                            </tr>
                        <?php endforeach;
                        unset($continent); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?= __("States") ?></h3>
                </div>
                <div class="box-body" style="height: 300px;overflow: auto;">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th><?= __("Name") ?></th>
                            <th><?= __("Clicks") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($states as $state) : ?>
                            <tr>
                                <td><?= $state->state; ?></td>
                                <td><?= $state->clicks; ?></td>
                            </tr>
                        <?php endforeach;
                        unset($state); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?= __("Cities") ?></h3>
                </div>
                <div class="box-body" style="height: 300px;overflow: auto;">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th><?= __("Name") ?></th>
                            <th><?= __("Clicks") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($cities as $city) : ?>
                            <tr>
                                <td><?= $city->city; ?></td>
                                <td><?= $city->clicks; ?></td>
                            </tr>
                        <?php endforeach;
                        unset($city); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php $this->start('scriptBottom'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/almasaeed2010/AdminLTE@v2.3.11/plugins/morris/morris.css">
<script src="https://cdn.jsdelivr.net/gh/DmitryBaranovskiy/raphael@v2.1.0/raphael-min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/almasaeed2010/AdminLTE@v2.3.11/plugins/morris/morris.min.js"
        type="text/javascript"></script>

<script>
    jQuery(document).ready(function () {
        new Morris.Line({
            element: 'chart_div',
            resize: true,
            data: [
                <?php
                foreach ($chartLinksStats as $key => $value) {
                    $date = date("Y-m-d", strtotime($key));
                    echo '{date: "' . $date . '", views: ' . $value . '},';
                }
                ?>
            ],
            xkey: 'date',
            xLabels: 'day',
            ykeys: ['views'],
            labels: ['<?= __('Views') ?>'],
            lineColors: ['#3c8dbc'],
            lineWidth: 2,
            hideHover: 'auto',
            smooth: false
        });
    });
</script>

<?php if ($logged_user_plan->stats == 2) : ?>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type='text/javascript'>
        google.load('visualization', '1', {'packages': ['geochart']});
        google.setOnLoadCallback(drawRegionsMap);

        function drawRegionsMap()
        {
            var data = google.visualization.arrayToDataTable([
                ['Country', 'Clicks'],
                <?php
                foreach ($countries as $country) {
                    echo '["' . $country->country . '", ' . $country->clicks . '],';
                }
                ?>
            ]);

            var options = {};

            var chart = new google.visualization.GeoChart(document.getElementById('countries_geochart'));
            chart.draw(data, options);
        }
    </script>
<?php endif; ?>

<?php $this->end(); ?>
