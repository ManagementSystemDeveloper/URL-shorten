<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;
use Cake\Cache\Cache;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\PagesTable $Pages
 */
class PagesController extends FrontController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['home', 'view']);
    }

    public function home()
    {
        if ((bool)get_option('private_service', 0)) {
            $this->render('private', 'blank');
            return null;
        }

        $this->loadModel('Users');

        /*
          $todayClicks = $this->Users->Statistics->find()
          ->where([
          'DATE(Statistics.created) = CURDATE()'
          ])
          ->count();
          $this->set('todayClicks', $todayClicks);
         */

        $lang = locale_get_default();

        if (($totalLinks = Cache::read('home_totalLinks_' . $lang, '1hour')) === false) {
            $totalLinks = $this->Users->Links->find()
                ->where(['id >= 1'])
                ->count();

            $totalLinks += (int)get_option('fake_links', 0);

            $totalLinks = display_price_currency($totalLinks, [
                'places' => 0,
                'before' => '',
                'after' => '',
            ]);

            Cache::write('home_totalLinks_' . $lang, $totalLinks, '1hour');
        }
        $this->set('totalLinks', $totalLinks);

        if (($totalClicks = Cache::read('home_totalClicks_' . $lang, '1hour')) === false) {
            $totalClicks = $this->Users->Statistics->find()
                ->where([
                    'id >=' => 1
                ])
                ->count();

            $totalClicks += (int)get_option('fake_clicks', 0);

            $totalClicks = display_price_currency($totalClicks, [
                'places' => 0,
                'before' => '',
                'after' => '',
            ]);

            Cache::write('home_totalClicks_' . $lang, $totalClicks, '1hour');
        }
        $this->set('totalClicks', $totalClicks);

        if (($totalUsers = Cache::read('home_totalUsers_' . $lang, '1hour')) === false) {
            $totalUsers = $this->Users->find()
                ->where(['id >= 1'])
                ->count();

            $totalUsers += (int)get_option('fake_users', 0);

            $totalUsers = display_price_currency($totalUsers, [
                'places' => 0,
                'before' => '',
                'after' => '',
            ]);

            Cache::write('home_totalUsers_' . $lang, $totalUsers, '1hour');
        }
        $this->set('totalUsers', $totalUsers);
    }

    public function view($slug = null)
    {
        if (!$slug) {
            throw new NotFoundException(__('Invalid Page.'));
        }

        $page = $this->Pages->find()->where(['slug' => $slug, 'published' => 1])->first();

        if (!$page) {
            throw new NotFoundException(__('Invalid Page.'));
        }

        $this->set('page', $page);
    }
}
