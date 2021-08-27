<?php

namespace App\Controller\Admin;

use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\BundlesTable $Bundles
 */
class BundlesController extends AppAdminController
{
    public function index()
    {
        $query = $this->Bundles->find()->contain([
            'Users' => function ($q) {
                return $q
                    ->select(['username']);
            }
        ]);
        $bundles = $this->paginate($query);

        $this->set('bundles', $bundles);
    }

    public function add()
    {
        $bundle = $this->Bundles->newEntity();

        if ($this->request->is('post')) {
            $bundle = $this->Bundles->patchEntity($bundle, $this->getRequest()->getData());

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

        $bundle = $this->Bundles->get($id);
        if (!$bundle) {
            throw new NotFoundException(__('Invalid Bundle'));
        }

        if ($this->request->is(['post', 'put'])) {
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

        $bundle = $this->Bundles->findById($id)->first();

        if ($this->Bundles->delete($bundle)) {
            $this->Bundles->BundlesLinks->deleteAll(['bundle_id' => $id]);

            $this->Flash->success(__('The bundle with id: {0} has been deleted.', $bundle->id));

            return $this->redirect(['action' => 'index']);
        }
    }
}
