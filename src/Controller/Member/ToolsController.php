<?php

namespace App\Controller\Member;

use Cake\I18n\Time;

/**
 * @property \App\Model\Table\LinksTable $Links
 * @property \Cake\ORM\Table $Tools
 */
class ToolsController extends AppMemberController
{
    public function quick()
    {
        if (!$this->logged_user_plan->api_quick) {
            $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));

            return $this->redirect(['_name' => 'member_dashboard']);
        }
    }

    public function massShrinker()
    {
        if (!$this->logged_user_plan->api_mass) {
            $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));

            return $this->redirect(['_name' => 'member_dashboard']);
        }

        $this->loadModel('Links');

        $link = $this->Links->newEntity();
        if ($this->getRequest()->is('post')) {
            $urls = explode("\n", str_replace("\r", "\n", $this->getRequest()->getData('urls')));
            $urls = array_unique(array_filter($urls));
            $urls = array_slice($urls, 0, get_option('mass_shrinker_limit', 20));
            $urls = array_map('trim', $urls);

            $type = get_option('member_default_redirect', 1);
            if (array_key_exists($this->getRequest()->getQuery('type'), get_allowed_redirects())) {
                $type = $this->getRequest()->getQuery('type');
            }

            $results = [];
            foreach ($urls as $url) {
                $results[] = $this->addMassShrinker($url, $type);
            }

            $this->set('results', $results);
        }
        $this->set('link', $link);
    }

    protected function addMassShrinker($url, $type = 1)
    {
        $this->loadModel('Links');

        $result = ['url' => '', 'short' => ''];

        $url = parse_url($url, PHP_URL_SCHEME) === null ? 'http://' . $url : $url;

        $link = $this->Links->find()
            ->where([
                'url_hash' => sha1($url),
                'user_id' => $this->Auth->user('id'),
                'status' => 1,
                'url' => $url,
            ])
            ->first();

        if ($link) {
            return ['url' => $url, 'short' => $link->alias];
        }

        $user = $this->Users->find()->contain('Plans')->where(['Users.id' => $this->Auth->user('id')])->first();

        $user_plan = get_user_plan($user->id);

        if ($user_plan->url_daily_limit) {
            $start = Time::now()->startOfDay()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfDay()->format('Y-m-d H:i:s');

            $links_daily_count = $this->Links->find()
                ->where([
                    "created BETWEEN :date1 AND :date2",
                    'user_id' => $this->Auth->user('id'),
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_daily_count >= $user_plan->url_daily_limit) {
                return [
                    'url' => $url,
                    'short' => 'error',
                    'domain' => '',
                    'message' => __('Your account has exceeded its daily short links limit.'),
                ];
            }
        }

        if ($user_plan->url_monthly_limit) {
            $start = Time::now()->startOfMonth()->format('Y-m-d H:i:s');
            $end = Time::now()->endOfMonth()->format('Y-m-d H:i:s');

            $links_monthly_count = $this->Links->find()
                ->where([
                    "created BETWEEN :date1 AND :date2",
                    'user_id' => $this->Auth->user('id'),
                ])
                ->bind(':date1', $start, 'datetime')
                ->bind(':date2', $end, 'datetime')
                ->count();

            if ($links_monthly_count >= $user_plan->url_monthly_limit) {
                return [
                    'url' => $url,
                    'short' => 'error',
                    'domain' => '',
                    'message' => __('Your account has exceeded its monthly short links limit.'),
                ];
            }
        }

        $link = $this->Links->newEntity();
        $data = [];

        $data['user_id'] = $this->Auth->user('id');
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
            $this->Users->save($user);

            return ['url' => $url, 'short' => $link->alias, 'domain' => $link->domain];
        }

        return [
            'url' => $url,
            'short' => 'error',
            'domain' => '',
            'message' => __('It is not a valid URL.'),
        ];
    }

    public function api()
    {
        if (!$this->logged_user_plan->api_developer) {
            $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));

            return $this->redirect(['_name' => 'member_dashboard']);
        }
    }

    public function full()
    {
        if (!$this->logged_user_plan->api_full) {
            $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));

            return $this->redirect(['_name' => 'member_dashboard']);
        }
    }

    public function bookmarklet()
    {
        if (!$this->logged_user_plan->bookmarklet) {
            $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));

            return $this->redirect(['_name' => 'member_dashboard']);
        }
    }
}
