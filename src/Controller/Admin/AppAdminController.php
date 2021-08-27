<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * @property \Cake\ORM\Table $AppAdmin
 */
class AppAdminController extends AppController
{
    public $paginate = [
        'limit' => 10,
        'order' => ['id' => 'DESC'],
    ];

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->viewBuilder()->setLayout('admin');

        if ($this->redirect_for_database_upgrade()) {
            return $this->redirect(['controller' => 'Upgrade', 'action' => 'index'], 307);
        }

        if ($this->redirect_for_license_activate()) {
            return $this->redirect(['controller' => 'Activation', 'action' => 'index'], 307);
        }
    }

    public function isAuthorized($user = null)
    {
        // Admin can access every action
        if (version_compare(get_option('app_version', '1.0.0'), '2.0.0', '<')) {
            $user['role'] = 2;
            if ($user['account_type'] == 'Admin') {
                $user['role'] = 1;
            }
        }
        if ($user['role'] == 1) {
            return true;
        }
        // Default deny
        return false;
    }

    protected function redirect_for_database_upgrade()
    {
        if (require_database_upgrade() && $this->request->getParam('controller') !== 'Upgrade') {
            return true;
        }

        return false;
    }

    protected function redirect_for_license_activate()
    {
        if (require_database_upgrade()) {
            return false;
        }

        $Activation = TableRegistry::getTableLocator()->get('Activation');
        if ($Activation->checkLicense() === false && $this->request->getParam('controller') !== 'Activation') {
            return true;
        }

        return false;
    }
}
