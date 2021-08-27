<?php

namespace App\Controller\Member;

use Cake\Mailer\MailerAwareTrait;
use Cake\I18n\Time;
use Cake\Http\Exception\NotFoundException;
use Cake\Cache\Cache;

/**
 * @property \App\Model\Table\UsersTable $Users
 * @property \App\Model\Table\AnnouncementsTable $Announcements
 */
class UsersController extends AppMemberController
{
    use MailerAwareTrait;

    public function dashboard()
    {
        if (($total_links = Cache::read('total_links_' . $this->Auth->user('id'), '15min')) === false) {
            $total_links = $this->Users->Links->find()
                ->where([
                    'Links.user_id' => $this->Auth->user('id'),
                ])
                ->count();
            Cache::write('total_links_' . $this->Auth->user('id'), $total_links, '15min');
        }
        $this->set('total_links', $total_links);

        if (($total_clicks = Cache::read('total_clicks_' . $this->Auth->user('id'), '15min')) === false) {
            $total_clicks = $this->Users->Statistics->find()
                ->where([
                    'Statistics.user_id' => $this->Auth->user('id'),
                ])
                ->count();
            Cache::write('total_clicks_' . $this->Auth->user('id'), $total_clicks, '5min');
        }
        $this->set('total_clicks', $total_clicks);

        if (($total_bundles = Cache::read('total_bundles_' . $this->Auth->user('id'), '15min')) === false) {
            $total_bundles = $this->Users->Bundles->find()
                ->where([
                    'Bundles.user_id' => $this->Auth->user('id'),
                ])
                ->count();
            Cache::write('total_bundles_' . $this->Auth->user('id'), $total_bundles, '15min');
        }
        $this->set('total_bundles', $total_bundles);

        ///////////////////////////

        /**
         * @var \App\Model\Entity\Statistic $last_record
         */
        $last_record = $this->Users->Statistics->find()
            ->select('created')
            ->where(['user_id' => $this->Auth->user('id')])
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
            ->where(['user_id' => $this->Auth->user('id')])
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

        $date1 = Time::now()->year($year)->month($month)->startOfMonth()->format('Y-m-d H:i:s');
        $date2 = Time::now()->year($year)->month($month)->endOfMonth()->format('Y-m-d H:i:s');

        $views = Cache::read('views_publisher_' . $this->Auth->user('id') . '_' . $date1 . '_' . $date2, '15min');
        if ($views === false) {
            $views = $this->Users->Statistics->find()
                ->select([
                    'day' => 'DATE_FORMAT(Statistics.created,"%d-%m-%Y")',
                    'count' => 'COUNT(Statistics.id)',
                ])
                ->where([
                    "Statistics.created BETWEEN :date1 AND :date2",
                    'Statistics.user_id' => $this->Auth->user('id'),
                ])
                ->order(['Statistics.id' => 'DESC'])
                ->bind(':date1', $date1, 'datetime')
                ->bind(':date2', $date2, 'datetime')
                ->group('day')
                ->toArray();
            Cache::write('views_publisher_' . $this->Auth->user('id') . '_' . $date1 . '_' . $date2, $views, '15min');
        }

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

        $newLinks = Cache::read('newLinks_' . $this->Auth->user('id') . '_' . $date1 . '_' . $date2, '15min');
        if ($newLinks === false) {
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
                    'Links.user_id' => $this->Auth->user('id'),
                    "Links.status IN (1, 2)",
                ])
                ->order(['Links.created' => 'DESC'])
                ->bind(':date1', $date1, 'datetime')
                ->bind(':date2', $date2, 'datetime')
                ->limit(10)
                ->toArray();
            Cache::write('newLinks_' . $this->Auth->user('id') . '_' . $date1 . '_' . $date2, $newLinks, '15min');
        }
        $this->set('newLinks', $newLinks);

        $popularLinks = Cache::read('popularLinks_' . $this->Auth->user('id') . '_' . $date1 . '_' . $date2, '15min');
        if ($popularLinks === false) {
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
                    'Links.user_id' => $this->Auth->user('id'),
                    "Links.status IN (1, 2)",
                ])
                ->order(['Links.clicks' => 'DESC'])
                ->bind(':date1', $date1, 'datetime')
                ->bind(':date2', $date2, 'datetime')
                ->limit(10)
                ->toArray();
            Cache::write(
                'popularLinks_' . $this->Auth->user('id') . '_' . $date1 . '_' . $date2,
                $popularLinks,
                '15min'
            );
        }
        $this->set('popularLinks', $popularLinks);

        $this->loadModel('Announcements');

        $announcements = $this->Announcements->find()
            ->where(['Announcements.published' => 1])
            ->order(['Announcements.id DESC'])
            ->limit(3)
            ->toArray();
        $this->set('announcements', $announcements);
    }

    public function profile()
    {
        $user = $this->Users->find()->contain(['Plans'])->where(['Users.id' => $this->Auth->user('id')])->first();

        if ($this->request->is(['post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            //debug($user->errors());
            if ($this->Users->save($user)) {
                if ($this->Auth->user('id') === $user->id) {
                    $data = $user->toArray();
                    unset($data['password']);

                    $this->Auth->setUser($data);
                }
                $this->Flash->success(__('Profile has been updated'));
                $this->redirect(['action' => 'profile']);
            } else {
                $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
            }
        }
        unset($user->password);
        $this->set('user', $user);
    }

    public function plans()
    {
        if ((bool)get_option('enable_premium_membership') === false) {
            throw new NotFoundException(__('Invalid request'));
        }

        $user = $this->Users->findById($this->Auth->user('id'))->contain(['Plans'])->first();
        $this->set('user', $user);

        $plans = $this->Users->plans->find()->where(['enable' => 1, 'hidden' => 0]);
        $this->set('plans', $plans);
    }

    public function payPlan($id = null, $period = null)
    {
        if ((bool)get_option('enable_premium_membership') === false) {
            throw new NotFoundException(__('Invalid request'));
        }

        $this->request->allowMethod(['post']);

        if (!$id || !$period) {
            throw new NotFoundException(__('Invalid request'));
        }

        $plan = $this->Users->Plans->findById($id)->first();

        $amount = $plan->yearly_price;
        $period_name = __("Yearly");
        if ($period === 'm') {
            $amount = $plan->monthly_price;
            $period_name = __("Monthly");
        }

        $data = [
            'status' => 2, //Unpaid Invoice
            'user_id' => $this->Auth->user('id'),
            'description' => __("{0} Premium Membership: {1}", [$period_name, $plan->title]),
            'type' => 1, //Plan Invoice
            'rel_id' => $plan->id, //Plan Id
            'payment_method' => '',
            'amount' => $amount,
            'data' => serialize([
                'payment_period' => $period,
            ]),
        ];

        $invoice = $this->Users->Invoices->newEntity($data);

        if ($this->Users->Invoices->save($invoice)) {
            $this->Flash->success(__('An invoice with id: {0} has been generated.', $invoice->id));

            return $this->redirect(['controller' => 'Invoices', 'action' => 'view', $invoice->id]);
        }
    }

    public function changeEmail($username = null, $key = null)
    {
        if (!$username && !$key) {
            $user = $this->Users->findById($this->Auth->user('id'))->first();

            if ($this->request->is(['post', 'put'])) {
                $uuid = \Cake\Utility\Text::uuid();

                $user->activation_key = \Cake\Utility\Security::hash($uuid, 'sha1', true);

                $user = $this->Users->patchEntity($user, $this->request->data, ['validate' => 'changEemail']);

                if ($this->Users->save($user)) {
                    // Send rest email
                    $this->getMailer('User')->send('changeEmail', [$user]);

                    $this->Flash->success(__('Kindly check your email to confirm it.'));

                    $this->redirect(['action' => 'changeEmail']);
                } else {
                    $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
                }
            }
            $this->set('user', $user);
        } else {
            $user = $this->Users->find('all')
                ->contain(['Plans'])
                ->where([
                    'Users.status' => 1,
                    'Users.username' => $username,
                    'Users.activation_key' => $key,
                ])
                ->first();

            if (!$user) {
                $this->Flash->error(__('Invalid Activation.'));

                return $this->redirect(['action' => 'changeEmail']);
            }

            $user->email = $user->temp_email;
            $user->temp_email = '';
            $user->activation_key = '';

            if ($this->Users->save($user)) {
                if ($this->Auth->user('id') === $user->id) {
                    $data = $user->toArray();
                    unset($data['password']);

                    $this->Auth->setUser($data);
                }
                $this->Flash->success(__('Your email has been confirmed.'));

                return $this->redirect(['action' => 'signin', 'prefix' => 'auth']);
            } else {
                $this->Flash->error(__('Unable to confirm your email.'));

                return $this->redirect(['action' => 'changeEmail']);
            }
        }
    }

    public function changePassword()
    {
        $user = $this->Users->findById($this->Auth->user('id'))->first();

        if ($this->request->is(['post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data, ['validate' => 'changePassword']);
            //debug($user->errors());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Password has been updated'));
                $this->redirect(['action' => 'changePassword']);
            } else {
                $this->Flash->error(__('Oops! There are mistakes in the form. Please make the correction.'));
            }
        }
        unset($user->password);
        $this->set('user', $user);
    }
}
