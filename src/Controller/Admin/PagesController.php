<?php

namespace App\Controller\Admin;

use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\PagesTable $Pages
 */
class PagesController extends AppAdminController
{
    public function index()
    {
        $query = $this->Pages->find();
        $pages = $this->paginate($query);

        $this->set('pages', $pages);
    }

    public function add()
    {
        $page = $this->Pages->newEntity();

        if ($this->request->is('post')) {
            if (!empty($this->getRequest()->getData('slug'))) {
                $this->setRequest($this->getRequest()->withData(
                    'slug',
                    $this->Pages->createSlug($this->getRequest()->getData('slug'))
                ));
            } else {
                $this->setRequest($this->getRequest()->withData(
                    'slug',
                    $this->Pages->createSlug($this->getRequest()->getData('title'))
                ));
            }

            $page = $this->Pages->patchEntity($page, $this->request->getData());

            if ($this->Pages->save($page)) {
                $this->Flash->success(__('Page has been added.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
        }
        $this->set('page', $page);
    }

    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid Page'));
        }

        if (isset(get_site_languages()[$this->getRequest()->getQuery('lang')])) {
            $this->Pages->setLocale($this->getRequest()->getQuery('lang'));
        }

        $page = $this->Pages->get($id);
        if (!$page) {
            throw new NotFoundException(__('Invalid Page'));
        }

        if ($this->request->is(['post', 'put'])) {
            if (!empty($this->getRequest()->getData('slug'))) {
                $this->setRequest($this->getRequest()->withData(
                    'slug',
                    $this->Pages->createSlug($this->getRequest()->getData('slug'), $id)
                ));
            } else {
                $this->setRequest($this->getRequest()->withData(
                    'slug',
                    $this->Pages->createSlug($this->getRequest()->getData('title'), $id)
                ));
            }

            $page = $this->Pages->patchEntity($page, $this->request->getData());

            if ($this->Pages->save($page)) {
                $this->Flash->success(__('Page has been updated.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
        }
        $this->set('page', $page);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $page = $this->Pages->findById($id)->first();

        if ($this->Pages->delete($page)) {
            $this->Flash->success(__('The page with id: {0} has been deleted.', $page->id));

            return $this->redirect(['action' => 'index']);
        }
    }
}
