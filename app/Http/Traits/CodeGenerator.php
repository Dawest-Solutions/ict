<?php

namespace App\Http\Traits;


use App\Models\Employee;

trait CodeGenerator {


    /**
    * Generate alphanumeric code
    *
    * @param int $length
    * @return string
    */
    private static function generateCode(int $length = 6): string
    {

        $charset = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $charset_multiplied = str_repeat($charset, 5);
        $charset_array = str_split($charset_multiplied);

        shuffle($charset_array);

        $mixed_array = array_rand(array_flip($charset_array), $length);

        return implode($mixed_array);
    }

    /**
    * Check the code for a unique record in the DB
    *
    * @param $code
    * @return bool
    */
    private static function checkUnique($code): bool
    {

        $result = Employee::where('registration_code', $code)->get();

        return $result->count() == 0;
    }

    /**
    * Returns a unique alphanumeric code
    *
    * @param int $length
    * @return string
    */
    public static function getAlphanumericCode(int $length = 6): string
    {

        $attempts = 0;

        do {
          $code = self::generateCode($length);
          $attempts++;
        } while(!self::checkUnique($code) || $attempts < 50);

        return $code;
    }
}
