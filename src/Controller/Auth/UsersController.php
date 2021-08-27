<?php

namespace App\Controller\Auth;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\Mailer\MailerAwareTrait;
use Cake\Utility\Security;
use Cake\Utility\Text;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    use MailerAwareTrait;

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Captcha');

        if (in_array($this->getRequest()->getParam('action'), ['multidomainsAuth', 'authDone'])) {
            //$this->getEventManager()->off($this->Csrf);
            $this->getEventManager()->off($this->Security);
        }
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['multidomainsAuth', 'authDone', 'signup', 'logout', 'activateAccount', 'forgotPassword']);
        $this->viewBuilder()->setLayout('auth');
    }

    public function signin()
    {
        if ($this->Auth->user('id')) {
            return $this->redirect('/');
        }

        $user = $this->Users->newEntity();
        $this->set('user', $user);

        if ($this->getRequest()->is('post')) {
            if ((get_option('enable_captcha_signin', 'no') == 'yes') &&
                isset_captcha() &&
                !$this->Captcha->verify($this->getRequest()->getData())
            ) {
                $this->Flash->error(__('The CAPTCHA was incorrect. Try again'));

                return null;
            }
        }

        if ($this->getRequest()->is('post') || $this->getRequest()->getQuery('provider')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

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

                $this->_setUserCookie($user);

                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }

    /**
     * @param array $user
     * @return bool
     * @throws \Exception
     */
    protected function _setUserCookie($user)
    {
        if (!$this->request->getData('remember_me')) {
            return false;
        }

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

        $rememberToken = $this->Users->RememberTokens->newEntity();
        $rememberToken->selector = $selector;
        $rememberToken->token = hash('sha256', $authenticator);
        $rememberToken->user_id = $user['id'];
        $rememberToken->expires = $expire->toDateTimeString();
        $this->Users->RememberTokens->save($rememberToken);

        $_SESSION['Auth']['AppAuth']['Cookie'] = urlencode(data_encrypt([
            'name' => 'RememberMe',
            'value' => $selector . ':' . base64_encode($authenticator),
            'expire' => $expire->timestamp,
        ]));

        return true;
    }

    public function multidomainsAuth()
    {
        $this->autoRender = false;

        $response = $this->getResponse();
        $response = $response->withType('gif');
        $response = $response->withStringBody(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=='));

        $this->setResponse($response);

        if (!$this->getRequest()->is('get')) {
            return $this->getResponse();
        }

        try {
            if ($this->getRequest()->getQuery('auth')) {
                $auth = data_decrypt($this->getRequest()->getQuery('auth'));

                if ((time() - $auth['time']) > 60) {
                    return $this->getResponse();
                }

                session_write_close();

                session_name($auth['session_name']);
                session_id($auth['session_id']);

                session_start();

                if ($this->getRequest()->getQuery('cookie')) {
                    $cookie = data_decrypt($this->getRequest()->getQuery('cookie'));

                    if ($cookie) {
                        setcookie(
                            $cookie['name'],
                            $cookie['value'],
                            $cookie['expire'],
                            '/',
                            '',
                            false, // TLS-only
                            true // http-only
                        );
                    }
                }
            }
        } catch (\Exception $ex) {
        }

        return $this->getResponse();
    }

    public function authDone()
    {
        $this->autoRender = false;

        $response = $this->getResponse();
        $response = $response->withType('gif');
        $response = $response->withStringBody(base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=='));

        $this->setResponse($response);

        if (!$this->getRequest()->is('get')) {
            return $this->getResponse();
        }

        try {
            $this->getRequest()->getSession()->delete('Auth.AppAuth');
            //unset($_SESSION['Auth']['AppAuth']);
        } catch (\Exception $ex) {
        }

        return $this->getResponse();
    }

    public function signup()
    {
        if ($this->Auth->user('id')) {
            return $this->redirect('/');
        }

        if ((bool)get_option('close_registration', false) ||
            (bool)get_option('private_service', false)) {
            return $this->redirect('/');
        }

        $user = $this->Users->newEntity();

        $this->set('user', $user);

        if ($this->request->is('post')) {
            if ((get_option('enable_captcha_signup') == 'yes') &&
                isset_captcha() &&
                !$this->Captcha->verify($this->getRequest()->getData())
            ) {
                $this->Flash->error(__('The CAPTCHA was incorrect. Try again'));

                return null;
            }

            $user = $this->Users->patchEntity($user, $this->getRequest()->getData());

            $user->api_token = Security::hash(Text::uuid(), 'sha1', true);
            $user->activation_key = Security::hash(Text::uuid(), 'sha1', true);

            $user->role = 2;
            $user->status = 1;
            $user->plan_id = 1;
            $user->first_name = $this->request->getData('username');
            $user->register_ip = get_ip();
            $user->redirect_type = get_option('member_default_redirect', 1);

            $trial_plan = (int)get_option('trial_plan', '');
            if ($trial_plan > 1) {
                $plan_expiration = Time::now();

                if (get_option('trial_plan_period', 'm') === 'm') {
                    $expiration = $plan_expiration->addMonth();
                } else {
                    $expiration = $plan_expiration->addYear();
                }
                $user->plan_id = $trial_plan;
                $user->expiration = $expiration;
            }

            if (get_option('account_activate_email', 'yes') == 'yes') {
                $user->status = 2;
            }

            if ($this->Users->save($user)) {
                if (get_option('account_activate_email', 'yes') == 'yes') {
                    // Send activation email
                    try {
                        $this->getMailer('User')->send('activation', [$user]);
                    } catch (\Exception $exception) {
                        \Cake\Log\Log::write('error', $exception->getMessage());
                    }

                    $this->Flash->success(__("Your account has been created. " .
                        "Please check your email inbox or spam folder to activate your account."));

                    return $this->redirect(['action' => 'signin']);
                }
                $this->Flash->success(__('Your account has been created.'));

                return $this->redirect(['action' => 'signin']);
            }
            $this->Flash->error(__('Unable to add the user.'));
        }
        $this->set('user', $user);
    }

    public function logout()
    {
        if (isset($_COOKIE['RememberMe']) && strpos($_COOKIE['RememberMe'], ":") !== false) {
            list($selector, $authenticator) = explode(':', $_COOKIE['RememberMe']);

            $rememberToken = $this->Users->RememberTokens->find()
                ->where([
                    'selector' => $selector,
                ])
                ->limit(1)
                ->first();

            if ($rememberToken) {
                $this->Users->RememberTokens->delete($rememberToken);
            }

            unset($_COOKIE['RememberMe']);
            setcookie('RememberMe', null, -1, '/');
        }

        return $this->redirect($this->Auth->logout());
    }

    public function activateAccount($username = null, $key = null)
    {
        if (!$username && !$key) {
            $this->Flash->error(__('Invalid Activation.'));

            return $this->redirect(['action' => 'signin']);
        }
        $user = $this->Users->find()
            ->contain(['Plans'])
            ->where([
                'Users.username' => $username,
                'Users.status' => 2,
                'Users.activation_key' => $key,
            ])
            ->first();

        if (!$user) {
            $this->Flash->error(__('Invalid Activation.'));

            return $this->redirect(['action' => 'signin']);
        }

        $user->status = 1;
        $user->activation_key = '';

        if ($this->Users->save($user)) {
            $this->Flash->success(__('Your account has been activated.'));
            $this->Auth->setUser($user->toArray());

            return $this->redirect(['controller' => 'users', 'action' => 'dashboard', 'prefix' => 'member']);
        } else {
            $this->Flash->error(__('Unable to activate your account.'));

            return $this->redirect(['action' => 'signin', 'prefix' => 'auth']);
        }
    }

    public function forgotPassword($username = null, $key = null)
    {
        if ($this->Auth->user('id')) {
            return $this->redirect('/');
        }

        if (!$username && !$key) {
            $user = $this->Users->newEntity();
            $this->set('user', $user);

            if ($this->request->is(['post', 'put'])) {
                if ((get_option('enable_captcha_forgot_password') == 'yes') &&
                    isset_captcha() &&
                    !$this->Captcha->verify($this->request->data)
                ) {
                    $this->Flash->error(__('The CAPTCHA was incorrect. Try again'));

                    return null;
                }

                $user = $this->Users->findByEmail($this->request->data['email'])->first();

                if (!$user) {
                    $this->Flash->error(__('Invalid User.'));

                    return $this->redirect(['action' => 'forgotPassword', 'prefix' => 'auth']);
                }

                $user->activation_key = Security::hash(Text::uuid(), 'sha1', true);

                $user = $this->Users->patchEntity($user, $this->request->data, ['validate' => 'forgotPassword']);

                if ($this->Users->save($user)) {
                    // Send rest email
                    try {
                        $this->getMailer('User')->send('forgotPassword', [$user]);
                    } catch (\Exception $exception) {
                        \Cake\Log\Log::write('error', $exception->getMessage());
                    }

                    $this->Flash->success(__('Kindly check your email for reset password link.'));

                    return $this->redirect(['action' => 'forgotPassword', 'prefix' => 'auth']);
                } else {
                    $this->Flash->error(__('Unable to reset password.'));

                    return $this->redirect(['action' => 'forgotPassword', 'prefix' => 'auth']);
                }
            }
        } else {
            $user = $this->Users->find('all')
                ->where([
                    'username' => $username,
                    'status' => 1,
                    'activation_key' => $key,
                ])
                ->first();
            if (!$user) {
                $this->Flash->error(__('Invalid Request.'));

                return $this->redirect(['action' => 'forgotPassword', 'prefix' => 'auth']);
            }

            if ($this->request->is(['post', 'put'])) {
                $user->activation_key = '';

                $user = $this->Users->patchEntity($user, $this->request->data, ['validate' => 'forgotPassword']);

                if ($this->Users->save($user)) {
                    $this->Flash->success(__('Your password has been changed.'));

                    return $this->redirect(['action' => 'signin', 'prefix' => 'auth']);
                } else {
                    $this->Flash->error(__('Unable to change your password.'));
                }
            }

            unset($user->password);

            $this->set('user', $user);
        }
    }
}
