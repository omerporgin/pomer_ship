<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class HiddenChars implements InvokableRule
{
    /**
     * @var array
     */
    protected $hiddenValues = [
        '&ZeroWidthSpace;',
        '​',
        '&NegativeThickSpace;',
        '&NegativeMediumSpace;',
        '&NegativeThinSpace;',
        '&NegativeVeryThinSpace;',
        '&#8203;',
        '&#x200B;',
    ];

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        foreach ($this->hiddenValues as $hiddenValue) {
            if (str_contains($value, $hiddenValue)){
                $fail('The :attribute include hidden chars.');
            }
        }
    }
}
