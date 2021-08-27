<?php

namespace App\Controller;

use Cake\I18n\Time;
use Cake\Event\Event;

/**
 * @property \App\Model\Table\LinksTable $Links
 * @property \Cake\ORM\Table $Tools
 */
class ToolsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['st', 'full', 'api', 'bookmarklet']);
    }

    public function bookmarklet()
    {
        $this->setResponse(
            $this->getResponse()->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );

        $this->viewBuilder()->setLayout('blank');

        $this->loadModel('Links');

        $valid_bookmarklet = false;

        if ($this->getRequest()->getQuery('api') &&
            $this->getRequest()->getQuery('url')
        ) {
            $valid_bookmarklet = true;
        }

        $this->set('valid_bookmarklet', $valid_bookmarklet);

        if (!$valid_bookmarklet) {
            $this->Flash->error(__('Bad Request.'));

            return null;
        }

        $api = $this->getRequest()->getQuery('api');

        /**
         * @var \App\Model\Entity\User $user
         */
        $user = $this->Links->Users->find()
            ->contain('Plans')
            ->where([
                'Users.api_token' => $api,
                'Users.status' => 1,
            ])
            ->first();

        if (!$user) {
            $this->Flash->error(__('Invalid API token.'));
            $valid_bookmarklet = false;
            $this->set('valid_bookmarklet', $valid_bookmarklet);

            return null;
        }

        $custom_alias = true;
        if (!get_user_plan($user->id)->alias) {
            $custom_alias = false;
        }
        $this->set('custom_alias', $custom_alias);

        $link = $this->Links->newEntity();

        if (!$this->getRequest()->is(['post'])) {
            $url = $this->getRequest()->getData('url');

            $type = get_option('member_default_redirect', 1);
            if (array_key_exists($this->getRequest()->getData('type'), get_allowed_redirects())) {
                $type = $this->getRequest()->getData('type');
            }

            if (!get_user_plan($user->id)->bookmarklet) {
                $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));
                $this->set('link', $link);

                return null;
            }

            $url = trim($url);
            $url = str_replace(" ", "%20", $url);
            $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;

            $domain = '';
            if ($this->getRequest()->getData('domain')) {
                $domain = $this->getRequest()->getData('domain');
            }
            if (!in_array($domain, get_multi_domains_list())) {
                $domain = '';
            }

            $linkWhere = [
                'url_hash' => sha1($url),
                'user_id' => $user->id,
                'status' => 1,
                'type' => $type,
                'url' => $url,
            ];

            if ($this->getRequest()->getQuery('alias') && strlen($this->getRequest()->getQuery('alias')) > 0) {
                $linkWhere['alias'] = $this->getRequest()->getData('alias');
            }

            $link = $this->Links->find()->where($linkWhere)->first();

            if ($link) {
                $this->set('short_link', get_short_url($link->alias, $domain));

                return null;
            }

            $user_plan = get_user_plan($user->id);

            if ($user_plan->url_daily_limit) {
                $start = Time::now()->startOfDay()->format('Y-m-d H:i:s');
                $end = Time::now()->endOfDay()->format('Y-m-d H:i:s');

                $links_daily_count = $this->Links->find()
                    ->where([
                        "created BETWEEN :date1 AND :date2",
                        'user_id' => $user->id,
                    ])
                    ->bind(':date1', $start, 'datetime')
                    ->bind(':date2', $end, 'datetime')
                    ->count();

                if ($links_daily_count >= $user_plan->url_daily_limit) {
                    $this->Flash->error(__('Your account has exceeded its daily short links limit.'));
                    $valid_bookmarklet = false;
                    $this->set('valid_bookmarklet', $valid_bookmarklet);

                    return null;
                }
            }

            if ($user_plan->url_monthly_limit) {
                $start = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
                $end = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

                $links_monthly_count = $this->Links->find()
                    ->where([
                        "created BETWEEN :date1 AND :date2",
                        'user_id' => $user->id,
                    ])
                    ->bind(':date1', $start, 'datetime')
                    ->bind(':date2', $end, 'datetime')
                    ->count();

                if ($links_monthly_count >= $user_plan->url_monthly_limit) {
                    $this->Flash->error(__('Your account has exceeded its monthly short links limit.'));
                    $valid_bookmarklet = false;
                    $this->set('valid_bookmarklet', $valid_bookmarklet);

                    return null;
                }
            }

            $link = $this->Links->newEntity();
            $data = [];

            $data['user_id'] = $user->id;
            $data['url'] = $url;
            $data['url_hash'] = sha1($url);
            $data['domain'] = $domain;
            if (empty($this->getRequest()->getData('alias'))) {
                $data['alias'] = $this->Links->geturl();
            } else {
                $data['alias'] = $this->getRequest()->getData('alias');
            }
            $data['type'] = $type;

            $link->status = 1;
            $link->clicks = 0;
            $link->method = 6;

            $linkMeta = [
                'title' => '',
                'description' => '',
                'image' => '',
            ];

            if (get_option('disable_meta_api') === 'no') {
                $linkMeta = $this->Links->getLinkMeta($url);
            }

            $data['title'] = $linkMeta['title'];
            $data['description'] = $linkMeta['description'];
            $link->image = $linkMeta['image'];

            $link = $this->Links->patchEntity($link, $data);
            if ($this->Links->save($link)) {
                $user->urls += 1;
                $user->setDirty('modified', true);
                $this->Links->Users->save($user);

                $this->set('short_link', get_short_url($link->alias, $domain));

                return null;
            } else {
                $this->Flash->error(__('Check the below errors.'));
            }
        }
        $this->set('link', $link);
    }

    public function st()
    {
        $this->setResponse(
            $this->getResponse()->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );

        $this->viewBuilder()->setLayout('blank');

        $this->loadModel('Links');

        $message = '';
        $this->set('message', $message);

        if (!$this->getRequest()->is(['post'])) {
            return null;
        }

        if (!$this->getRequest()->getData('api') ||
            !$this->getRequest()->getData('url')
        ) {
            $message = __('Invalid Request.');
            $this->set('message', $message);

            return null;
        }

        $api = $this->getRequest()->getData('api');
        $url = $this->getRequest()->getData('url');

        $type = get_option('member_default_redirect', 1);

        /**
         * @var \App\Model\Entity\User $user
         */

        $user = $this->Links->Users->find()
            ->contain('Plans')
            ->where([
                'Users.api_token' => $api,
                'Users.status' => 1,
            ])
            ->first();

        if (!$user) {
            $message = __('Invalid API token.');
            $this->set('message', $message);

            return null;
        }

        if (!get_user_plan($user->id)->api_quick) {
            $message = __('You must upgrade your plan so you can use this tool.');
            $this->set('message', $message);

            return null;
        }

        $url = trim($url);
        $url = str_replace(" ", "%20", $url);
        $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;

        $link = $this->Links->find()
            ->where([
                'url_hash' => sha1($url),
                'user_id' => $user->id,
                'status' => 1,
                'type' => $type,
                'url' => $url,
            ])
            ->first();

        if ($link) {
            return $this->redirect(get_short_url($link->alias), 301);
        }

        $user_plan = get_user_plan($user->id);

        if ($user_plan->url_daily_limit) {
            $start = Time::now()->startOfDay()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfDay()->format('Y-m-d H:i:s');

            $links_daily_count = $this->Links->find()
                ->where([
                    "created BETWEEN :date1 AND :date2",
                    'user_id' => $user->id,
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_daily_count >= $user_plan->url_daily_limit) {
                $message = __('Your account has exceeded its daily short links limit.');
                $this->set('message', $message);

                return null;
            }
        }

        if ($user_plan->url_monthly_limit) {
            $start = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

            $links_monthly_count = $this->Links->find()
                ->where([
                    "created BETWEEN :date1 AND :date2",
                    'user_id' => $user->id,
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_monthly_count >= $user_plan->url_monthly_limit) {
                $message = __('Your account has exceeded its monthly short links limit.');
                $this->set('message', $message);

                return null;
            }
        }

        $link = $this->Links->newEntity();
        $data = [];

        $data['user_id'] = $user->id;
        $data['url'] = $url;
        $data['url'] = sha1($url);
        $data['alias'] = $this->Links->geturl();
        $data['type'] = $type;

        $link->status = 1;
        $link->clicks = 0;
        $link->method = 2;

        $linkMeta = [
            'title' => '',
            'description' => '',
            'image' => '',
        ];

        if (get_option('disable_meta_api') === 'no') {
            $linkMeta = $this->Links->getLinkMeta($url);
        }

        $data['title'] = $linkMeta['title'];
        $data['description'] = $linkMeta['description'];
        $link->image = $linkMeta['image'];

        $link = $this->Links->patchEntity($link, $data);
        if ($this->Links->save($link)) {
            $user->urls += 1;
            $user->setDirty('modified', true);
            $this->Links->Users->save($user);

            return $this->redirect(get_short_url($link->alias), 301);
        }

        $error_msg = [];
        if ($link->hasErrors()) {
            foreach ($link->getErrors() as $errors) {
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        $error_msg[] = h($error);
                    }
                } else {
                    $error_msg[] = h($errors);
                }
            }
        }
        $this->set('message', implode('<br>', $error_msg));

        return null;
    }

    public function full()
    {
        $this->setResponse(
            $this->getResponse()->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );

        $this->viewBuilder()->setLayout('blank');

        $this->loadModel('Links');

        $message = '';
        $this->set('message', $message);

        if (!$this->getRequest()->getQuery('api') ||
            !$this->getRequest()->getQuery('url')
        ) {
            $message = __('Invalid Request.');
            $this->set('message', $message);

            return null;
        }

        $api = $this->getRequest()->getQuery('api');
        $url = urldecode(base64_decode($this->getRequest()->getQuery('url')));

        $type = get_option('member_default_redirect', 1);
        if (array_key_exists($this->getRequest()->getQuery('type'), get_allowed_redirects())) {
            $type = $this->getRequest()->getQuery('type');
        }

        /**
         * @var \App\Model\Entity\User $user
         */
        $user = $this->Links->Users->find()
            ->contain('Plans')
            ->where([
                'Users.api_token' => $api,
                'Users.status' => 1,
            ])
            ->first();

        if (!$user) {
            $message = __('Invalid API token.');
            $this->set('message', $message);

            return null;
        }

        if (!get_user_plan($user->id)->api_full) {
            $message = __('You must upgrade your plan so you can use this tool.');
            $this->set('message', $message);

            return null;
        }

        $url = trim($url);
        $url = str_replace(" ", "%20", $url);
        $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;

        $linkWhere = [
            'url_hash' => sha1($url),
            'user_id' => $user->id,
            'status' => 1,
            'type' => $type,
            'url' => $url,
        ];

        $link = $this->Links->find()->where($linkWhere)->first();

        if ($link) {
            return $this->redirect(get_short_url($link->alias), 301);
        }

        $user_plan = get_user_plan($user->id);

        if ($user_plan->url_daily_limit) {
            $start = Time::now()->startOfDay()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfDay()->format('Y-m-d H:i:s');

            $links_daily_count = $this->Links->find()
                ->where([
                    "created BETWEEN :date1 AND :date2",
                    'user_id' => $user->id,
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_daily_count >= $user_plan->url_daily_limit) {
                $message = __('Your account has exceeded its daily short links limit.');
                $this->set('message', $message);

                return null;
            }
        }

        if ($user_plan->url_monthly_limit) {
            $start = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

            $links_monthly_count = $this->Links->find()
                ->where([
                    "created BETWEEN :date1 AND :date2",
                    'user_id' => $user->id,
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_monthly_count >= $user_plan->url_monthly_limit) {
                $message = __('Your account has exceeded its monthly short links limit.');
                $this->set('message', $message);

                return null;
            }
        }

        $link = $this->Links->newEntity();
        $data = [];

        $data['user_id'] = $user->id;
        $data['url'] = $url;
        $data['url_hash'] = sha1($url);
        $data['alias'] = $this->Links->geturl();
        $data['type'] = $type;

        $link->status = 1;
        $link->clicks = 0;
        $link->method = 4;

        $linkMeta = [
            'title' => '',
            'description' => '',
            'image' => '',
        ];

        if (get_option('disable_meta_api') === 'no') {
            $linkMeta = $this->Links->getLinkMeta($url);
        }

        $data['title'] = $linkMeta['title'];
        $data['description'] = $linkMeta['description'];
        $link->image = $linkMeta['image'];

        $link = $this->Links->patchEntity($link, $data);
        if ($this->Links->save($link)) {
            $user->urls += 1;
            $user->setDirty('modified', true);
            $this->Links->Users->save($user);

            return $this->redirect(get_short_url($link->alias), 301);
        }

        $error_msg = [];
        if ($link->hasErrors()) {
            foreach ($link->getErrors() as $errors) {
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        $error_msg[] = h($error);
                    }
                } else {
                    $error_msg[] = h($errors);
                }
            }
        }
        $this->set('message', implode('<br>', $error_msg));

        return null;
    }

    public function api()
    {
        $this->autoRender = false;

        $this->setResponse(
            $this->getResponse()
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('X-Robots-Tag', 'noindex, nofollow')
        );

        $this->loadModel('Links');

        $format = 'json';
        if ($this->getRequest()->getQuery('format') && strtolower($this->getRequest()->getQuery('format')) === 'text') {
            $format = 'text';
        }
        $this->setResponse($this->getResponse()->withType($format));

        if (!$this->getRequest()->getQuery('api') ||
            !$this->getRequest()->getQuery('url')
        ) {
            $content = [
                'status' => 'error',
                'message' => 'Invalid API call',
                'shortenedUrl' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

            return $this->response;
        }

        $api = $this->getRequest()->getQuery('api');
        $url = urldecode($this->getRequest()->getQuery('url'));

        $type = get_option('member_default_redirect', 1);
        if (array_key_exists($this->getRequest()->getQuery('type'), get_allowed_redirects())) {
            $type = $this->getRequest()->getQuery('type');
        }

        /**
         * @var \App\Model\Entity\User $user
         */
        $user = $this->Links->Users->find()
            ->contain('Plans')
            ->where([
                'Users.api_token' => $api,
                'Users.status' => 1,
            ])
            ->first();

        if (!$user) {
            $content = [
                'status' => 'error',
                'message' => 'Invalid API token',
                'shortenedUrl' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

            return $this->getResponse();
        }

        if (!get_user_plan($user->id)->api_developer) {
            $content = [
                'status' => 'error',
                'message' => 'You must upgrade your plan so you can use this tool.',
                'shortenedUrl' => '',
            ];
            $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

            return $this->getResponse();
        }

        $url = trim($url);
        $url = str_replace(" ", "%20", $url);
        $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;

        $linkWhere = [
            'url_hash' => sha1($url),
            'user_id' => $user->id,
            'status' => 1,
            'type' => $type,
            'url' => $url,
        ];

        if ($this->getRequest()->getQuery('alias') && strlen($this->getRequest()->getQuery('alias')) > 0) {
            $linkWhere['alias'] = $this->getRequest()->getQuery('alias');
        }

        $link = $this->Links->find()->where($linkWhere)->first();

        if ($link) {
            $content = [
                'status' => 'success',
                'shortenedUrl' => get_short_url($link->alias, $link->domain),
            ];
            $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

            return $this->getResponse();
        }

        $user_plan = get_user_plan($user->id);

        if ($user_plan->url_daily_limit) {
            $start = Time::now()->startOfDay()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfDay()->format('Y-m-d H:i:s');

            $links_daily_count = $this->Links->find()
                ->where([
                    "created BETWEEN :date1 AND :date2",
                    'user_id' => $user->id,
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_daily_count >= $user_plan->url_daily_limit) {
                $content = [
                    'status' => 'error',
                    'message' => 'Your account has exceeded its daily short links limit.',
                    'shortenedUrl' => '',
                ];
                $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

                return $this->getResponse();
            }
        }

        if ($user_plan->url_monthly_limit) {
            $start = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

            $links_monthly_count = $this->Links->find()
                ->where([
                    "created BETWEEN :date1 AND :date2",
                    'user_id' => $user->id,
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_monthly_count >= $user_plan->url_monthly_limit) {
                $content = [
                    'status' => 'error',
                    'message' => 'Your account has exceeded its monthly short links limit.',
                    'shortenedUrl' => '',
                ];
                $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

                return $this->getResponse();
            }
        }

        $link = $this->Links->newEntity();
        $data = [];

        $data['user_id'] = $user->id;
        $data['url'] = $url;
        $data['url_hash'] = sha1($url);
        if (!empty($this->getRequest()->getQuery('alias'))) {
            $data['alias'] = $this->getRequest()->getQuery('alias');
        } else {
            $data['alias'] = $this->Links->geturl();
        }
        $data['type'] = $type;

        $link->status = 1;
        $link->clicks = 0;
        $link->method = 5;

        $linkMeta = [
            'title' => '',
            'description' => '',
            'image' => '',
        ];

        if (get_option('disable_meta_api') === 'no') {
            $linkMeta = $this->Links->getLinkMeta($url);
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
                'shortenedUrl' => get_short_url($link->alias, $link->domain),
            ];
            $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

            return $this->getResponse();
        }

        $error_msg = [];
        if ($link->hasErrors()) {
            foreach ($link->getErrors() as $errors) {
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        $error_msg[] = $error;
                    }
                } else {
                    $error_msg[] = $errors;
                }
            }
        }

        $content = [
            'status' => 'error',
            'message' => $error_msg,
            'shortenedUrl' => '',
        ];
        $this->setResponse($this->getResponse()->withStringBody($this->apiContent($content, $format)));

        return $this->getResponse();
    }

    protected function apiContent($content = [], $format = 'json')
    {
        $body = json_encode($content);
        if ($format === 'text') {
            $body = $content['shortenedUrl'];
        }

        return $body;
    }
}
