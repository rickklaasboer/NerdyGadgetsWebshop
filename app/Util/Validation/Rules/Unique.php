<?php

namespace App\Util\Validation\Rules;

use App\Util\Validation\Validator;
use Doctrine\ORM\EntityManager;

class Unique implements Rule
{
    private string $entity;
    private string $column;

    public function __construct(string $entity, string $column)
    {
        $this->entity = $entity;
        $this->column = $column;
    }

    public function run(Validator $validator, string $key, mixed $value): bool
    {
        /** @var EntityManager $manager */
        $manager = app(EntityManager::class);

        $q = $manager->createQueryBuilder()
            ->select('x')
            ->from($this->entity, 'x')
            ->where($manager->getExpressionBuilder()->eq(sprintf('x.%s', $this->column), ':value'))
            ->setParameter('value', $value);

        $condition = count($q->getQuery()->getResult()) < 1;

        if (!$condition) {
            $validator->bag->add(sprintf("The %s field should be unique", $key));
        }

        return $condition;
    }
}