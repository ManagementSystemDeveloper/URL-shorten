<?php

namespace App\Controller;

use Cake\I18n\Time;
use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\LinksTable $Links
 * @property \App\Controller\Component\CaptchaComponent $Captcha
 */
class LinksController extends FrontController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setLayout('front');
        $this->Auth->allow(['shorten', 'view']);
    }

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Captcha');
    }

    public function view($alias)
    {
        $this->setResponse(
            $this->getResponse()
                ->withHeader('X-Frame-Options', 'SAMEORIGIN')
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );

        /** @var \App\Model\Entity\Link $link */
        $link = $this->Links->find()
            ->contain(['Users.Plans'])
            ->where([
                'Links.alias' => $alias,
                'Links.status <>' => 3,
                'Users.status' => 1,
            ])
            ->first();

        if (!$link) {
            throw new NotFoundException(__('404 Not Found'));
        }
        $this->set('link', $link);

        if ((bool)get_option('maintenance_mode', false)) {
            return $this->redirect($link->url, 307);
        }

        $device_detector = $this->Links->Statistics->getDeviceDetector();

        if ($device_detector->isBot()) {
            return $this->redirect($link->url, 301);
        }

        $geo = $this->Links->Statistics->get_geo(get_ip());

        $destination_url = $link->url;

        if (is_serialized($link->smart)) {
            $smart = unserialize($link->smart);
            if (!empty($smart)) {
                $country = $geo['country'];
                // $device_detector->getOs('name') OR $device_detector->getOs('short_name')
                $os = $device_detector->getOs('name');
                // $device_detector->getDeviceName()
                $device = $device_detector->getDeviceName();
                // $device_detector->getModel()
                $model = $device_detector->getModel();

                foreach ($smart as $check) {
                    if (in_array($check['country'], ['', $country]) &&
                        in_array($check['os'], ['', $os]) &&
                        in_array($check['device_type'], ['', $device]) &&
                        in_array($check['model'], ['', $model])) {
                        $destination_url = $check['url'];
                        break;
                    }
                }
            }
        }

        $this->set('destination_url', $destination_url);

        $short_link = get_short_url($link->alias, $link->domain);
        $this->set('short_link', $short_link);

        $link_user_plan = get_user_plan($link->user_id);

        $plan_link_password = $link_user_plan->password;
        $plan_disable_ads_area1 = $link_user_plan->disable_ads_area1;
        $plan_disable_ads_area2 = $link_user_plan->disable_ads_area2;
        $plan_timer = $link_user_plan->timer;
        $plan_feed = $link_user_plan->feed;
        $plan_comments = $link_user_plan->comments;

        $this->set('plan_link_password', $plan_link_password);

        if ($this->getRequest()->is('post')) {
            if ($this->getRequest()->getData('password') === $link->password) {
                return $this->redirect($destination_url, 301);
            } else {
                $this->Flash->error(__('Please enter a valid password.'));
            }
        } else {
            $this->updateLinkClicks($link);
            $this->addStatisticEntry($link, $device_detector, $geo);
        }

        $user_redirect = $link->user->redirect_type;
        $link_redirect = $link->type;

        $redirect = $link_redirect;
        if ($link_redirect == 0) {
            $redirect = $user_redirect;
        }

        if (!array_key_exists($redirect, get_allowed_redirects())) {
            $redirect = get_option('member_default_redirect', 1);
        }
        if ($link->user_id == 1) {
            $redirect = get_option('anonymous_default_redirect', 1);
        }

        if ($redirect == 1) {
            return $this->redirect($destination_url, 301);
        }

        $ads_area1 = get_option('ads_area1', '');
        if ($plan_disable_ads_area1) {
            $ads_area1 = '';
        }
        $this->set('ads_area1', $ads_area1);

        $ads_area2 = get_option('ads_area2', '');
        if ($plan_disable_ads_area2) {
            $ads_area2 = '';
        }
        $this->set('ads_area2', $ads_area2);

        $timer = abs((int)$plan_timer);
        $this->set('timer', $timer);

        $feed = $link->user->feed;
        if ($plan_feed == false) {
            $feed = '';
        }
        $this->set('feed', $feed);

        $comments = $link->user->disqus_shortname;
        if ($plan_comments == false) {
            $comments = '';
        }
        $this->set('comments', $comments);
    }

    /**
     * @param \App\Model\Entity\Link $link
     *
     * @return void
     */
    protected function updateLinkClicks($link)
    {
        $link->clicks += 1;
        $link->setDirty('modified', true);
        $this->Links->save($link);
    }

    /**
     * @param \App\Model\Entity\Link $link
     * @param \DeviceDetector\DeviceDetector $device_detector
     * @param array $geo
     * @return void
     */
    protected function addStatisticEntry($link, $device_detector, $geo)
    {
        $referer = strtolower(env('HTTP_REFERER'));

        $languages = $this->getRequest()->acceptLanguage();

        $statistic = $this->Links->Statistics->newEntity();

        $statistic->link_id = $link->id;
        $statistic->user_id = $link->user_id;
        $statistic->ip = get_ip();
        $statistic->continent = $geo['continent'];
        $statistic->country = $geo['country'];
        $statistic->state = $geo['state'];
        $statistic->city = $geo['city'];
        $statistic->location = $geo['location'];
        $statistic->browser = ($device_detector->getClient('name') ?: 'Others');
        $statistic->platform = ($device_detector->getOs('name') ?: 'Others');
        $statistic->device_type = ($device_detector->getDeviceName() ?: 'Others');
        $statistic->device_name = ($device_detector->getModel() ?: 'Others');
        $statistic->device_brand = ($device_detector->getBrand() ?: 'Others');
        $statistic->is_mobile = ($device_detector->isMobile() && !$device_detector->isTablet()) ? 1 : 0;
        $statistic->is_tablet = ($device_detector->isTablet()) ? 1 : 0;
        $statistic->referer_domain = (parse_url($referer, PHP_URL_HOST) ?: 'Direct');
        $statistic->referer = $referer;
        $statistic->user_agent = env('HTTP_USER_AGENT');
        $statistic->language = (isset($languages[0])) ? $languages[0] : 'Others';
        $statistic->timezone = $geo['timezone'];

        $this->Links->Statistics->save($statistic);
    }


    public function shorten()
    {
        $this->autoRender = false;

        $this->setResponse($this->getResponse()->withType('json'));

        if (!$this->getRequest()->is(['ajax'])) {
            $content = [
                'status' => 'error',
                'message' => __('Bad Request.'),
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        $user_id = 1;
        if (null !== $this->Auth->user('id')) {
            $user_id = $this->Auth->user('id');
        }

        if ($user_id === 1 &&
            (bool)get_option('enable_captcha_shortlink_anonymous', false) &&
            isset_captcha() &&
            !$this->Captcha->verify($this->getRequest()->getData())
        ) {
            $content = [
                'status' => 'error',
                'message' => __('The CAPTCHA was incorrect. Try again'),
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        if ($user_id == 1 && get_option('home_shortening_register') === 'yes') {
            $content = [
                'status' => 'error',
                'message' => __('Bad Request.'),
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        $user = $this->Links->Users->find()
            ->contain(['Plans'])
            ->where([
                'Users.id' => $user_id,
                'Users.status' => 1,
            ])
            ->first();

        if (!$user) {
            $content = [
                'status' => 'error',
                'message' => __('Invalid user'),
                'url' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        $url = trim($this->getRequest()->getData('url'));
        $url = str_replace(" ", "%20", $url);
        $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;
        $this->setRequest($this->getRequest()->withData('url', $url));

        $domain = '';
        if ($this->getRequest()->getData('domain')) {
            $domain = $this->getRequest()->getData('domain');
        }
        if (!in_array($domain, get_multi_domains_list())) {
            $domain = '';
        }

        if ($this->getRequest()->getData('type') == 0) {
            $this->setRequest($this->getRequest()->withData('type', $user->redirect_type));
        }

        $linkWhere = [
            'url_hash' => sha1($this->getRequest()->getData('url')),
            'user_id' => $user->id,
            'status' => 1,
            'type' => $this->getRequest()->getData('type'),
            'url' => $this->getRequest()->getData('url'),

        ];

        if ($this->getRequest()->getData('alias') && strlen($this->getRequest()->getData('alias')) > 0) {
            $linkWhere['alias'] = $this->getRequest()->getData('alias');
        }

        $link = $this->Links->find()->where($linkWhere)->first();

        if ($link) {
            $content = [
                'status' => 'success',
                'message' => '',
                'url' => get_short_url($link->alias, $domain),
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        $user_plan = get_user_plan($user->id);

        if ($user_plan->url_daily_limit) {
            $start = Time::now()->startOfDay()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfDay()->format('Y-m-d H:i:s');

            $links_daily_count = $this->Links->find()
                ->where([
                    "created BETWEEN :date1 AND :date2",
                    'user_id' => $user_id,
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_daily_count >= $user_plan->url_daily_limit) {
                $content = [
                    'status' => 'error',
                    'message' => __('Your account has exceeded its daily short links limit.'),
                    'url' => '',
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->getResponse();
            }
        }

        if ($user_plan->url_monthly_limit) {
            $start = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

            $links_monthly_count = $this->Links->find()
                ->where([
                    "created BETWEEN :date1 AND :date2",
                    'user_id' => $user_id,
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_monthly_count >= $user_plan->url_monthly_limit) {
                $content = [
                    'status' => 'error',
                    'message' => __('Your account has exceeded its monthly short links limit.'),
                    'url' => '',
                ];
                $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

                return $this->getResponse();
            }
        }

        $smart_data = [];
        if ($this->getRequest()->getData('smart')) {
            foreach ($this->getRequest()->getData('smart') as $key => $condition) {
                if (!in_array($condition['country'], array_keys(get_countries(true)))) {
                    continue;
                }
                if (!in_array($condition['os'], array_keys(get_operating_systems(true)))) {
                    continue;
                }
                if (!in_array($condition['device_type'], array_keys(get_device_types(true)))) {
                    continue;
                }
                if (!in_array($condition['model'], array_keys(get_models(true)))) {
                    continue;
                }
                if (empty($condition['url'])) {
                    continue;
                }
                $smart_data[] = [
                    'country' => $condition['country'],
                    'device_type' => $condition['device_type'],
                    'os' => $condition['os'],
                    'model' => $condition['model'],
                    'url' => $condition['url'],
                ];
            }
        }

        $link = $this->Links->newEntity();
        $data = [];

        $data['user_id'] = $user->id;
        $data['url'] = $this->getRequest()->getData('url');
        $data['url_hash'] = sha1($this->getRequest()->getData('url'));

        $data['domain'] = $domain;

        if (!empty($this->getRequest()->getData('alias'))) {
            $data['alias'] = $this->getRequest()->getData('alias');
        } else {
            $data['alias'] = $this->Links->geturl();
        }

        if ($this->getRequest()->getData('bundles')) {
            $data['bundles'] = $this->getRequest()->getData('bundles', []);
        }

        $data['type'] = $this->getRequest()->getData('type');

        $link->smart = serialize($smart_data);

        if ($this->getRequest()->getData('password')) {
            $link->password = $this->getRequest()->getData('password');
        }

        $link->status = 1;
        $link->ip = get_ip();

        $linkMeta = [
            'title' => '',
            'description' => '',
            'image' => '',
        ];

        if ($user_id === 1 && get_option('disable_meta_home') === 'no') {
            $linkMeta = $this->Links->getLinkMeta($this->getRequest()->getData('url'));
        }

        if ($user_id !== 1 && get_option('disable_meta_member') === 'no') {
            $linkMeta = $this->Links->getLinkMeta($this->getRequest()->getData('url'));
        }

        $data['title'] = $linkMeta['title'];
        $data['description'] = $linkMeta['description'];
        $link->image = $linkMeta['image'];

        $link = $this->Links->patchEntity($link, $data);
        if ($this->Links->save($link)) {
            $user->urls += 1;
            $user->setDirty('modified', true);
            $this->Links->Users->save($user);

            $content = [
                'status' => 'success',
                'message' => '',
                'url' => get_short_url($link->alias, $domain),
            ];
            $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

            return $this->getResponse();
        }

        $message = __('Invalid URL.');
        if ($link->getErrors()) {
            $error_msg = [];
            foreach ($link->getErrors() as $errors) {
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        $error_msg[] = $error;
                    }
                } else {
                    $error_msg[] = $errors;
                }
            }

            if (!empty($error_msg)) {
                $message = implode("<br>", $error_msg);
            }
        }

        $content = [
            'status' => 'error',
            'message' => $message,
            'url' => '',
        ];
        $this->setResponse($this->getResponse()->withStringBody(json_encode($content)));

        return $this->getResponse();
    }
}
