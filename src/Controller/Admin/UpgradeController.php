<?php

namespace App\Controller\Admin;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Filesystem\File;
use Migrations\Migrations;

/**
 * @property \Cake\ORM\Table $Upgrade
 */
class UpgradeController extends AppAdminController
{
    public function index()
    {
        if ($this->request->is('post')) {
            @ini_set('memory_limit', '1024M');
            @set_time_limit(10 * MINUTE);
            @ini_set('max_execution_time', 10 * MINUTE);

            try {
                $migrations = new Migrations();
                $result = $migrations->migrate();
            } catch (\Exception $ex) {
                $result = __('Can not able to run upgrade. Error: ') . $ex->getMessage();
            }

            if ($result !== true) {
                $this->Flash->error($result);
            } else {
                $Options = TableRegistry::getTableLocator()->get('Options');

                /** @var \App\Model\Entity\Option $app_version */
                $app_version = $Options->findByName('app_version')->first();

                if (version_compare($app_version, '2.0.0', '<')) {
                    Configure::write('Mighty.installed', 1);
                    Configure::dump('app_vars', 'default', ['Mighty']);

                    $file = new File(CONFIG . 'configure.php');
                    $content = $file->read();
                    $content = str_replace('__SALT__', generate_random_string(50), $content);
                    $file->write($content);
                }

                $app_version->value = APP_VERSION;
                $Options->save($app_version);

                emptyCache();
                createEmailFile();

                $this->Flash->success(__('Database upgraded successfully.'));

                return $this->redirect($this->Auth->logout());
            }
        }
    }
}
