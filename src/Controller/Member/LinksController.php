<?php

namespace App\Controller\Member;

use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\LinksTable $Links
 * @property \App\Controller\Component\CaptchaComponent $Captcha
 */
class LinksController extends AppMemberController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    public function index()
    {
        $conditions = [];

        $filter_fields = ['alias', 'type', 'title_desc'];

        //Transform POST into GET
        if ($this->getRequest()->is(['post', 'put']) && $this->getRequest()->getData('Filter')) {
            $filter_url = [];

            $filter_url['controller'] = $this->getRequest()->getParam('controller');

            $filter_url['action'] = $this->getRequest()->getParam('action');

            // We need to overwrite the page every time we change the parameters
            $filter_url['page'] = 1;

            // for each filter we will add a GET parameter for the generated url
            foreach ($this->getRequest()->getData('Filter') as $name => $value) {
                if (in_array($name, $filter_fields) && strlen($value) > 0) {
                    // You might want to sanitize the $value here
                    // or even do a urlencode to be sure
                    $filter_url[$name] = urlencode($value);
                }
            }
            // now that we have generated an url with GET parameters,
            // we'll redirect to that page
            return $this->redirect($filter_url);
        } else {
            // Inspect all the named parameters to apply the filters
            foreach ($this->getRequest()->getQuery() as $param_name => $value) {
                if (in_array($param_name, $filter_fields)) {
                    // You may use a switch here to make special filters
                    // like "between dates", "greater than", etc

                    $search_params = ['alias'];

                    if (in_array($param_name, $search_params)) {
                        $conditions[] = [
                            ['Links.' . $param_name . ' LIKE' => '%' . $value . '%'],
                        ];
                    } elseif ($param_name == 'title_desc') {
                        $conditions['OR'] = [
                            ['Links.title LIKE' => '%' . $value . '%'],
                            ['Links.description LIKE' => '%' . $value . '%'],
                            ['Links.url LIKE' => '%' . $value . '%'],
                        ];
                    } else {
                        if (in_array($param_name, ['type'])) {
                            $conditions['Links.' . $param_name] = $value;
                        }
                    }
                    $this->setRequest($this->getRequest()->withData('Filter.' . $param_name, $value));
                }
            }
        }

        $query = $this->Links->find()
            ->where($conditions)
            ->where([
                'user_id' => $this->Auth->user('id'),
                'status' => 1,
            ]);
        $links = $this->paginate($query);

        $this->set('links', $links);
    }

    public function hidden()
    {
        $conditions = [];

        $filter_fields = ['alias', 'type', 'title_desc'];

        //Transform POST into GET
        if ($this->getRequest()->is(['post', 'put']) && $this->getRequest()->getData('Filter')) {
            $filter_url = [];

            $filter_url['controller'] = $this->getRequest()->getParam('controller');

            $filter_url['action'] = $this->getRequest()->getParam('action');

            // We need to overwrite the page every time we change the parameters
            $filter_url['page'] = 1;

            // for each filter we will add a GET parameter for the generated url
            foreach ($this->getRequest()->getData('Filter') as $name => $value) {
                if (in_array($name, $filter_fields) && strlen($value) > 0) {
                    // You might want to sanitize the $value here
                    // or even do a urlencode to be sure
                    $filter_url[$name] = urlencode($value);
                }
            }
            // now that we have generated an url with GET parameters,
            // we'll redirect to that page
            return $this->redirect($filter_url);
        } else {
            // Inspect all the named parameters to apply the filters
            foreach ($this->getRequest()->getQuery() as $param_name => $value) {
                $value = urldecode($value);
                if (in_array($param_name, $filter_fields)) {
                    // You may use a switch here to make special filters
                    // like "between dates", "greater than", etc

                    $search_params = ['alias'];

                    if (in_array($param_name, $search_params)) {
                        $conditions[] = [
                            ['Links.' . $param_name . ' LIKE' => '%' . $value . '%'],
                        ];
                    } elseif ($param_name == 'title_desc') {
                        $conditions['OR'] = [
                            ['Links.title LIKE' => '%' . $value . '%'],
                            ['Links.description LIKE' => '%' . $value . '%'],
                            ['Links.url LIKE' => '%' . $value . '%'],
                        ];
                    } else {
                        if (in_array($param_name, ['type'])) {
                            $conditions['Links.' . $param_name] = $value;
                        }
                    }
                    $this->setRequest($this->getRequest()->withData('Filter.' . $param_name, $value));
                }
            }
        }

        $query = $this->Links->find()
            ->where($conditions)
            ->where([
                'user_id' => $this->Auth->user('id'),
                'status' => 2,
            ]);
        $links = $this->paginate($query);

        $this->set('links', $links);
    }

    public function edit($alias = null)
    {
        if (!$alias) {
            throw new NotFoundException(__('Invalid link'));
        }

        $user = $this->Links->Users->find()->contain('Plans')->where(['Users.id' => $this->Auth->user('id')])->first();

        $user_plan = $this->logged_user_plan;
        if (!$user_plan->edit_link) {
            $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));

            return $this->redirect(['action' => 'index']);
        }

        $edit_long_url = $user_plan->edit_long_url;
        $link_password = $user_plan->password;

        $this->set('edit_long_url', $edit_long_url);
        $this->set('link_password', $link_password);

        $link = $this->Links->find()
            ->contain(['Bundles'])
            ->where([
                'Links.alias' => $alias,
                'Links.user_id' => $this->Auth->user('id'),
                'Links.status <>' => 3,
            ])
            ->first();

        if (!$link) {
            throw new NotFoundException(__('Invalid link'));
        }

        $bundles = $this->Links->Bundles
            ->find('list', [
                'keyField' => 'id',
                'valueField' => 'title',
            ])
            ->where(['user_id' => $this->Auth->user('id')]);

        $this->set('bundles', $bundles);

        if ($this->getRequest()->is(['post', 'put'])) {
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

            $this->setRequest($this->getRequest()->withData('smart', serialize($smart_data)));

            $this->setRequest($this->getRequest()->withData('user_id', $this->Auth->user('id')));

            $link = $this->Links->patchEntity($link, $this->getRequest()->getData());

            if ($this->Links->save($link)) {
                $this->Flash->success(__('Your Link has been updated.'));

                return $this->redirect(['action' => 'edit', $alias]);
            } else {
                //debug( $link->errors() );
                $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
            }
        }
        $this->set('link', $link);
    }

    public function hide($alias)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $link = $this->Links->find()
            ->where([
                'alias' => $alias,
                'user_id' => $this->Auth->user('id'),
                'status' => 1,
            ])
            ->first();

        $link->status = 2;

        if ($this->Links->save($link)) {
            $this->Flash->success(__('The Link with alias: {0} has been hided.', $alias));

            return $this->redirect(['action' => 'index']);
        }
    }

    public function unhide($alias)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $link = $this->Links->find()
            ->where([
                'alias' => $alias,
                'user_id' => $this->Auth->user('id'),
                'status' => 2,
            ])
            ->first();

        $link->status = 1;

        if ($this->Links->save($link)) {
            $this->Flash->success(__('The Link with alias: {0} has been unhided.', $alias));

            return $this->redirect(['action' => 'hidden']);
        }
    }

    public function delete($alias)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        if (!$this->Auth->user('plan.delete_link')) {
            $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));

            return $this->redirect('/member/dashboard');
        }

        $link = $this->Links->find()
            ->where([
                'alias' => $alias,
                'user_id' => $this->Auth->user('id'),
            ])
            ->first();

        if ($this->Links->delete($link)) {
            $this->Links->Statistics->deleteAll(['link_id' => $link->id]);

            $this->Links->BundlesLinks->deleteAll(['link_id' => $link->id]);

            $user = $this->Links->Users->get($link->user_id);
            $user->urls -= 1;
            $user->setDirty('modified', true);
            $this->Links->Users->save($user);

            $this->Flash->success(__('The Link with alias: {0} has been deleted.', $alias));

            return $this->redirect(['action' => 'index']);
        }
    }
}
