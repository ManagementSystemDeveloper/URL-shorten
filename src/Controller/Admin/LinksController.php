<?php

namespace App\Controller\Admin;

use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\LinksTable $Links
 * @property \App\Controller\Component\CaptchaComponent $Captcha
 */
class LinksController extends AppAdminController
{
    public function index()
    {
        $conditions = [];

        $filter_fields = ['user_id', 'alias', 'type', 'title_desc'];

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
                    if (in_array($param_name, ['alias'])) {
                        $conditions[] = [
                            ['Links.' . $param_name . ' LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['title_desc'])) {
                        $conditions['OR'] = [
                            ['Links.title LIKE' => '%' . $value . '%'],
                            ['Links.description LIKE' => '%' . $value . '%'],
                            ['Links.url LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['user_id', 'type'])) {
                        $conditions['Links.' . $param_name] = $value;
                    }
                    $this->setRequest($this->getRequest()->withData('Filter.' . $param_name, $value));
                }
            }
        }

        $query = $this->Links->find()
            ->contain(['Users'])
            ->where($conditions)
            ->where(['Links.status' => 1]);
        $links = $this->paginate($query);

        $this->set('links', $links);
    }

    public function hidden()
    {
        $conditions = [];

        $filter_fields = ['user_id', 'alias', 'type', 'title_desc'];

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
                    if (in_array($param_name, ['alias'])) {
                        $conditions[] = [
                            ['Links.' . $param_name . ' LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['title_desc'])) {
                        $conditions['OR'] = [
                            ['Links.title LIKE' => '%' . $value . '%'],
                            ['Links.description LIKE' => '%' . $value . '%'],
                            ['Links.url LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['user_id', 'type'])) {
                        $conditions['Links.' . $param_name] = $value;
                    }
                    $this->setRequest($this->getRequest()->withData('Filter.' . $param_name, $value));
                }
            }
        }

        $query = $this->Links->find()
            ->contain(['Users'])
            ->where($conditions)
            ->where(['Links.status' => 2]);
        $links = $this->paginate($query);

        $this->set('links', $links);
    }

    public function inactive()
    {
        $conditions = [];

        $filter_fields = ['user_id', 'alias', 'type', 'title_desc'];

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
                    if (in_array($param_name, ['alias'])) {
                        $conditions[] = [
                            ['Links.' . $param_name . ' LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['title_desc'])) {
                        $conditions['OR'] = [
                            ['Links.title LIKE' => '%' . $value . '%'],
                            ['Links.description LIKE' => '%' . $value . '%'],
                            ['Links.url LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['user_id', 'type'])) {
                        $conditions['Links.' . $param_name] = $value;
                    }
                    $this->setRequest($this->getRequest()->withData('Filter.' . $param_name, $value));
                }
            }
        }

        $query = $this->Links->find()
            ->contain(['Users'])
            ->where($conditions)
            ->where(['Links.status' => 3]);
        $links = $this->paginate($query);

        $this->set('links', $links);
    }

    public function edit($alias = null)
    {
        if (!$alias) {
            throw new NotFoundException(__('Invalid link'));
        }

        /** @var \App\Model\Entity\Link $link */
        $link = $this->Links->findByAlias($alias)
            ->contain(['Bundles'])
            ->first();

        if (!$link) {
            throw new NotFoundException(__('Invalid link'));
        }

        /** @var \App\Model\Entity\Bundle $bundles */
        $bundles = $this->Links->Bundles
            ->find('list', [
                'keyField' => 'id',
                'valueField' => 'title',
            ])
            ->where(['user_id' => $link->user_id]);

        $this->set('bundles', $bundles);

        if ($this->getRequest()->is(['post', 'put'])) {
            $this->setRequest($this->getRequest()->withData('user_id', $link->user_id));
            $link = $this->Links->patchEntity($link, $this->getRequest()->getData());
            if ($this->Links->save($link)) {
                $this->Flash->success(__('The Link has been updated.'));

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

        $link = $this->Links->findByAlias($alias)->first();

        $link->status = 2;

        if ($this->Links->save($link)) {
            $this->Flash->success(__('The Link with alias: {0} has been hided.', $alias));

            return $this->redirect(['action' => 'index']);
        }
    }

    public function unhide($alias)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $link = $this->Links->findByAlias($alias)->first();

        $link->status = 1;

        if ($this->Links->save($link)) {
            $this->Flash->success(__('The Link with alias: {0} has been unhided.', $alias));

            return $this->redirect(['action' => 'hidden']);
        }
    }

    public function deactivate($alias)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $link = $this->Links->findByAlias($alias)->first();

        $link->status = 3;

        if ($this->Links->save($link)) {
            $this->Flash->success(__('The Link with alias: {0} has been unhided.', $alias));

            return $this->redirect(['action' => 'hidden']);
        }
    }

    public function delete($alias)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $link = $this->Links->findByAlias($alias)->first();

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
