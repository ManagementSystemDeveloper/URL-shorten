<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $link_id
 * @property int $user_id
 * @property string $ip
 * @property string $country
 * @property string $referer_domain
 * @property string $referer
 * @property string|null $user_agent
 * @property \Cake\I18n\FrozenTime $created
 * @property int $id
 * @property string $continent
 * @property string $state
 * @property string $city
 * @property string $location
 * @property string $browser
 * @property string $platform
 * @property string $device_type
 * @property string $device_brand
 * @property string $device_name
 * @property bool $is_mobile
 * @property bool $is_tablet
 * @property string $language
 * @property string $timezone
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Link $link
 */
class Statistic extends Entity
{
}
