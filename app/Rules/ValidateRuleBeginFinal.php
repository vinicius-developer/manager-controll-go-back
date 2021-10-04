<?php

namespace App\Rules;

use DateTime;
use Illuminate\Contracts\Validation\Rule;

class ValidateRuleBeginFinal implements Rule
{
    private $final;
    private $begin;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $beginDate, string $finalDate)
    {
        $this->begin = new Datetime($beginDate);
        $this->final = new Datetime($finalDate);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if($this->begin < $this->final) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Data de inicio é menor do que a data final';
    }
}
