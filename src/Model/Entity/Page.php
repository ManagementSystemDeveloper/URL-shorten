<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int $published
 * @property string|null $content
 * @property \Cake\I18n\FrozenTime $modified
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\ORM\Entity $title_translation
 * @property \Cake\ORM\Entity $content_translation
 * @property \Cake\ORM\Entity[] $_i18n
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property \Cake\ORM\Entity $meta_title_translation
 * @property \Cake\ORM\Entity $meta_description_translation
 */
class Page extends Entity
{
    public function permalink()
    {
        return Router::url(['_name' => 'page.view', 'slug' => $this->slug], true);
    }
}
