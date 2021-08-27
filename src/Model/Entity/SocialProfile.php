<?php

namespace App\Model\Entity;

use ADmad\SocialAuth\Model\Entity\SocialProfile as SocialProfilePlugin;

/**
 * @property int $id
 * @property int|null $user_id
 * @property string $provider
 * @property string $identifier
 * @property string|null $profile_url
 * @property string|null $website_url
 * @property string|null $photo_url
 * @property string|null $display_name
 * @property string|null $description
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $gender
 * @property string|null $language
 * @property string|null $age
 * @property string|null $birth_day
 * @property string|null $birth_month
 * @property string|null $birth_year
 * @property string|null $email
 * @property string|null $email_verified
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $country
 * @property string|null $region
 * @property string|null $city
 * @property string|null $zip
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \App\Model\Entity\User|null $user
 */
class SocialProfile extends SocialProfilePlugin
{
}
