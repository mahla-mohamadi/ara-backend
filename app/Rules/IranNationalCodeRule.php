<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class IranNationalCodeRule implements ValidationRule {

    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (!$this->validateNationalCode($value)) {
            $fail('The :attribute is not a valid card number.');
        }
    }

    public function validateNationalCode($value)
    {
        if (!preg_match('/^[0-9]{10}$/',$value))
            return false;
        for ($i=0;$i<10;$i++)
            if (preg_match('/^'.$i.'{10}$/',$value))
                return false;
        for ($i=0,$sum=0;$i<9;$i++)
            $sum+=((10-$i)*intval(substr($value,$i)));
        $ret=$sum%11;
        $parity=intval(substr($value,9,1));
        if (($ret<2 && $ret==$parity) || ($ret>=2 && $ret == 11-$parity))
            return true;
        return false;
    }
}
