<?php
$baseDir = dirname(dirname(__FILE__));
return [
    'plugins' => [
        'ADmad/SocialAuth' => $baseDir . '/vendor/admad/cakephp-social-auth/',
        'ClassicTheme' => $baseDir . '/plugins/ClassicTheme/',
        'Migrations' => $baseDir . '/vendor/cakephp/migrations/'
    ]
];