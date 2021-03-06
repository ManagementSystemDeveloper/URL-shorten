<?php

namespace App\Model\Table;

use Cake\I18n\Time;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Network\Exception\BadRequestException;

/**
 * @property \App\Model\Table\LinksTable&\Cake\ORM\Association\HasMany $Links
 * @property \App\Model\Table\StatisticsTable&\Cake\ORM\Association\HasMany $Statistics
 * @property \App\Model\Table\PlansTable&\Cake\ORM\Association\BelongsTo $Plans
 * @property \App\Model\Table\InvoicesTable&\Cake\ORM\Association\HasMany $Invoices
 * @property \App\Model\Table\SocialProfilesTable&\Cake\ORM\Association\HasMany $SocialProfiles
 *
 * @method \Cake\ORM\Query findById($id)
 * @property \App\Model\Table\BundlesTable&\Cake\ORM\Association\HasMany $Bundles
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \App\Model\Table\RememberTokensTable&\Cake\ORM\Association\HasMany $RememberTokens
 */
class UsersTable extends Table
{
    public function initialize(array $config)
    {
        $this->hasMany('Links');
        $this->hasMany('Statistics');
        $this->hasMany('Invoices');
        $this->hasMany('Bundles');
        $this->hasMany('RememberTokens');
        $this->belongsTo('Plans');
        $this->hasMany('SocialProfiles');
        $this->addBehavior('Timestamp');
    }

    public function findAuth(\Cake\ORM\Query $query, array $options)
    {
        $user_status = 1;
        if (version_compare(get_option('app_version', '1.0.0'), '2.0.0', '<')) {
            $user_status = 'Active';
        }

        $andWhere = [
            'Users.status' => $user_status,
            'Users.id <>' => 1,
        ];

        if ((bool)get_option('maintenance_mode', false)) {
            $andWhere['Users.role'] = 1;
        }

        $query
            ->where(
                [
                    'OR' => [
                        ['Users.username' => $options['username']],
                        ['Users.email' => $options['username']],
                    ],
                ],
                [],
                true
            )
            ->andwhere($andWhere);

        return $query;
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notBlank('username', 'A username is required')
            ->add('username', [
                'alphaNumeric' => [
                    'rule' => ['alphaNumeric'],
                    'message' => __('alphaNumeric Only'),
                ],
                'minLength' => [
                    'rule' => ['minLength', 5],
                    'message' => __('Minimum Length 5'),
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 255],
                    'message' => __('Maximum Length 255'),
                ],
            ])
            ->add('username', 'checkReserved', [
                'rule' => function ($value, $context) {
                    $reserved_domains = explode(',', get_option('reserved_usernames'));
                    $reserved_domains = array_map('trim', $reserved_domains);
                    $reserved_domains = array_filter($reserved_domains);

                    if (in_array(strtolower($value), $reserved_domains)) {
                        return false;
                    }

                    return true;
                },
                'message' => __('This username is a reserved word.'),
            ])
            ->add('username', [
                'unique' => [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => __('Username already exists'),
                ],
            ])
            ->add('username_compare', [
                'compare' => [
                    'rule' => ['compareWith', 'username'],
                    'message' => __('Not the same'),
                ],
            ])
            ->notBlank('password', 'A password is required')
            ->add('password', [
                'minLength' => [
                    'rule' => ['minLength', 5],
                    'message' => __('Minimum Length 5'),
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 25],
                    'message' => __('Maximum Length 25'),
                ],
            ])
            ->add('password_compare', [
                'compare' => [
                    'rule' => ['compareWith', 'password'],
                    'message' => __('Not the same'),
                ],
            ])
            ->notBlank('email', 'An email is required')
            ->add('email', 'validFormat', [
                'rule' => 'email',
                'message' => __('E-mail must be valid'),
            ])
            ->add('email', [
                'unique' => [
                    'rule' => 'validateUnique',
                    'provider' => 'table',
                    'message' => __('E-mail must be unique'),
                ],
            ])
            ->add('email_compare', [
                'compare' => [
                    'rule' => ['compareWith', 'email'],
                    'message' => __('Not the same'),
                ],
            ])
            ->allowEmptyString('first_name')
            ->allowEmptyString('last_name')
            ->add('redirect_type', 'inList', [
                'rule' => ['inList', array_keys(get_allowed_redirects())],
                'message' => __('Choose a valid value.'),
            ])
            ->equals('accept', 1, __('To use our service you must accept our Terms of Use and Privacy Policy.'));
    }

    public function validationChangeEmail(Validator $validator)
    {
        //$validator = $this->validateDefault($validator);
        return $validator
            ->notBlank('temp_email', 'An email is required')
            ->add('temp_email', 'validFormat', [
                'rule' => 'email',
                'message' => __('E-mail must be valid'),
            ])
            ->add('temp_email', 'custom', [
                'rule' => function ($value, $context) {
                    $count = $this->find('all')
                        ->where(['email' => $value])
                        ->count();
                    if ($count > 0) {
                        return false;
                    } else {
                        return true;
                    }
                },
                'message' => __('E-mail must be unique'),
            ])
            ->add('confirm_email', [
                'compare' => [
                    'rule' => ['compareWith', 'temp_email'],
                    'message' => __('Not the same'),
                ],
            ]);
    }

    public function validationChangePassword(Validator $validator)
    {
        //$validator = $this->validateDefault($validator);
        return $validator
            ->notBlank('current_password', 'Please enter current password.')
            ->add('current_password', 'custom', [
                'rule' => function ($value, $context) {
                    $user = $this->findById($context['data']['id'])->first();

                    return (new DefaultPasswordHasher)->check($value, $user->password);
                },
                'message' => __('Please enter current password.'),
            ])
            ->notBlank('password', 'A password is required')
            ->add('password', [
                'minLength' => [
                    'rule' => ['minLength', 5],
                    'message' => __('Minimum Length 5'),
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 25],
                    'message' => __('Maximum Length 25'),
                ],
            ])
            ->add('confirm_password', [
                'compare' => [
                    'rule' => ['compareWith', 'password'],
                    'message' => __('Not the same'),
                ],
            ]);
    }

    public function validationForgotPassword(Validator $validator)
    {
        //$validator = $this->validateDefault($validator);
        return $validator
            ->notBlank('email', 'An email is required')
            ->add('email', 'validFormat', [
                'rule' => 'email',
                'message' => __('E-mail must be valid'),
            ])
            ->notBlank('password', 'A password is required')
            ->add('password', [
                'minLength' => [
                    'rule' => ['minLength', 5],
                    'message' => __('Minimum Length 5'),
                ],
                'maxLength' => [
                    'rule' => ['maxLength', 25],
                    'message' => __('Maximum Length 25'),
                ],
            ])
            ->add('confirm_password', [
                'compare' => [
                    'rule' => ['compareWith', 'password'],
                    'message' => __('Not the same'),
                ],
            ]);
    }

    public function getUser(\Cake\Datasource\EntityInterface $profile)
    {
        if ((bool)get_option('close_registration', false) ||
            (bool)get_option('private_service', false)) {
            session_destroy();
            throw new BadRequestException(__('Registration is currently closed.'));
        }

        $user_where_done = false;
        if (!empty($profile->email)) {
            $user_where['Users.email'] = $profile->email;
            $user_where_done = true;
        }

        if ($user_where_done === false) {
            $user_where['Users.username'] = $profile->identifier;
        }

        // Check if user with same email exists. This avoids creating multiple
        // user accounts for different social identities of same user. You should
        // probably skip this check if your system doesn't enforce unique email
        // per user.
        /**
         * @var \App\Model\Entity\User $user
         */
        $user = $this->find()
            ->where($user_where)
            ->first();

        if ($user) {
            if ($user->status !== 1) {
                session_destroy();
                throw new BadRequestException(__('Your account has been deactivated.'));
            }

            return $user;
        }

        $plan_id = 1;
        $plan_expiration = null;

        $trial_plan = (int)get_option('trial_plan', '');
        if ($trial_plan > 1) {
            $plan_expiration = Time::now();

            if (get_option('trial_plan_period', 'm') === 'm') {
                $expiration = $plan_expiration->addMonth();
            } else {
                $expiration = $plan_expiration->addYear();
            }

            $plan_id = $trial_plan;
            $plan_expiration = $expiration;
        }

        // Create new user account
        $data = [
            'status' => 1,
            'username' => $profile->identifier,
            'password' => generate_random_string(10),
            'plan_id' => $plan_id,
            'role' => 2,
            'api_token' => \Cake\Utility\Security::hash(\Cake\Utility\Text::uuid(), 'sha1', true),
            'register_ip' => get_ip(),
        ];

        if (!empty($profile->email)) {
            $data['email'] = $profile->email;
        }

        if (!empty($profile->first_name)) {
            $data['first_name'] = $profile->first_name;
        }

        if (!empty($profile->last_name)) {
            $data['last_name'] = $profile->last_name;
        }

        if (!empty($plan_expiration)) {
            $data['expiration'] = $plan_expiration;
        }

        $user = $this->newEntity($data);

        $user = $this->save($user);

        if ($user) {
            $user = $this->find()->contain(['Plans'])->where(['Users.id' => $user->id])->first();

            return $user;
        } else {
            //debug($user->errors());
            \Cake\Log\Log::write('debug', $user->getErrors());
            session_destroy();
            throw new \RuntimeException('Unable to save new user');
        }
    }
}
