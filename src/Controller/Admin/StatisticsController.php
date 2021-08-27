<?php

namespace App\Controller\Admin;

/**
 * @property \App\Model\Table\StatisticsTable $Statistics
 */
class StatisticsController extends AppAdminController
{
    public function location()
    {
        if (isset($this->request->query['Filter'])) {
            $date_from = $this->request->query['Filter']['from'];
            $date_from_str = $date_from['year'] . '-' . $date_from['month'] . '-' . $date_from['day'] . ' 00:00:00';
            $date_to = $this->request->query['Filter']['to'];
            $date_to_str = $date_to['year'] . '-' . $date_to['month'] . '-' . $date_to['day'] . ' 00:00:00';
        } else {
            $date_from_str = date('Y-m-d H:i:s', strtotime('last month'));
            $date_to_str = date('Y-m-d H:i:s', strtotime('now'));
            $this->request->query['Filter']['from'] = $date_from_str;
            $this->request->query['Filter']['to'] = $date_to_str;
        }

        $this->set('date_from_str', $date_from_str);
        $this->set('date_to_str', $date_to_str);

        $conditions = [];
        $conditions[] = "Statistics.created BETWEEN :date1 AND :date2";

        if (!empty($this->request->query['Filter']['user_id'])) {
            $conditions['user_id'] = (int)$this->request->query['Filter']['user_id'];
        }

        $linksStats = $this->Statistics->find()
            ->select([
                'day' => 'DATE_FORMAT(Statistics.created,"%d-%m-%Y")',
                'count' => 'COUNT(Statistics.id)'
            ])
            ->where($conditions)
            ->order(['Statistics.created' => 'DESC'])
            ->bind(':date1', $date_from_str, 'datetime')
            ->bind(':date2', $date_to_str, 'datetime')
            ->group('day')
            ->toArray();

        $startTime = strtotime($date_from_str);
        $endTime = strtotime($date_to_str);
        $chartLinksStats = array();
        for ($i = $startTime; $i <= $endTime; $i = $i + DAY) {
            $chartLinksStats[date('d-m-Y', $i)] = 0;
        }
        foreach ($linksStats as $linksStat) {
            if (empty($linksStat->count)) {
                $linksStat->count = 0;
            }
            $chartLinksStats[$linksStat->day] = $linksStat->count;
        }
        $this->set('chartLinksStats', $chartLinksStats);

        $countries = $this->Statistics->find()
            ->select([
                'country',
                'clicks' => 'COUNT(country)'
            ])
            ->where($conditions)
            ->bind(':date1', $date_from_str, 'datetime')
            ->bind(':date2', $date_to_str, 'datetime')
            ->order(['clicks' => 'DESC'])
            ->group('country');

        $this->set('countries', $countries);

        $continents = $this->Statistics->find()
            ->select([
                'continent',
                'clicks' => 'COUNT(continent)'
            ])
            ->where($conditions)
            ->bind(':date1', $date_from_str, 'datetime')
            ->bind(':date2', $date_to_str, 'datetime')
            ->order(['clicks' => 'DESC'])
            ->group('continent');

        $this->set('continents', $continents);

        $states = $this->Statistics->find()
            ->select([
                'state',
                'clicks' => 'COUNT(state)'
            ])
            ->where($conditions)
            ->bind(':date1', $date_from_str, 'datetime')
            ->bind(':date2', $date_to_str, 'datetime')
            ->order(['clicks' => 'DESC'])
            ->group('state');

        $this->set('states', $states);

        $cities = $this->Statistics->find()
            ->select([
                'city',
                'clicks' => 'COUNT(city)'
            ])
            ->where($conditions)
            ->bind(':date1', $date_from_str, 'datetime')
            ->bind(':date2', $date_to_str, 'datetime')
            ->order(['clicks' => 'DESC'])
            ->group('city');

        $this->set('cities', $cities);
    }

    public function referrer()
    {
        if (isset($this->request->query['Filter'])) {
            $date_from = $this->request->query['Filter']['from'];
            $date_from_str = $date_from['year'] . '-' . $date_from['month'] . '-' . $date_from['day'] . ' 00:00:00';
            $date_to = $this->request->query['Filter']['to'];
            $date_to_str = $date_to['year'] . '-' . $date_to['month'] . '-' . $date_to['day'] . ' 00:00:00';
        } else {
            $date_from_str = date('Y-m-d H:i:s', strtotime('last month'));
            $date_to_str = date('Y-m-d H:i:s', strtotime('now'));
            $this->request->query['Filter']['from'] = $date_from_str;
            $this->request->query['Filter']['to'] = $date_to_str;
        }

        $this->set('date_from_str', $date_from_str);
        $this->set('date_to_str', $date_to_str);

        $conditions = [];
        $conditions[] = "Statistics.created BETWEEN :date1 AND :date2";

        if (!empty($this->request->query['Filter']['user_id'])) {
            $conditions['user_id'] = (int)$this->request->query['Filter']['user_id'];
        }

        $referers = $this->Statistics->find()
            ->select([
                'referer_domain',
                'clicks' => 'COUNT(referer_domain)'
            ])
            ->where($conditions)
            ->bind(':date1', $date_from_str, 'datetime')
            ->bind(':date2', $date_to_str, 'datetime')
            ->order(['clicks' => 'DESC'])
            ->group('referer_domain');

        $this->set('referers', $referers);
    }

    public function browser()
    {
        if (isset($this->request->query['Filter'])) {
            $date_from = $this->request->query['Filter']['from'];
            $date_from_str = $date_from['year'] . '-' . $date_from['month'] . '-' . $date_from['day'] . ' 00:00:00';
            $date_to = $this->request->query['Filter']['to'];
            $date_to_str = $date_to['year'] . '-' . $date_to['month'] . '-' . $date_to['day'] . ' 00:00:00';
        } else {
            $date_from_str = date('Y-m-d H:i:s', strtotime('last month'));
            $date_to_str = date('Y-m-d H:i:s', strtotime('now'));
            $this->request->query['Filter']['from'] = $date_from_str;
            $this->request->query['Filter']['to'] = $date_to_str;
        }

        $this->set('date_from_str', $date_from_str);
        $this->set('date_to_str', $date_to_str);

        $conditions = [];
        $conditions[] = "Statistics.created BETWEEN :date1 AND :date2";

        if (!empty($this->request->query['Filter']['user_id'])) {
            $conditions['user_id'] = (int)$this->request->query['Filter']['user_id'];
        }

        $browsers = $this->Statistics->find()
            ->select([
                'browser',
                'clicks' => 'COUNT(browser)'
            ])
            ->where($conditions)
            ->bind(':date1', $date_from_str, 'datetime')
            ->bind(':date2', $date_to_str, 'datetime')
            ->order(['clicks' => 'DESC'])
            ->group('browser');

        $this->set('browsers', $browsers);
    }

    public function platform()
    {
        if (isset($this->request->query['Filter'])) {
            $date_from = $this->request->query['Filter']['from'];
            $date_from_str = $date_from['year'] . '-' . $date_from['month'] . '-' . $date_from['day'] . ' 00:00:00';
            $date_to = $this->request->query['Filter']['to'];
            $date_to_str = $date_to['year'] . '-' . $date_to['month'] . '-' . $date_to['day'] . ' 00:00:00';
        } else {
            $date_from_str = date('Y-m-d H:i:s', strtotime('last month'));
            $date_to_str = date('Y-m-d H:i:s', strtotime('now'));
            $this->request->query['Filter']['from'] = $date_from_str;
            $this->request->query['Filter']['to'] = $date_to_str;
        }

        $this->set('date_from_str', $date_from_str);
        $this->set('date_to_str', $date_to_str);

        $conditions = [];
        $conditions[] = "Statistics.created BETWEEN :date1 AND :date2";

        if (!empty($this->request->query['Filter']['user_id'])) {
            $conditions['user_id'] = (int)$this->request->query['Filter']['user_id'];
        }

        $platforms = $this->Statistics->find()
            ->select([
                'platform',
                'clicks' => 'COUNT(platform)'
            ])
            ->where($conditions)
            ->bind(':date1', $date_from_str, 'datetime')
            ->bind(':date2', $date_to_str, 'datetime')
            ->order(['clicks' => 'DESC'])
            ->group('platform');

        $this->set('platforms', $platforms);
    }

    public function language()
    {
        if (isset($this->request->query['Filter'])) {
            $date_from = $this->request->query['Filter']['from'];
            $date_from_str = $date_from['year'] . '-' . $date_from['month'] . '-' . $date_from['day'] . ' 00:00:00';
            $date_to = $this->request->query['Filter']['to'];
            $date_to_str = $date_to['year'] . '-' . $date_to['month'] . '-' . $date_to['day'] . ' 00:00:00';
        } else {
            $date_from_str = date('Y-m-d H:i:s', strtotime('last month'));
            $date_to_str = date('Y-m-d H:i:s', strtotime('now'));
            $this->request->query['Filter']['from'] = $date_from_str;
            $this->request->query['Filter']['to'] = $date_to_str;
        }

        $this->set('date_from_str', $date_from_str);
        $this->set('date_to_str', $date_to_str);

        $conditions = [];
        $conditions[] = "Statistics.created BETWEEN :date1 AND :date2";

        if (!empty($this->request->query['Filter']['user_id'])) {
            $conditions['user_id'] = (int)$this->request->query['Filter']['user_id'];
        }

        $languages = $this->Statistics->find()
            ->select([
                'language',
                'clicks' => 'COUNT(language)'
            ])
            ->where($conditions)
            ->bind(':date1', $date_from_str, 'datetime')
            ->bind(':date2', $date_to_str, 'datetime')
            ->order(['clicks' => 'DESC'])
            ->group('language');

        $this->set('languages', $languages);
    }

    public function device()
    {
        if (isset($this->request->query['Filter'])) {
            $date_from = $this->request->query['Filter']['from'];
            $date_from_str = $date_from['year'] . '-' . $date_from['month'] . '-' . $date_from['day'] . ' 00:00:00';
            $date_to = $this->request->query['Filter']['to'];
            $date_to_str = $date_to['year'] . '-' . $date_to['month'] . '-' . $date_to['day'] . ' 00:00:00';
        } else {
            $date_from_str = date('Y-m-d H:i:s', strtotime('last month'));
            $date_to_str = date('Y-m-d H:i:s', strtotime('now'));
            $this->request->query['Filter']['from'] = $date_from_str;
            $this->request->query['Filter']['to'] = $date_to_str;
        }

        $this->set('date_from_str', $date_from_str);
        $this->set('date_to_str', $date_to_str);

        $conditions = [];
        $conditions[] = "Statistics.created BETWEEN :date1 AND :date2";

        if (!empty($this->request->query['Filter']['user_id'])) {
            $conditions['user_id'] = (int)$this->request->query['Filter']['user_id'];
        }

        $devices = $this->Statistics->find()
            ->select([
                'device_type',
                'clicks' => 'COUNT(device_type)'
            ])
            ->where($conditions)
            ->bind(':date1', $date_from_str, 'datetime')
            ->bind(':date2', $date_to_str, 'datetime')
            ->order(['clicks' => 'DESC'])
            ->group('device_type');

        $this->set('devices', $devices);
    }

    public function mobile()
    {
        if (isset($this->request->query['Filter'])) {
            $date_from = $this->request->query['Filter']['from'];
            $date_from_str = $date_from['year'] . '-' . $date_from['month'] . '-' . $date_from['day'] . ' 00:00:00';
            $date_to = $this->request->query['Filter']['to'];
            $date_to_str = $date_to['year'] . '-' . $date_to['month'] . '-' . $date_to['day'] . ' 00:00:00';
        } else {
            $date_from_str = date('Y-m-d H:i:s', strtotime('last month'));
            $date_to_str = date('Y-m-d H:i:s', strtotime('now'));
            $this->request->query['Filter']['from'] = $date_from_str;
            $this->request->query['Filter']['to'] = $date_to_str;
        }

        $this->set('date_from_str', $date_from_str);
        $this->set('date_to_str', $date_to_str);

        $conditions = [];
        $conditions[] = "Statistics.created BETWEEN :date1 AND :date2";

        if (!empty($this->request->query['Filter']['user_id'])) {
            $conditions['user_id'] = (int)$this->request->query['Filter']['user_id'];
        }

        $device_brands = $this->Statistics->find()
            ->select([
                'device_brand',
                'clicks' => 'COUNT(device_brand)'
            ])
            ->where($conditions)
            ->bind(':date1', $date_from_str, 'datetime')
            ->bind(':date2', $date_to_str, 'datetime')
            ->order(['clicks' => 'DESC'])
            ->group('device_brand');

        $this->set('device_brands', $device_brands);

        $device_names = $this->Statistics->find()
            ->select([
                'device_name',
                'clicks' => 'COUNT(device_name)'
            ])
            ->where($conditions)
            ->bind(':date1', $date_from_str, 'datetime')
            ->bind(':date2', $date_to_str, 'datetime')
            ->order(['clicks' => 'DESC'])
            ->group('device_name');

        $this->set('device_names', $device_names);
    }
}
