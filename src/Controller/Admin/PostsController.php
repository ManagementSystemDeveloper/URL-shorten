<?php

namespace App\Controller\Admin;

use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\PostsTable $Posts
 */
class PostsController extends AppAdminController
{
    public function index()
    {
        $query = $this->Posts->find();
        $posts = $this->paginate($query);

        $this->set('posts', $posts);
    }

    public function add()
    {
        $post = $this->Posts->newEntity();

        if ($this->request->is('post')) {
            if (!empty($this->getRequest()->getData('slug'))) {
                $this->setRequest($this->getRequest()->withData(
                    'slug',
                    $this->Posts->createSlug($this->getRequest()->getData('slug'))
                ));
            } else {
                $this->setRequest($this->getRequest()->withData(
                    'slug',
                    $this->Posts->createSlug($this->getRequest()->getData('title'))
                ));
            }

            $post = $this->Posts->patchEntity($post, $this->request->getData());

            if ($this->Posts->save($post)) {
                $this->Flash->success(__('Post has been added.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
        }
        $this->set('post', $post);
    }

    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid Post'));
        }

        if (isset(get_site_languages()[$this->getRequest()->getQuery('lang')])) {
            $this->Posts->setLocale($this->getRequest()->getQuery('lang'));
        }

        $post = $this->Posts->get($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid Post'));
        }

        if ($this->request->is(['post', 'put'])) {
            if (!empty($this->getRequest()->getData('slug'))) {
                $this->setRequest($this->getRequest()->withData(
                    'slug',
                    $this->Posts->createSlug($this->getRequest()->getData('slug'), $id)
                ));
            } else {
                $this->setRequest($this->getRequest()->withData(
                    'slug',
                    $this->Posts->createSlug($this->getRequest()->getData('title'), $id)
                ));
            }

            $post = $this->Posts->patchEntity($post, $this->request->getData());

            if ($this->Posts->save($post)) {
                $this->Flash->success(__('Post has been updated.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
        }
        $this->set('post', $post);
    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $post = $this->Posts->findById($id)->first();

        if ($this->Posts->delete($post)) {
            $this->Flash->success(__('The post with id: {0} has been deleted.', $post->id));

            return $this->redirect(['action' => 'index']);
        }
    }
}
