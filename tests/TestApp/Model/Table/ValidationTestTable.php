<?php
declare(strict_types=1);

namespace DataValidationTesting\Test\TestApp\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * A test table used to exercise the DataValidationTestTrait methods.
 */
class ValidationTestTable extends Table
{
    /**
     * @inheritDoc
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->setTable('validation_test');
    }

    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('required_field')
            ->notEmptyString('not_empty_field')
            ->allowEmptyString('empty_field')
            ->boolean('boolean_field')
            ->allowEmptyString('boolean_field')
            ->urlWithProtocol('url_field')
            ->allowEmptyString('url_field')
            ->dateTime('datetime_field')
            ->allowEmptyDateTime('datetime_field')
            ->maxLength('max_length_field', 10)
            ->allowEmptyString('max_length_field')
            ->minLength('min_length_field', 5)
            ->allowEmptyString('min_length_field')
            ->scalar('scalar_field')
            ->maxLength('scalar_field', 50)
            ->allowEmptyString('scalar_field')
            ->lengthBetween('length_between_field', [5, 10])
            ->allowEmptyString('length_between_field')
            ->naturalNumber('natural_number_field')
            ->allowEmptyString('natural_number_field')
            ->allowEmptyString('unique_field');

        return $validator;
    }

    /**
     * @inheritDoc
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['unique_field']));

        return $rules;
    }
}
