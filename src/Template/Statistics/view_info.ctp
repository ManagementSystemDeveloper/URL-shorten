<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Link $link
 * @var mixed $browsers
 * @var mixed $cities
 * @var mixed $continents
 * @var mixed $countries
 * @var mixed $device_brands
 * @var mixed $device_names
 * @var mixed $devices
 * @var string $facebook_count
 * @var string $google_plus_count
 * @var mixed $languages
 * @var string $linkedin_count
 * @var string $pinterest_count
 * @var mixed $plan_stats
 * @var mixed $platforms
 * @var string $reddit_count
 * @var mixed $referrers
 * @var mixed $states
 * @var mixed $stats
 * @var string $stumbledupon_count
 */
$this->assign('title', __("Link Statistics on Last 30 Days"));
$this->assign('description', get_option('site_description'));
$this->assign('content_title', __("Link Statistics on Last 30 Days"));
?>

<div class="container">

    <h3 class="page-title"><?= __("Link Statistics on Last 30 Days") ?></h3>

    <div class="box">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-3 text-center">
                    <img src="<?= 'data:image/png;base64,' . base64_encode(curlRequest('http://api.miniature.io/?width=400&height=300&screen=1366&url=' . urlencode($link->url))->body); ?>"
                         alt="<?= h($link->title); ?>" title="<?= h($link->title); ?>">
                </div>
                <div class="col-sm-7">
                    <h3 class="page-header title">
                        <?= $this->Html->link($link->title, get_short_url($link->alias, $link->domain)); ?>
                    </h3>
                    <p>
                        <small><?= $this->Text->autoLinkUrls($link->url, ['class' => 'text-muted']); ?></small>
                    </p>
                    <p><?= h($link->description); ?></p>
                </div>
                <div class="col-sm-2 text-center">
                    <img alt="QR code"
                         src="//chart.googleapis.com/chart?cht=qr&amp;chs=150x150&amp;choe=UTF-8&amp;chld=H|0&amp;chl=<?= urlencode(get_short_url(
                             $link->alias,
                             $link->domain
                         )); ?>">
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="box box-info">
        <div class="box-header with-border">
            <i class="glyphicon glyphicon-stats"></i>
            <h3 class="box-title"><?= __("Clicks") ?></h3>
        </div>
        <div class="box-body">
            <div class="chart" id="last-month-hits" style="position: relative; height: 300px; width: 100%;"></div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

    <hr>

    <?php
    /*
    <div class="box box-info">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-4 text-center">
                    <span class="display-counter"><?= $todayClicks ?></span>
                    <span>Today Clicks</span>
                </div>
                <div class="col-sm-4 text-center">
                    <span class="display-counter"><?= $yesterdayClicks ?></span>
                    <span>Yesterday Clicks</span>
                </div>
                <div class="col-sm-4 text-center">
                    <span class="display-counter"><?= $totalClicks ?></span>
                    <span>Total Clicks</span>
                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box -->

    <hr>
    */
    ?>
    <div class="box box-success">
        <div class="box-header with-border">
            <i class="fa fa-globe"></i>
            <h3 class="box-title"><?= __("Countries") ?></h3>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-8">
                    <div id="countries_geochart" style="position: relative; height: 300px; width: 100%;"></div>
                </div>
                <div class="col-sm-4" style="max-height: 300px;overflow: auto;">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th><?= __("Country") ?></th>
                            <th><?= __("Clicks") ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($countries as $country) : ?>
                            <tr>
                                <td><?= $country->country ?></td>
                                <td><?= $country->clicks ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php unset($country); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <?php if ($plan_stats == 3) : ?>
        <div class="row">
            <div class="col-sm-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-globe"></i>
                        <h3 class="box-title"><?= __("Continents") ?></h3>
                    </div>
                    <div class="box-body" style="max-height: 300px;overflow: auto;">
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
                    <div class="box-header with-border">
                        <i class="fa fa-globe"></i>
                        <h3 class="box-title"><?= __("States") ?></h3>
                    </div>
                    <div class="box-body" style="max-height: 300px;overflow: auto;">
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
                    <div class="box-header with-border">
                        <i class="fa fa-globe"></i>
                        <h3 class="box-title"><?= __("Cities") ?></h3>
                    </div>
                    <div class="box-body" style="max-height: 300px;overflow: auto;">
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

        <hr>

        <div class="row">
            <div class="col-sm-6">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <i class="fa fa-external-link"></i>
                        <h3 class="box-title"><?= __("Referrers") ?></h3>
                    </div>
                    <div class="box-body" style="max-height: 300px;overflow: auto;">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th><?= __("Domain") ?></th>
                                <th><?= __("Clicks") ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($referrers as $referrer) : ?>
                                <tr>
                                    <td><?= $referrer->referer_domain ?></td>
                                    <td><?= $referrer->clicks ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php unset($referrer); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-file-text-o"></i>
                        <h3 class="box-title"><?= __("Browsers") ?></h3>
                    </div>
                    <div class="box-body" style="max-height: 300px;overflow: auto;">
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
            </div>
        </div>

        <hr>

        <div class="box box-info wow fadeInUp">
            <div class="box-header">
                <i class="fa fa-share-square-o"></i>
                <h3 class="box-title"><?= __("Social Media Counts") ?></h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-2 text-center">
                        <span class="display-counter"><?= $facebook_count; ?></span>
                        <i class="fa fa-facebook-square fa-3x" style="color: #3b5998;"></i>
                    </div>
                    <div class="col-sm-2 text-center">
                        <span class="display-counter"><?= $google_plus_count; ?></span>
                        <i class="fa fa-google-plus-square fa-3x" style="color: #dd4b39;"></i>
                    </div>
                    <div class="col-sm-2 text-center">
                        <span class="display-counter"><?= $pinterest_count; ?></span>
                        <i class="fa fa-pinterest-square fa-3x" style="color: #cb2027;"></i>
                    </div>
                    <div class="col-sm-2 text-center">
                        <span class="display-counter"><?= $linkedin_count; ?></span>
                        <i class="fa fa-linkedin-square fa-3x" style="color: #007bb6;"></i>
                    </div>
                    <div class="col-sm-2 text-center">
                        <span class="display-counter"><?= $stumbledupon_count; ?></span>
                        <i class="fa fa-stumbleupon-circle fa-3x" style="color: #EB4823;"></i>
                    </div>
                    <div class="col-sm-2 text-center">
                        <span class="display-counter"><?= $reddit_count; ?></span>
                        <i class="fa fa-reddit-square fa-3x" style="color: #333333;"></i>
                    </div>
                </div>
            </div><!-- /.box-body -->
        </div><!-- /.box -->

        <hr>

        <div class="row">
            <div class="col-sm-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-desktop"></i>
                        <h3 class="box-title"><?= __("Platforms") ?></h3>
                    </div>
                    <div class="box-body" style="max-height: 300px;overflow: auto;">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th><?= __("Name") ?></th>
                                <th><?= __("Clicks") ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($platforms as $platform) : ?>
                                <tr>
                                    <td><?= $platform->platform; ?></td>
                                    <td><?= $platform->clicks; ?></td>
                                </tr>
                            <?php endforeach;
                            unset($platform); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <i class="fa fa-language"></i>
                        <h3 class="box-title"><?= __("Languages") ?></h3>
                    </div>
                    <div class="box-body" style="max-height: 300px;overflow: auto;">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th><?= __("Name") ?></th>
                                <th><?= __("Clicks") ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($languages as $language) : ?>
                                <tr>
                                    <td><?= $language->language; ?></td>
                                    <td><?= $language->clicks; ?></td>
                                </tr>
                            <?php endforeach;
                            unset($language); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-sm-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("Devices") ?></h3>
                    </div>
                    <div class="box-body" style="max-height: 500px;overflow: auto;">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th><?= __("Name") ?></th>
                                <th><?= __("Clicks") ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($devices as $device) : ?>
                                <tr>
                                    <td><?= $device->device_type; ?></td>
                                    <td><?= $device->clicks; ?></td>
                                </tr>
                            <?php endforeach;
                            unset($device); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("Mobile Device Brands") ?></h3>
                    </div>
                    <div class="box-body" style="max-height: 500px;overflow: auto;">
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
            <div class="col-sm-4">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?= __("Mobile Device Names") ?></h3>
                    </div>
                    <div class="box-body" style="max-height: 500px;overflow: auto;">
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
    <?php endif; ?>
</div>


<?php $this->start('scriptBottom'); ?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/almasaeed2010/AdminLTE@v2.3.11/plugins/morris/morris.css">
<script src="https://cdn.jsdelivr.net/gh/DmitryBaranovskiy/raphael@v2.1.0/raphael-min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/almasaeed2010/AdminLTE@v2.3.11/plugins/morris/morris.min.js"
        type="text/javascript"></script>

<script>

    jQuery(document).ready(function () {
        new Morris.Line({
            element: 'last-month-hits',
            resize: true,
            data: [
                <?php
                $last30days = array();
                for ($i = 30; $i > 0; $i--) {
                    $last30days[date('d-m-Y', strtotime('-' . $i . ' days'))] = 0;
                }
                foreach ($stats as $stat) {
                    if (empty($stat->statDateCount)) {
                        $stat->statDateCount = 0;
                    }
                    $last30days[$stat->statDate] = $stat->statDateCount;
                }

                foreach ($last30days as $key => $value) {
                    $date = date("Y-m-d", strtotime($key));
                    echo '{date: "' . $date . '", clicks: ' . $value . '},';
                }
                ?>
            ],
            xkey: 'date',
            xLabels: 'day',
            ykeys: ['clicks'],
            labels: ['Clicks'],
            lineWidth: 2,
            hideHover: 'auto',
            smooth: false
        });

    });
</script>

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

<?php $this->end(); ?>
