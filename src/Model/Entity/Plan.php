<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property string $title
 * @property bool $enable
 * @property bool $hidden
 * @property string|null $description
 * @property float $monthly_price
 * @property float $yearly_price
 * @property int $url_daily_limit
 * @property int $url_monthly_limit
 * @property bool $edit_link
 * @property bool $edit_long_url
 * @property bool $alias
 * @property bool $password
 * @property bool $delete_link
 * @property bool $bundle
 * @property bool $comments
 * @property int $stats
 * @property bool $api_quick
 * @property bool $api_mass
 * @property bool $api_full
 * @property bool $api_developer
 * @property bool $bookmarklet
 * @property bool $feed
 * @property bool $disable_ads_area1
 * @property bool $disable_ads_area2
 * @property int $timer
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $created
 * @property int $id
 * @property \App\Model\Entity\User[] $users
 * @property \Cake\ORM\Entity $title_translation
 * @property \Cake\ORM\Entity $description_translation
 * @property \Cake\ORM\Entity[] $_i18n
 */
class Plan extends Entity
{
}
