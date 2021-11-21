<?php

namespace App\Util\Validation;

use App\Util\Validation\Rules\Between;
use App\Util\Validation\Rules\Email;
use App\Util\Validation\Rules\Equals;
use App\Util\Validation\Rules\Exists;
use App\Util\Validation\Rules\Max;
use App\Util\Validation\Rules\Min;
use App\Util\Validation\Rules\Required;
use App\Util\Validation\Rules\Rule;
use App\Util\Validation\Rules\Unique;
use InvalidArgumentException;

class Builder
{
    protected $validators = [
        'between' => Between::class,
        'email' => Email::class,
        'equals' => Equals::class,
        'exists' => Exists::class,
        'max' => Max::class,
        'min' => Min::class,
        'required' => Required::class,
        'unique' => Unique::class,
    ];

    /**
     * @var Rule[] array
     */
    protected array $build = [];

    /**
     * Create instance
     *
     * @return self
     */
    public static function make(): self
    {
        return new self;
    }

    /**
     * Allows creating a validator rule set as a string
     *
     * For example:
     * 'key' => 'required|min:1|max:1'
     *
     * @param string $validators
     * @return Rule[]
     */
    public function parse(string $validators)
    {
        $rules = explode('|', $validators);

        foreach ($rules as $rule) {

            $name = $rule;
            $rawParams = null;

            if (strpos($rule, ':')) {
                [$name, $rawParams] = explode(':', $rule);
            }

            if (!array_key_exists($name, $this->validators)) {
                throw new InvalidArgumentException(sprintf('Validator %s does not exist', $name));
            }

            $validator = $this->validators[$name];
            $parameters = [];

            if ($rawParams) {
                $parameters = explode(',', $rawParams);
            }

            $this->build[] = new $validator(...$parameters);
        }

        return $this->get();
    }

    /**
     * Create a between rule
     *
     * @param int $min
     * @param int $max
     * @return $this
     */
    public function between(int $min, int $max): self
    {
        $this->build[] = new Between($min, $max);

        return $this;
    }

    /**
     * Create an email rule
     *
     * @return $this
     */
    public function email(): self
    {
        $this->build[] = new Email();

        return $this;
    }

    /**
     * Create an equals rule
     *
     * @return $this
     */
    public function equals(mixed $other): self
    {
        $this->build[] = new Equals($other);

        return $this;
    }

    /**
     * Create an exists rule
     *
     * @return $this
     */
    public function exists(string $entity, string $column): self
    {
        $this->build[] = new Exists($entity, $column);

        return $this;
    }

    /**
     * Create a min rule
     *
     * @return $this
     */
    public function min(int $length): self
    {
        $this->build[] = new Min($length);

        return $this;
    }

    /**
     * Create a max rule
     *
     * @return $this
     */
    public function max(int $length): self
    {
        $this->build[] = new Max($length);

        return $this;
    }

    /**
     * Create a required rule
     *
     * @return $this
     */
    public function required(): self
    {
        $this->build[] = new Required();

        return $this;
    }

    /**
     * Create a unique rule
     *
     * @return $this
     */
    public function unique(string $entity, string $column): self
    {
        $this->build[] = new Unique($entity, $column);

        return $this;
    }

    /**
     * Create a set of validator rules
     * NOTE: calling get() will reset build
     *
     * @return Rule[] array
     */
    public function get(): array
    {
        $build = $this->build;
        $this->build = [];

        return $build;
    }
}