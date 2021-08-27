<?php

namespace App\Controller\Admin;

use Cake\ORM\TableRegistry;

/**
 * @property \App\Model\Table\OptionsTable $Options
 */
class OptionsController extends AppAdminController
{
    public function index()
    {
        $plans = TableRegistry::getTableLocator()->get('Plans')
            ->find('list', [
                'keyField' => 'id',
                'valueField' => 'title',
            ])
            ->where(['enable' => 1])
            ->toArray();

        $this->set('plans', $plans);

        if ($this->saveOptions()) {
            emptyCache();

            $this->Flash->success(__('Settings have been saved.'));

            return $this->redirect(['action' => 'index']);
        }
    }

    public function email()
    {
        if ($this->saveOptions()) {
            createEmailFile();

            $this->Flash->success(__('Email settings have been saved.'));

            return $this->redirect(['action' => 'email']);
        }
    }

    public function socialLogin()
    {
        if ($this->saveOptions()) {
            $this->Flash->success(__('Social login settings have been saved.'));

            return $this->redirect(['action' => 'socialLogin']);
        }
    }

    public function payment()
    {
        if ($this->saveOptions()) {
            $this->Flash->success(__('Payment settings have been saved.'));

            return $this->redirect(['action' => 'payment']);
        }
    }

    protected function saveOptions()
    {
        $options = $this->Options->find()->all();

        $settings = [];
        foreach ($options as $option) {
            $settings[$option->name] = [
                'id' => $option->id,
                'value' => $option->value,
            ];
        }

        if ($this->request->is(['post', 'put'])) {
            foreach ($this->getRequest()->getData('Options') as $key => $optionData) {
                if (is_array($optionData['value'])) {
                    $optionData['value'] = serialize($optionData['value']);
                }
                $option = $this->Options->newEntity();
                $option->id = $key;
                $option = $this->Options->patchEntity($option, $optionData);
                $this->Options->save($option);
            }

            return true;
        }

        $this->set('options', $options);
        $this->set('settings', $settings);
    }
}
