<?php

namespace App\Controller\Member;

use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\BundlesTable $Bundles
 */
class BundlesController extends AppMemberController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        if (!$this->logged_user_plan->bundle) {
            $this->Flash->error(__('You must upgrade your plan so you can use this tool.'));

            return $this->redirect('/member/dashboard');
        }
    }

    public function index()
    {
        $query = $this->Bundles->find()
            ->contain([
                'Users' => function (\Cake\ORM\Query $q) {
                    return $q
                        ->select(['Users.username']);
                },
            ])
            ->where([
                'Bundles.user_id' => $this->Auth->user('id'),
            ]);
        $bundles = $this->paginate($query);

        $this->set('bundles', $bundles);
    }

    public function add()
    {
        $bundle = $this->Bundles->newEntity();

        if ($this->request->is('post')) {
            if (!empty($this->getRequest()->getData('slug'))) {
                $this->setRequest($this->getRequest()->withData(
                    'slug',
                    $this->Bundles->createSlug($this->getRequest()->getData('slug'), null, $this->Auth->user('id'))
                ));
            } else {
                $this->setRequest($this->getRequest()->withData(
                    'slug',
                    $this->Bundles->createSlug($this->getRequest()->getData('title'), null, $this->Auth->user('id'))
                ));
            }

            $bundle = $this->Bundles->patchEntity($bundle, $this->getRequest()->getData());

            $bundle->user_id = $this->Auth->user('id');

            if ($this->Bundles->save($bundle)) {
                $this->Flash->success(__('Bundle has been added.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
        }
        $this->set('bundle', $bundle);
    }

    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid Bundle'));
        }

        $bundle = $this->Bundles->find()
            ->where([
                'id' => $id,
                'user_id' => $this->Auth->user('id'),
            ])
            ->first();

        if (!$bundle) {
            throw new NotFoundException(__('Invalid Bundle'));
        }

        if ($this->request->is(['post', 'put'])) {
            if (!empty($this->getRequest()->getData('slug'))) {
                $this->setRequest($this->getRequest()->withData(
                    'slug',
                    $this->Bundles->createSlug($this->getRequest()->getData('slug'), $id, $this->Auth->user('id'))
                ));
            } else {
                $this->setRequest($this->getRequest()->withData(
                    'slug',
                    $this->Bundles->createSlug($this->getRequest()->getData('title'), $id, $this->Auth->user('id'))
                ));
            }

            $bundle = $this->Bundles->patchEntity($bundle, $this->getRequest()->getData());

            if ($this->Bundles->save($bundle)) {
                $this->Flash->success(__('Bundle has been updated.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
        }
        $this->set('bundle', $bundle);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $bundle = $this->Bundles->find()
            ->where([
                'id' => $id,
                'user_id' => $this->Auth->user('id'),
            ])
            ->first();

        if ($this->Bundles->delete($bundle)) {
            $this->Bundles->BundlesLinks->deleteAll(['bundle_id' => $id]);

            $this->Flash->success(__('The bundle with id: {0} has been deleted.', $bundle->id));

            return $this->redirect(['action' => 'index']);
        }
    }
}
