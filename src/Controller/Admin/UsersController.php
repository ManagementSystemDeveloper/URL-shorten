<?php

namespace App\Controller\Admin;

use Cake\Http\Exception\NotFoundException;
use Cake\I18n\Time;
use Cake\Cache\Cache;
use Cake\Mailer\MailerAwareTrait;

/**
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppAdminController
{
    use MailerAwareTrait;

    public function dashboard()
    {
        if (($total_links = Cache::read('total_links', '5min')) === false) {
            $total_links = $this->Users->Links->find()
                ->count();
            Cache::write('total_links', $total_links, '5min');
        }
        $this->set('total_links', $total_links);

        if (($total_clicks = Cache::read('total_clicks', '5min')) === false) {
            $total_clicks = $this->Users->Statistics->find()
                ->count();
            Cache::write('total_clicks', $total_clicks, '5min');
        }
        $this->set('total_clicks', $total_clicks);

        if (($total_users = Cache::read('total_users', '5min')) === false) {
            $total_users = $this->Users->find()
                ->count();
            Cache::write('total_users', $total_users, '5min');
        }
        $this->set('total_users', $total_users);

        if (($total_bundles = Cache::read('total_bundles', '5min')) === false) {
            $total_bundles = $this->Users->Bundles->find()
                ->count();
            Cache::write('total_bundles', $total_bundles, '5min');
        }
        $this->set('total_bundles', $total_bundles);

        ///////////////////////////

        /**
         * @var \App\Model\Entity\Statistic $last_record
         */
        $last_record = $this->Users->Statistics->find()
            ->select('created')
            ->order(['created' => 'DESC'])
            ->first();

        if (!$last_record) {
            $last_record = Time::now();
        } else {
            $last_record = $last_record->created;
        }

        /**
         * @var \App\Model\Entity\Statistic $first_record
         */
        $first_record = $this->Users->Statistics->find()
            ->select('created')
            ->order(['created' => 'ASC'])
            ->first();

        if (!$first_record) {
            $first_record = Time::now()->modify('-1 second');
        } else {
            $first_record = $first_record->created;
        }

        $year_month = [];

        $last_month = Time::now()->year($last_record->year)->month($last_record->month)->startOfMonth();
        $first_month = Time::now()->year($first_record->year)->month($first_record->month)->startOfMonth();

        while ($first_month <= $last_month) {
            $year_month[$last_month->format('Y-m')] = $last_month->i18nFormat('LLLL Y');

            $last_month->modify('-1 month');
        }

        $this->set('year_month', $year_month);

        $to_month = Time::now()->format('Y-m');
        if (array_key_exists($this->request->getQuery('month'), $year_month)) {
            $to_month = explode('-', $this->request->getQuery('month'));
            $year = (int)$to_month[0];
            $month = (int)$to_month[1];
        } else {
            $time = new Time($to_month);
            $current_time = $time->startOfMonth();

            $year = (int)$current_time->format('Y');
            $month = (int)$current_time->format('m');
        }

        $date1 = Time::now()->year($year)->month($month)
            ->startOfMonth()->format('Y-m-d H:i:s');
        $date2 = Time::now()->year($year)->month($month)
            ->endOfMonth()->format('Y-m-d H:i:s');

        if (($views = Cache::read('views_' . $date1 . '_' . $date2, '5min')) === false) {
            $views = $this->Users->Statistics->find()
                ->select([
                    'day' => 'DATE_FORMAT(Statistics.created,"%d-%m-%Y")',
                    'count' => 'COUNT(Statistics.id)',
                ])
                ->where([
                    "Statistics.created BETWEEN :date1 AND :date2",
                ])
                ->order(['Statistics.created' => 'DESC'])
                ->bind(':date1', $date1, 'datetime')
                ->bind(':date2', $date2, 'datetime')
                ->group('day')
                ->toArray();
            Cache::write('views_' . $date1 . '_' . $date2, $views, '5min');
        }
        $this->set('views', $views);

        $CurrentMonthDays = [];

        $targetTime = Time::now();
        $targetTime->year($year)
            ->month($month)
            ->day(1);

        for ($i = 1; $i <= $targetTime->format('t'); $i++) {
            $CurrentMonthDays[$i . "-" . $month . "-" . $year] = [
                'view' => 0,
            ];
        }
        foreach ($views as $view) {
            $day = Time::now()->modify($view->day)->format('j-n-Y');
            $CurrentMonthDays[$day]['view'] = $view->count;
        }
        $this->set('CurrentMonthDays', $CurrentMonthDays);

        if (($newLinks = Cache::read('newLinks_' . $date1 . '_' . $date2, '5min')) === false) {
            $newLinks = $this->Users->Links->find()
                ->contain(['Users'])
                ->select([
                    'Links.alias',
                    'Links.url',
                    'Links.title',
                    'Links.created',
                    'Links.clicks',
                    'Users.id',
                    'Users.username',
                ])
                ->where([
                    "Links.created BETWEEN :date1 AND :date2",
                    "Links.status IN (1, 2)",
                ])
                ->order(['Links.created' => 'DESC'])
                ->bind(':date1', $date1, 'datetime')
                ->bind(':date2', $date2, 'datetime')
                ->limit(10)
                ->toArray();
            Cache::write('newLinks_' . $date1 . '_' . $date2, $newLinks, '5min');
        }
        $this->set('newLinks', $newLinks);

        if (($popularLinks = Cache::read('popularLinks_' . $date1 . '_' . $date2, '5min')) === false) {
            $popularLinks = $this->Users->Links->find()
                ->contain(['Users'])
                ->select([
                    'Links.alias',
                    'Links.url',
                    'Links.title',
                    'Links.created',
                    'Links.clicks',
                    'Users.id',
                    'Users.username',
                ])
                ->where([
                    "Links.created BETWEEN :date1 AND :date2",
                    "Links.status IN (1, 2)",
                ])
                ->order(['Links.clicks' => 'DESC'])
                ->bind(':date1', $date1, 'datetime')
                ->bind(':date2', $date2, 'datetime')
                ->limit(10)
                ->toArray();
            Cache::write('popularLinks_' . $date1 . '_' . $date2, $popularLinks, '5min');
        }
        $this->set('popularLinks', $popularLinks);

        if (($newUsers = Cache::read('newUsers_' . $date1 . '_' . $date2, '5min')) === false) {
            $newUsers = $this->Users->find()
                ->select(['id', 'username', 'created'])
                ->where([
                    "created BETWEEN :date1 AND :date2",
                ])
                ->order(['created' => 'DESC'])
                ->bind(':date1', $date1, 'datetime')
                ->bind(':date2', $date2, 'datetime')
                ->limit(10)
                ->toArray();
            Cache::write('newUsers_' . $date1 . '_' . $date2, $newUsers, '5min');
        }
        $this->set('newUsers', $newUsers);

        if (($popularUsers = Cache::read('popularUsers_' . $date1 . '_' . $date2, '5min')) === false) {
            $popularUsers = $this->Users->find()
                ->select(['id', 'username', 'created'])
                ->where([
                    "created BETWEEN :date1 AND :date2",
                ])
                ->order(['urls' => 'DESC'])
                ->bind(':date1', $date1, 'datetime')
                ->bind(':date2', $date2, 'datetime')
                ->limit(10)
                ->toArray();
            Cache::write('popularUsers_' . $date1 . '_' . $date2, $popularUsers, '5min');
        }
        $this->set('popularUsers', $popularUsers);
    }

    public function index()
    {
        $conditions = [];

        $filter_fields = ['id', 'status', 'username', 'email', 'login_ip', 'register_ip', 'other_fields'];

        //Transform POST into GET
        if ($this->getRequest()->is(['post', 'put']) && $this->getRequest()->getData('Filter')) {
            $filter_url = [];

            $filter_url['controller'] = $this->getRequest()->getParam('controller');

            $filter_url['action'] = $this->getRequest()->getParam('action');

            // We need to overwrite the page every time we change the parameters
            $filter_url['page'] = 1;

            // for each filter we will add a GET parameter for the generated url
            foreach ($this->getRequest()->getData('Filter') as $name => $value) {
                if (in_array($name, $filter_fields) && $value) {
                    // You might want to sanitize the $value here
                    // or even do a urlencode to be sure
                    $filter_url[$name] = urlencode($value);
                }
            }
            // now that we have generated an url with GET parameters,
            // we'll redirect to that page
            return $this->redirect($filter_url);
        } else {
            // Inspect all the named parameters to apply the filters
            foreach ($this->getRequest()->getQuery() as $param_name => $value) {
                $value = urldecode($value);
                if (in_array($param_name, $filter_fields)) {
                    if (in_array($param_name, ['username', 'email'])) {
                        $conditions[] = [
                            ['Users.' . $param_name . ' LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['other_fields'])) {
                        $conditions['OR'] = [
                            ['Users.first_name LIKE' => '%' . $value . '%'],
                            ['Users.last_name LIKE' => '%' . $value . '%'],
                        ];
                    } elseif (in_array($param_name, ['id', 'status', 'login_ip', 'register_ip'])) {
                        if ($param_name == 'status' && !in_array($value, [1, 2, 3])) {
                            continue;
                        }
                        $conditions['Users.' . $param_name] = $value;
                    }
                    $this->setRequest($this->getRequest()->withData('Filter.' . $param_name, $value));
                }
            }
        }

        $query = $this->Users->find()
            ->where($conditions)
            ->where(['Users.username <>' => 'anonymous']);
        $users = $this->paginate($query);
        $this->set('users', $users);
    }

    public function dataExport($id = null)
    {
        $this->getRequest()->allowMethod(['post']);

        $this->autoRender = false;

        if (!$id) {
            throw new NotFoundException(__('Invalid User'));
        }

        /** @var \App\Model\Entity\User $user */
        $user = $this->Users->findById($id)->contain(['Links', 'Plans', 'Invoices'])->first();
        if (!$user) {
            throw new NotFoundException(__('Invalid User'));
        }

        $response = $this->getResponse();

        $response->withType('html');

        $data = $this->processDataExport($user);

        $response->withStringBody($data);
        $response->withDownload('export-' . $id . '-' . date('Y-m-d') . '.html');

        return $response;
    }

    public function view($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid User'));
        }

        $user = $this->Users->findById($id)->first();
        if (!$user) {
            throw new NotFoundException(__('Invalid User'));
        }
        $this->set('user', $user);
    }

    public function add()
    {
        $user = $this->Users->newEntity();

        $plans = $this->Users->Plans
            ->find('list', [
                'keyField' => 'id',
                'valueField' => 'title',
            ])
            ->where(['enable' => 1]);

        $this->set('plans', $plans);

        if ($this->getRequest()->is('post')) {
            $user = $this->Users->patchEntity($user, $this->getRequest()->getData());

            $user->api_token = \Cake\Utility\Security::hash(\Cake\Utility\Text::uuid(), 'sha1', true);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been added.'));

                return $this->redirect(['action' => 'view', $user->id]);
            }
            $this->Flash->error(__('Unable to add the user.'));
        }
        $this->set('user', $user);
    }

    public function message($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid User'));
        }

        $user = $this->Users->findById($id)->first();
        if (!$user) {
            throw new NotFoundException(__('Invalid User'));
        }
        $this->set('user', $user);

        $message = new \App\Form\MessageUserForm();

        if ($this->getRequest()->is('post')) {
            try {
                if ($message->execute($this->getRequest()->getData())) {
                    $this->Flash->success('We will get back to you soon.');

                    return $this->redirect(['action' => 'index']);
                }
            } catch (\Exception $exception) {
                \Cake\Log\Log::write('error', $exception->getMessage());
                $this->Flash->error('There was a problem submitting your form.');
            }
        }
        $this->set('message', $message);
    }

    public function resendActivation($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid User'));
        }

        $user = $this->Users->findById($id)->first();
        if (!$user) {
            throw new NotFoundException(__('Invalid User'));
        }
        $this->set('user', $user);

        $user->activation_key = \Cake\Utility\Security::hash(\Cake\Utility\Text::uuid(), 'sha1', true);

        if ($this->Users->save($user)) {
            try {
                $this->getMailer('User')->send('activation', [$user]);
            } catch (\Exception $exception) {
                \Cake\Log\Log::write('error', $exception->getMessage());
            }
            $this->Flash->success(__('The activation email has been sent, Please ask user to check email ' .
                'inbox or spam folder to activate his account.'));

            return $this->redirect($this->referer());
        }

        $this->Flash->error(__('Unable to add the user.'));

        return $this->redirect($this->referer());
    }

    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid User'));
        }

        $user = $this->Users->findById($id)->where(['Users.username <>' => 'anonymous'])->first();
        if (!$user) {
            throw new NotFoundException(__('Invalid User'));
        }

        $plans = $this->Users->Plans
            ->find('list', [
                'keyField' => 'id',
                'valueField' => 'title',
            ])
            ->where(['enable' => 1]);

        $this->set('plans', $plans);

        if ($this->getRequest()->is(['post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->getRequest()->getData());

            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been updated.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Unable to edit user.'));
        }
        $this->set('user', $user);
    }

    public function deactivate($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $user = $this->Users->findById($id)->where(['Users.username <>' => 'anonymous'])->first();

        $user->status = 3;

        if ($this->Users->save($user)) {
            $this->Flash->success(__('The User with id: {0} has been deactivated.', $user->id));

            return $this->redirect(['action' => 'index']);
        }
    }

    public function delete($id = null)
    {
        $this->getRequest()->allowMethod(['post', 'delete']);

        $user = $this->Users->findById($id)->where(['Users.username <>' => 'anonymous'])->first();

        if ($this->Users->delete($user)) {
            $this->Users->SocialProfiles->deleteAll(['user_id' => $user->id]);
            $this->Users->Bundles->deleteAll(['user_id' => $user->id]);
            $this->Users->Invoices->deleteAll(['user_id' => $user->id]);
            $this->Users->Links->deleteAll(['user_id' => $user->id]);
            $this->Users->Statistics->deleteAll(['user_id' => $user->id]);

            $this->Flash->success(__('The User with id: {0} has been deleted.', $user->id));

            return $this->redirect(['action' => 'index']);
        }
    }

    /**
     * @param \App\Model\Entity\User $user
     * @return string
     */
    protected function processDataExport($user)
    {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
            <style type='text/css'>
                body {
                    color: black;
                    font-family: Arial, sans-serif;
                    font-size: 11pt;
                    margin: 15px auto;
                    width: 860px;
                }

                table {
                    background: #f0f0f0;
                    border: 1px solid #ddd;
                    margin-bottom: 20px;
                    width: 100%;
                }

                th {
                    padding: 5px;
                    text-align: left;
                    width: 20%;
                }

                td {
                    padding: 5px;
                }

                tr:nth-child(odd) {
                    background-color: #fafafa;
                }
            </style>
            <title><?= h(__('Personal Data Export')) ?></title>
        </head>
        <body>

        <h1><?= h(__('Personal Data Export')) ?></h1>

        <h2><?= h(__('About')) ?></h2>
        <div>
            <table>
                <tbody>
                <tr>
                    <th><?= h(__('Report generated for')) ?></th>
                    <td><?= h($user->username) ?> - <?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th><?= h(__('For site')) ?></th>
                    <td><?= h(get_option('site_name')) ?></td>
                </tr>
                <tr>
                    <th><?= h(__('At URL')) ?></th>
                    <td><a href="<?= build_main_domain_url('/') ?>"><?= build_main_domain_url('/') ?></a></td>
                </tr>
                <tr>
                    <th><?= h(__('On')) ?></th>
                    <td><?= date('Y-m-d H:i:s') ?></td>
                </tr>
                </tbody>
            </table>
        </div>

        <h2><?= h(__('User')) ?></h2>
        <div>
            <table>
                <tbody>
                <tr>
                    <th><?= h(__('Id')) ?></th>
                    <td><?= h($user->id) ?></td>
                </tr>
                <tr>
                    <th><?= h(__('Username')) ?></th>
                    <td><?= h($user->username) ?></td>
                </tr>
                <tr>
                    <th><?= h(__('Email')) ?></th>
                    <td><?= h($user->email) ?></td>
                </tr>
                <tr>
                    <th><?= h(__('API Token')) ?></th>
                    <td><?= h($user->api_token) ?></td>
                </tr>
                <tr>
                    <th><?= h(__('First Name')) ?></th>
                    <td><?php pr($user->first_name) ?></td>
                </tr>
                <tr>
                    <th><?= h(__('Last Name')) ?></th>
                    <td><?= h($user->last_name) ?></td>
                </tr>
                <tr>
                    <th><?= h(__('Login IP')) ?></th>
                    <td><?= h($user->login_ip) ?></td>
                </tr>
                <tr>
                    <th><?= h(__('Register IP')) ?></th>
                    <td><?= h($user->register_ip) ?></td>
                </tr>
                </tbody>
            </table>
        </div>

        <h2><?= h(__('Links')) ?></h2>
        <div>
            <table>
                <tbody>
                <?php
                /**
                 * @var \App\Model\Entity\Link $link
                 */
                ?>
                <tr>
                    <th><?= __('Short Link') ?></th>
                    <th><?= __('Long URL') ?></th>
                    <th><?= __('Created') ?></th>
                </tr>
                <?php foreach ($user->links as $link) : ?>
                    <tr>
                        <td><?= get_short_url($link->alias) ?></td>
                        <td><?= h($link->url) ?></td>
                        <td><?= display_date_timezone($link->created) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h2><?= h(__('Invoices')) ?></h2>
        <div>
            <table>
                <tbody>
                <?php
                /**
                 * @var \App\Model\Entity\Invoice $invoice
                 */
                ?>
                <tr>
                    <th><?= __('ID') ?></th>
                    <th><?= __('Status') ?></th>
                    <th><?= __('Description') ?></th>
                    <th><?= __('Amount') ?></th>
                    <th><?= __('Payment Method') ?></th>
                    <th><?= __('Paid Date') ?></th>
                    <th><?= __('Created Date') ?></th>
                </tr>
                <?php foreach ($user->invoices as $invoice) : ?>
                    <tr>
                        <td><?= $invoice->id ?></td>
                        <td><?= h(invoice_statuses($invoice->status)) ?></td>
                        <td><?= h($invoice->description) ?></td>
                        <td><?= display_price_currency($invoice->amount) ?></td>
                        <td><?= h($invoice->payment_method) ?></td>
                        <td><?= display_date_timezone($invoice->paid_date) ?></td>
                        <td><?= display_date_timezone($invoice->created) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        </body>
        </html>
        <?php
        $data = ob_get_contents();
        ob_end_clean();

        return $data;
    }
}
