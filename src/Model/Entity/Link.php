<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * @property int $user_id
 * @property int $bundle_id
 * @property string $alias
 * @property string $title
 * @property string $password
 * @property int $type
 * @property int $status
 * @property string|null $url
 * @property string|null $smart
 * @property string $domain
 * @property string $description
 * @property string $image
 * @property int $clicks
 * @property int $method
 * @property string $ip
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $created
 * @property int $id
 * @property \App\Model\Entity\User $user
 * @property \Cake\ORM\Entity $bundle
 * @property \App\Model\Entity\Statistic[] $statistics
 * @property string|null $url_hash
 * @property \App\Model\Entity\Bundle[] $bundles
 */
class Link extends Entity
{
    public function permalink()
    {
        return get_short_url($this->alias, $this->domain);
    }
}
