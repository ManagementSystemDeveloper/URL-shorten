<?php

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\I18n\Time;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @property \App\Controller\Component\CaptchaComponent $Captcha
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 * @property \App\Model\Table\UsersTable $Users
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     * @throws \Exception
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler', [
            'enableBeforeRedirect' => false,
        ]);
        $this->loadComponent('Security');
        //$this->loadComponent('Csrf');
        $this->loadComponent('Flash');
        $this->loadComponent('Auth', [
            'loginAction' => [
                'plugin' => false,
                'controller' => 'Users',
                'action' => 'signin',
                'prefix' => 'auth',
            ],
            'authenticate' => [
                'Form' => [
                    'finder' => 'auth',
                ],
            ],
            'authorize' => 'Controller',
            'loginRedirect' => [
                'plugin' => false,
                'controller' => 'Users',
                'action' => 'dashboard',
                'prefix' => 'member',
            ],
            'logoutRedirect' => [
                'plugin' => false,
                'controller' => 'Users',
                'action' => 'signin',
                'prefix' => 'auth',
            ],
            'authError' => '',
        ]);
        $this->loadComponent('Paginator');

        $this->_AuthenticateCookieUser();
    }

    /**
     * @return bool|null
     * @throws \Exception
     * @link https://stackoverflow.com/a/30135526/1794834
     */
    protected function _AuthenticateCookieUser()
    {
        if (!is_app_installed()) {
            return null;
        }

        if ($this->getResponse()->getStatusCode() !== 200) {
            return null;
        }

        if (in_array($this->getRequest()->getParam('action'), ['multidomainsAuth', 'authDone'])) {
            return null;
        }

        if ($this->Auth->user('id')) {
            return null;
        }

        if (!isset($_COOKIE['RememberMe']) || strpos($_COOKIE['RememberMe'], ":") === false) {
            return null;
        }

        list($selector, $authenticator) = explode(':', $_COOKIE['RememberMe']);

        $this->loadModel('Users');

        /**
         * @var \App\Model\Entity\RememberToken $rememberToken
         */
        $rememberToken = $this->Users->RememberTokens->find()
            ->where([
                'selector' => $selector,
            ])
            ->first();

        if (!$rememberToken) {
            unset($_COOKIE['RememberMe']);
            setcookie('RememberMe', null, -1, '/');

            return null;
        }

        if (!hash_equals($rememberToken->token, hash('sha256', base64_decode($authenticator)))) {
            unset($_COOKIE['RememberMe']);
            setcookie('RememberMe', null, -1, '/');

            return null;
        }

        if ($rememberToken->expires->isPast()) {
            unset($_COOKIE['RememberMe']);
            setcookie('RememberMe', null, -1, '/');

            return null;
        }

        /**
         * @var \App\Model\Entity\User $user
         */
        $user = $this->Users->find()
            ->contain(['Plans'])
            ->where([
                'Users.id' => $rememberToken->user_id,
                'Users.status' => 1,
            ])
            ->first();

        if (!$user) {
            unset($_COOKIE['RememberMe']);
            setcookie('RememberMe', null, -1, '/');

            return null;
        }

        unset($user->password);
        $this->Auth->setUser($user->toArray());

        $selector = base64_encode(random_bytes(9));
        $authenticator = random_bytes(33);
        $expire = Time::now()->addYear();

        setcookie(
            'RememberMe',
            $selector . ':' . base64_encode($authenticator),
            $expire->timestamp,
            '/',
            '',
            false, // TLS-only
            true // http-only
        );

        $rememberToken->selector = $selector;
        $rememberToken->token = hash('sha256', $authenticator);
        $rememberToken->user_id = $user['id'];
        $rememberToken->expires = $expire->toDateTimeString();
        $this->Users->RememberTokens->save($rememberToken);

        $multi_domains = get_all_domains_list();
        unset($multi_domains[$_SERVER['HTTP_HOST']]);
        if (count($multi_domains)) {
            $_SESSION['Auth']['AppAuth']['Domains'] = $multi_domains;
            $_SESSION['Auth']['AppAuth']['DomainsData'] = urlencode(data_encrypt([
                'session_name' => session_name(),
                'session_id' => session_id(),
                'time' => time(),
            ]));
        }

        $_SESSION['Auth']['AppAuth']['Cookie'] = urlencode(data_encrypt([
            'name' => 'RememberMe',
            'value' => $selector . ':' . base64_encode($authenticator),
            'expire' => $expire->timestamp,
        ]));

        return true;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        // Set the frontend layout
        $this->viewBuilder()->setLayout('front');
    }

    /**
     * Before render callback.
     *
     * @param \Cake\Event\Event $event The beforeRender event.
     * @return \Cake\Http\Response|null|void
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
    }
}
