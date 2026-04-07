<?php
declare(strict_types=1);

namespace DataValidationTesting\Test\TestApp\Model\Table;

use Cake\Database\Schema\TableSchema;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * The Validation Test Table
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
        $this->setSchema($this->buildSchema());
    }

    /**
     * Build the table schema.
     *
     * @return TableSchema
     */
    private function buildSchema(): TableSchema
    {
        $schema = new TableSchema('validation_test');
        $schema
            ->addColumn('id', ['type' => 'integer'])
            ->addColumn('required_field', ['type' => 'string', 'length' => 255])
            ->addColumn('not_empty_field', ['type' => 'string', 'length' => 255])
            ->addColumn('empty_field', ['type' => 'string', 'length' => 255])
            ->addColumn('boolean_field', ['type' => 'boolean'])
            ->addColumn('url_field', ['type' => 'string', 'length' => 255])
            ->addColumn('datetime_field', ['type' => 'datetime'])
            ->addColumn('max_length_field', ['type' => 'string', 'length' => 255])
            ->addColumn('min_length_field', ['type' => 'string', 'length' => 255])
            ->addColumn('scalar_field', ['type' => 'string', 'length' => 255])
            ->addColumn('length_between_field', ['type' => 'string', 'length' => 255])
            ->addColumn('natural_number_field', ['type' => 'integer'])
            ->addConstraint('primary', ['type' => 'primary', 'columns' => ['id']]);

        return $schema;
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
            ->allowEmptyString('natural_number_field');

        return $validator;
    }
}
