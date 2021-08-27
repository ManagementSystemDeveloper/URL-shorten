<?php

namespace App\Controller;

use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\StatisticsTable $Statistics
 */
class StatisticsController extends FrontController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['viewInfo']);
    }

    public function viewInfo($alias = null)
    {
        if (!$alias) {
            throw new NotFoundException(__('404 Not Found'));
        }

        if (null !== $this->Auth->user('id')) {
            if (get_option('link_info_member', 'yes') == 'no') {
                throw new NotFoundException(__('Invalid link'));
            }
        } else {
            if (get_option('link_info_public', 'yes') == 'no') {
                throw new NotFoundException(__('Invalid link'));
            }
        }

        /**
         * @var \App\Model\Entity\Link $link
         */
        $link = $this->Statistics->Links->find()->where(['alias' => $alias, 'status <>' => 3])->first();
        if (!$link) {
            throw new NotFoundException(__('404 Not Found'));
        }
        $this->set('link', $link);

        /**
         * @var \App\Model\Entity\User $user
         */
        $user = $this->Statistics->Links->Users->find()
            ->contain('Plans')
            ->where([
                'Users.id' => $link->user_id,
                'Users.status' => 1,
            ])
            ->first();
        if (!$user) {
            throw new NotFoundException(__('404 Not Found'));
        }

        if (null !== $this->Auth->user('id')) {
            if ($this->Auth->user('role') === 2) {
                if (get_option('link_info_member', 'yes') === 'no') {
                    throw new NotFoundException(__('404 Not Found'));
                }

                if ($this->Auth->user('id') !== $user->id) {
                    throw new NotFoundException(__('404 Not Found'));
                }
            }
        } else {
            if (get_option('link_info_public', 'yes') === 'no') {
                throw new NotFoundException(__('404 Not Found'));
            }

            if (1 !== $user->id) {
                throw new NotFoundException(__('404 Not Found'));
            }
        }

        if (!get_user_plan($user->id)->stats) {
            if ($this->Auth->user()) {
                $this->Flash->error(__('You must upgrade your plan so you can see the statistics.'));

                return $this->redirect(['_name' => 'member_dashboard']);
            } else {
                throw new NotFoundException(__('404 Not Found'));
            }
        }

        $short_link = get_short_url($link->alias, $link->domain);
        $this->set('short_link', $short_link);

        if (get_user_plan($user->id)->stats === 1) {
            if ($this->Auth->user()) {
                $this->Flash->error(__('You must upgrade your plan so you can see the statistics.'));

                return $this->redirect(['_name' => 'member_dashboard']);
            } else {
                throw new NotFoundException(__('404 Not Found'));
            }
        }

        $plan_stats = get_user_plan($user->id)->stats;

        $this->set('plan_stats', $plan_stats);

        $now = Time::now()->format('Y-m-d H:i:s');
        $last30 = Time::now()->modify('-30 day')->format('Y-m-d H:i:s');

        $stats = $this->Statistics->find()
            ->select([
                'statDate' => 'DATE_FORMAT(created,"%d-%m-%Y")',
                'statDateCount' => 'COUNT(DATE_FORMAT(created,"%d-%m-%Y"))',
            ])
            ->where([
                'link_id' => $link->id,
                'created BETWEEN :last30 AND :now',
            ])
            ->bind(':last30', $last30, 'datetime')
            ->bind(':now', $now, 'datetime')
            ->order(['created' => 'DESC'])
            ->group('statDate');

        $this->set('stats', $stats);

        /*
        $todayClicks = $this->Statistics->find()
            ->where([
                'link_id' => $link->id,
                'DATE(created) = CURDATE()'
            ])
            ->count();
        $this->set('todayClicks', $todayClicks);

        $yesterdayClicks = $this->Statistics->find()
            ->where([
                'link_id' => $link->id,
                'DATE(created) = CURDATE() - 1'
            ])
            ->count();
        $this->set('yesterdayClicks', $yesterdayClicks);

        $totalClicks = $this->Statistics->find()
            ->where([
                'link_id' => $link->id
            ])
            ->count();
        $this->set('totalClicks', $totalClicks);
        */

        $countries = $this->Statistics->find()
            ->select([
                'country',
                'clicks' => 'COUNT(country)',
            ])
            ->where([
                'link_id' => $link->id,
                'created BETWEEN :last30 AND :now',
            ])
            ->bind(':last30', $last30, 'datetime')
            ->bind(':now', $now, 'datetime')
            ->order(['clicks' => 'DESC'])
            ->group('country');

        $this->set('countries', $countries);

        if ($plan_stats == 3) {
            $continents = $this->Statistics->find()
                ->select([
                    'continent',
                    'clicks' => 'COUNT(continent)',
                ])
                ->where([
                    'link_id' => $link->id,
                    'created BETWEEN :last30 AND :now',
                ])
                ->bind(':last30', $last30, 'datetime')
                ->bind(':now', $now, 'datetime')
                ->order(['clicks' => 'DESC'])
                ->group('continent');

            $this->set('continents', $continents);

            $states = $this->Statistics->find()
                ->select([
                    'state',
                    'clicks' => 'COUNT(state)',
                ])
                ->where([
                    'link_id' => $link->id,
                    'created BETWEEN :last30 AND :now',
                ])
                ->bind(':last30', $last30, 'datetime')
                ->bind(':now', $now, 'datetime')
                ->order(['clicks' => 'DESC'])
                ->group('state');

            $this->set('states', $states);

            $cities = $this->Statistics->find()
                ->select([
                    'city',
                    'clicks' => 'COUNT(city)',
                ])
                ->where([
                    'link_id' => $link->id,
                    'created BETWEEN :last30 AND :now',
                ])
                ->bind(':last30', $last30, 'datetime')
                ->bind(':now', $now, 'datetime')
                ->order(['clicks' => 'DESC'])
                ->group('city');

            $this->set('cities', $cities);

            $referrers = $this->Statistics->find()
                ->select([
                    'referer_domain',
                    'clicks' => 'COUNT(referer)',
                ])
                ->where([
                    'link_id' => $link->id,
                    'created BETWEEN :last30 AND :now',
                ])
                ->bind(':last30', $last30, 'datetime')
                ->bind(':now', $now, 'datetime')
                ->order(['clicks' => 'DESC'])
                ->group('referer_domain');

            $this->set('referrers', $referrers);

            $browsers = $this->Statistics->find()
                ->select([
                    'browser',
                    'clicks' => 'COUNT(browser)',
                ])
                ->where([
                    'link_id' => $link->id,
                    'created BETWEEN :last30 AND :now',
                ])
                ->bind(':last30', $last30, 'datetime')
                ->bind(':now', $now, 'datetime')
                ->order(['clicks' => 'DESC'])
                ->group('browser');

            $this->set('browsers', $browsers);

            $platforms = $this->Statistics->find()
                ->select([
                    'platform',
                    'clicks' => 'COUNT(platform)',
                ])
                ->where([
                    'link_id' => $link->id,
                    'created BETWEEN :last30 AND :now',
                ])
                ->bind(':last30', $last30, 'datetime')
                ->bind(':now', $now, 'datetime')
                ->order(['clicks' => 'DESC'])
                ->group('platform');

            $this->set('platforms', $platforms);

            $languages = $this->Statistics->find()
                ->select([
                    'language',
                    'clicks' => 'COUNT(language)',
                ])
                ->where([
                    'link_id' => $link->id,
                    'created BETWEEN :last30 AND :now',
                ])
                ->bind(':last30', $last30, 'datetime')
                ->bind(':now', $now, 'datetime')
                ->order(['clicks' => 'DESC'])
                ->group('language');

            $this->set('languages', $languages);

            $devices = $this->Statistics->find()
                ->select([
                    'device_type',
                    'clicks' => 'COUNT(device_type)',
                ])
                ->where([
                    'link_id' => $link->id,
                    'created BETWEEN :last30 AND :now',
                ])
                ->bind(':last30', $last30, 'datetime')
                ->bind(':now', $now, 'datetime')
                ->order(['clicks' => 'DESC'])
                ->group('device_type');

            $this->set('devices', $devices);

            $device_brands = $this->Statistics->find()
                ->select([
                    'device_brand',
                    'clicks' => 'COUNT(device_brand)',
                ])
                ->where([
                    'link_id' => $link->id,
                    'created BETWEEN :last30 AND :now',
                ])
                ->bind(':last30', $last30, 'datetime')
                ->bind(':now', $now, 'datetime')
                ->order(['clicks' => 'DESC'])
                ->group('device_brand');

            $this->set('device_brands', $device_brands);

            $device_names = $this->Statistics->find()
                ->select([
                    'device_name',
                    'clicks' => 'COUNT(device_name)',
                ])
                ->where([
                    'link_id' => $link->id,
                    'created BETWEEN :last30 AND :now',
                ])
                ->bind(':last30', $last30, 'datetime')
                ->bind(':now', $now, 'datetime')
                ->order(['clicks' => 'DESC'])
                ->group('device_name');

            $this->set('device_names', $device_names);

            $facebook_count = Cache::remember($alias . '_facebook_count', function () use ($short_link) {
                return $this->Statistics->facebook_count($short_link);
            }, '1hour');
            $facebook_count = display_price_currency($facebook_count, [
                'places' => 0,
                'before' => '',
                'after' => '',
            ]);
            $this->set('facebook_count', $facebook_count);

            $google_plus_count = Cache::remember($alias . '_google_plus_count', function () use ($short_link) {
                return $this->Statistics->google_plus_count($short_link);
            }, '1hour');
            $google_plus_count = display_price_currency($google_plus_count, [
                'places' => 0,
                'before' => '',
                'after' => '',
            ]);
            $this->set('google_plus_count', $google_plus_count);

            $pinterest_count = Cache::remember($alias . '_pinterest_count', function () use ($short_link) {
                return $this->Statistics->pinterest_count($short_link);
            }, '1hour');
            $pinterest_count = display_price_currency($pinterest_count, [
                'places' => 0,
                'before' => '',
                'after' => '',
            ]);
            $this->set('pinterest_count', $pinterest_count);

            $linkedin_count = Cache::remember($alias . '_linkedin_count', function () use ($short_link) {
                return $this->Statistics->linkedin_count($short_link);
            }, '1hour');
            $linkedin_count = display_price_currency($linkedin_count, [
                'places' => 0,
                'before' => '',
                'after' => '',
            ]);
            $this->set('linkedin_count', $linkedin_count);

            $stumbledupon_count = Cache::remember($alias . '_stumbledupon_count', function () use ($short_link) {
                return $this->Statistics->stumbledupon_count($short_link);
            }, '1hour');
            $stumbledupon_count = display_price_currency($stumbledupon_count, [
                'places' => 0,
                'before' => '',
                'after' => '',
            ]);
            $this->set('stumbledupon_count', $stumbledupon_count);

            $reddit_count = Cache::remember($alias . '_reddit_count', function () use ($short_link) {
                return $this->Statistics->reddit_count($short_link);
            }, '1hour');
            $reddit_count = display_price_currency($reddit_count, [
                'places' => 0,
                'before' => '',
                'after' => '',
            ]);
            $this->set('reddit_count', $reddit_count);
        }
    }
}
