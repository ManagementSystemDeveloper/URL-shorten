<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $slug
 * @property string $title
 * @property bool $private
 * @property string $description
 * @property int $views
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $created
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Link[] $links
 */
class Bundle extends Entity
{
    public function permalink()
    {
        return Router::url([
            '_name' => 'bundle.view',
            'username' => $this->user->username,
            'slug' => $this->slug,
        ], true);
    }
}
