<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use GeoIp2\Database\Reader;
use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Device\DeviceParserAbstract;

/**
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\LinksTable&\Cake\ORM\Association\BelongsTo $Links
 * @method \App\Model\Entity\Statistic get($primaryKey, $options = [])
 * @method \App\Model\Entity\Statistic newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Statistic[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Statistic|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Statistic saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Statistic patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Statistic[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Statistic findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StatisticsTable extends Table
{
    public function initialize(array $config)
    {
        $this->belongsTo('Users');
        $this->belongsTo('Links');
        $this->addBehavior('Timestamp');
    }

    public function get_geo($ip)
    {
        try {
            $reader = new Reader(CONFIG . DS . 'binary' . DS . 'geoip' . DS . 'GeoLite2-City.mmdb');
            $record = $reader->city($ip);
            $geo = [
                'continent' => (trim($record->continent->name)) ?: 'Others',
                'country' => (trim($record->country->isoCode)) ?: 'Others',
                'state' => (trim($record->mostSpecificSubdivision->name)) ?: 'Others',
                'city' => (trim($record->city->name)) ?: 'Others',
                'location' => $record->location->latitude . ',' . $record->location->longitude,
                'timezone' => (trim($record->location->timeZone)) ?: 'Others',
            ];
        } catch (\Exception $ex) {
            $geo = [
                'continent' => 'Others',
                'country' => 'Others',
                'state' => 'Others',
                'city' => 'Others',
                'location' => 'Others',
                'timezone' => 'Others',
            ];
        }

        return $geo;
    }

    /**
     * @return \DeviceDetector\DeviceDetector
     * @throws \Exception
     */
    public function getDeviceDetector()
    {
        // OPTIONAL: Set version truncation to none, so full versions will be returned
        // By default only minor versions will be returned (e.g. X.Y)
        // for other options see VERSION_TRUNCATION_* constants in DeviceParserAbstract class
        DeviceParserAbstract::setVersionTruncation(DeviceParserAbstract::VERSION_TRUNCATION_NONE);
        $dd = new DeviceDetector(env('HTTP_USER_AGENT'));
        // OPTIONAL: Set caching method
        // By default static cache is used, which works best within one php process (memory array caching)
        // To cache across requests use caching in files or memcache
        $dd->setCache(new \Doctrine\Common\Cache\PhpFileCache(CACHE . 'ua' . DS));
        // OPTIONAL: If called, getBot() will only return true if a bot was detected  (speeds up detection a bit)
        $dd->discardBotInformation();
        // OPTIONAL: If called, bot detection will completely be skipped (bots will be detected as regular devices then)
        //$dd->skipBotDetection();
        $dd->parse();

        return $dd;
    }

    public function facebook_count($url)
    {
        $query = ['id' => $url];
        $response = curlRequest('https://graph.facebook.com/', 'GET', $query)->body;
        $data = json_decode($response);
        if (isset($data->share->share_count)) {
            return (int)$data->share->share_count;
        }

        return 0;
    }

    public function google_plus_count($url)
    {
        // http://stackoverflow.com/a/21290110/1794834
        $query = [
            'method' => 'pos.plusones.get',
            'id' => 'p',
            'params' => [
                "nolog" => true,
                "id" => $url,
                "source" => "widget",
                "userId" => "@viewer",
                "groupId" => "@self",
            ],
            'jsonrpc' => '2.0',
            'key' => 'p',
            'apiVersion' => 'v1',
        ];
        $headers = ['Content-Type: application/json'];
        $response = curlRequest(
            'https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ',
            'POST',
            json_encode($query),
            $headers
        )->body;
        $data = json_decode($response);
        if (isset($data->result->metadata->globalCounts->count)) {
            return (int)$data->result->metadata->globalCounts->count;
        }

        return 0;
    }

    public function pinterest_count($url)
    {
        $query = ['source' => 6, 'url' => $url];
        $response = curlRequest('https://widgets.pinterest.com/v1/urls/count.json', 'GET', $query)->body;
        $data = preg_replace('/^receiveCount\((.*)\)$/', '\\1', $response);
        if (isset(json_decode($data)->count)) {
            return (int)json_decode($data)->count;
        }

        return 0;
    }

    public function linkedin_count($url)
    {
        $query = ['format' => 'json', 'url' => $url];
        $response = curlRequest('https://www.linkedin.com/countserv/count/share', 'GET', $query)->body;
        $data = json_decode($response);
        if (isset($data->count)) {
            return (int)$data->count;
        }

        return 0;
    }

    public function stumbledupon_count($url)
    {
        $query = ['url' => $url];
        $response = curlRequest('https://www.stumbleupon.com/services/1.01/badge.getinfo', 'GET', $query)->body;
        $data = json_decode($response);
        if (isset($data->result->views)) {
            return (int)$data->result->views;
        }

        return 0;
    }

    public function reddit_count($url)
    {
        $query = ['url' => $url];
        $response = curlRequest('https://buttons.reddit.com/button_info.json', 'GET', $query)->body;
        $data = json_decode($response, true);
        if (isset($data['data']['children'][0]['data']['score'])) {
            return (int)$data['data']['children'][0]['data']['score'];
        }

        return 0;
    }
}
