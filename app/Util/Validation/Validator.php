<?php

namespace App\Util\Validation;

use App\Util\Validation\Rules\Rule;
use Exception;

class Validator
{
    private array $form;

    private array $rules;

    public MessageBag $bag;

    public function __construct(array $form, array $rules)
    {
        $this->form = $form;
        $this->rules = $rules;

        $this->bag = new MessageBag();
    }

    /**
     * Run the validator
     */
    public function validate()
    {
        // First, extract all rules from rules array
        foreach ($this->rules as $key => $rule) {

            // If the rule is an instance of builder we need to get the built array out of it first
            if ($rule instanceof Builder) {
                $rule = $rule->get();
            }

            // If the rule is a validation string, we need to build it first.
            if (is_string($rule)) {
                $rule = Builder::make()->parse($rule);
            }

            // Now run every rule to validate a key/value pair
            foreach ($rule as $r) {

                // Throw exception when validation rule did not implement our rule interface
                if (!$r instanceof Rule) {
                    throw new Exception(sprintf('Rule %s did not implement the Rule interface' . get_class($rule)));
                }

                // Run rule against key/value
                $r->run($this, $key, $this->form[$key] ?? '');
            }
        }
    }

    /**
     * Get the validator's form
     *
     * @return array
     */
    public function getForm(): array
    {
        return $this->form;
    }

    /**
     * Check if the validator failed
     *
     * @return bool
     */
    public function fails(): bool
    {
        $this->validate();

        return !$this->bag->isEmpty();
    }

    /**
     * Get all validation messages
     *
     * @return array
     */
    public function messages()
    {
        return $this->bag->all();
    }
}