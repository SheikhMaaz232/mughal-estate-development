<?php

if (!function_exists('amountInWords')) {
    function amountInWords($amount)
    {
        if ($amount === null) {
            return '';
        }

        $formatter = new NumberFormatter('en', NumberFormatter::SPELLOUT);
        return ucfirst($formatter->format($amount)) . ' only';
    }
}
