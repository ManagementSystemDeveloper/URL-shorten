<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

/**
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\LinksTable&\Cake\ORM\Association\BelongsToMany $Links
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasMany $BundlesLinks
 * @method \App\Model\Entity\Bundle get($primaryKey, $options = [])
 * @method \App\Model\Entity\Bundle newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Bundle[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Bundle|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bundle saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Bundle patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Bundle[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Bundle findOrCreate($search, callable $callback = null, $options = [])
 */
class BundlesTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->belongsTo('Users');
        $this->belongsToMany('Links');
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notBlank('title')
            ->allowEmptyString('slug')
            ->add('private', 'inList', [
                'rule' => ['inList', ['0', '1']],
                'message' => __('Choose a valid value.'),
            ])
            ->allowEmptyString('description');

        return $validator;
    }

    //http://www.whatstyle.net/articles/52/generate_unique_slugs_in_cakephp
    public function createSlug($slug, $id = null, $user_id = null)
    {
        $slug = mb_strtolower(Text::slug($slug, '-'));
        $i = 0;
        $conditions = [];
        $conditions['Bundles.user_id'] = $user_id;
        $conditions['Bundles.slug'] = $slug;
        if (!is_null($id)) {
            $conditions['Bundles.id <>'] = $id;
        }

        while ($this->find()->where($conditions)->count()) {
            if (!preg_match('/-{1}[0-9]+$/', $slug)) {
                $slug .= '-' . ++$i;
            } else {
                $slug = preg_replace('/[0-9]+$/', ++$i, $slug);
            }
            $conditions['Bundles.slug'] = $slug;
        }

        return $slug;
    }
}
