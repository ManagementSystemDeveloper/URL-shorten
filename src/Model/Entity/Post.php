<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $published
 * @property string|null $short_description
 * @property string|null $description
 * @property \Cake\I18n\FrozenTime|null $modified
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\ORM\Entity $title_translation
 * @property \Cake\ORM\Entity $slug_translation
 * @property \Cake\ORM\Entity $short_description_translation
 * @property \Cake\ORM\Entity $description_translation
 * @property \Cake\ORM\Entity[] $_i18n
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property \Cake\ORM\Entity $meta_title_translation
 * @property \Cake\ORM\Entity $meta_description_translation
 */
class Post extends Entity
{
    public function permalink()
    {
        return Router::url(['_name' => 'blog.view', 'id' => $this->id, 'slug' => $this->slug], true);
    }
}
