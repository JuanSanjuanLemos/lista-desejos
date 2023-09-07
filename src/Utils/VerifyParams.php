<?php
namespace App\Utils;
class VerifyParams{
    static public function verifyIsSet($array = array(), $data = array())
    {
        foreach ($array as $value) {
            if (!array_key_exists($value, $data)) {
                throw new \InvalidArgumentException("Valores inválidos!");
            }
        }
    }
}