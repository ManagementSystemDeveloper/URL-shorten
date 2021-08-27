<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\HasMany $Users
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasMany $I18n
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Plans_title_translation
 * @property \Cake\ORM\Table&\Cake\ORM\Association\HasOne $Plans_description_translation
 *
 * @method \App\Model\Entity\Plan get($primaryKey, $options = [])
 * @method \App\Model\Entity\Plan newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Plan[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Plan|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Plan saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Plan patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Plan[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Plan findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 * @mixin \Cake\ORM\Behavior\TranslateBehavior
 */
class PlansTable extends Table
{
    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->hasMany('Users');
        $this->addBehavior('Translate', ['fields' => ['title', 'description']]);
    }

    public function validationDefault(Validator $validator)
    {
        $validator
            ->notBlank('title')
            ->boolean('enable', __('Choose a valid value.'))
            ->numeric('monthly_price', __('Choose a valid value.'))
            ->numeric('yearly_price', __('Choose a valid value.'))
            ->numeric('stats', __('Enter a valid value.'))
            ->numeric('timer', __('Enter a valid value.'));

        return $validator;
    }
}
