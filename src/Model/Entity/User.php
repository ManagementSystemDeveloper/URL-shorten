<?php

namespace App\Model\Entity;

use Cake\Auth\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * @property int $status
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $temp_email
 * @property int $role
 * @property int $last_bundle_id
 * @property int $plan_id
 * @property string $api_token
 * @property string $activation_key
 * @property int $urls
 * @property \App\Model\Entity\Plan $plan
 * @property string $first_name
 * @property string $last_name
 * @property string $feed
 * @property int $redirect_type
 * @property string $disqus_shortname
 * @property string $login_ip
 * @property string register_ip
 * @property \Cake\I18n\FrozenTime $last_login
 * @property \Cake\I18n\FrozenTime|null $expiration
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $created
 * @property int $id
 * @property \App\Model\Entity\Link[] $links
 * @property \App\Model\Entity\Statistic[] $statistics
 * @property \App\Model\Entity\Invoice[] $invoices
 * @property \App\Model\Entity\Bundle[] $bundles
 * @property \App\Model\Entity\SocialProfile[] $social_profiles
 * @property \App\Model\Entity\RememberToken[] $remember_tokens
 */
class User extends Entity
{
    // Make all fields mass assignable for now.
    protected $_accessible = ['*' => true];

    protected function _setPassword($password)
    {
        return (new DefaultPasswordHasher)->hash($password);
    }
}
