<?php

namespace App\Controller;

use Cake\Event\Event;

/**
 * @property \Cake\ORM\Table $Front
 */
class FrontController extends AppController
{
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->setTheme(get_option('theme', 'ClassicTheme'));
    }
}
