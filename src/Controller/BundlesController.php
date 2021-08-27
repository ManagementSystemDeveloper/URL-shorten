<?php

namespace App\Controller;

use Cake\Event\Event;
use Cake\Http\Exception\NotFoundException;

/**
 * @property \App\Model\Table\BundlesTable $Bundles
 */
class BundlesController extends FrontController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->setLayout('front');
        $this->Auth->allow(['view']);
    }

    public function view($username = null, $slug = null)
    {
        if (!$username || !$slug) {
            throw new NotFoundException(__('404 Not Found'));
        }

        $user = $this->Bundles->Users->find()
            ->contain(['Plans'])
            ->where([
                'Users.username' => $username,
                'Users.status' => 1,
            ])
            ->first();

        if (!$user) {
            throw new NotFoundException(__('404 Not Found'));
        }
        $this->set('user', $user);

        $user_plan = get_user_plan($user->id);

        $plan_disable_ads_area1 = $user_plan->disable_ads_area1;
        $plan_disable_ads_area2 = $user_plan->disable_ads_area2;

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

        $bundle = $this->Bundles->find()
            ->contain([
                'Links' => function (\Cake\ORM\Query $q) {
                    return $q->where(['Links.status IN' => [1, 2]])
                        ->orderDesc('Links.id');
                },
            ])
            ->where([
                'Bundles.user_id' => $user->id,
                'Bundles.slug' => $slug,
            ])
            ->first();

        if (!$bundle) {
            throw new NotFoundException(__('404 Not Found'));
        }
        $this->set('bundle', $bundle);

        if ($this->Auth->user('role') !== 1) {
            if (($bundle->private && ($this->Auth->user('id') !== $user->id))) {
                throw new NotFoundException(__('404 Not Found'));
            }
        }

        $bundle->views += 1;
        $bundle->setDirty('modified', true);
        $this->Bundles->save($bundle);

        $this->set('links', $bundle->links);
    }
}
