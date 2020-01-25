<?php
namespace App\SymfonyPayments\Utils;


use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class FieldUtils
{
    public static function getSafeJson($string)
    {
        $data = json_decode($string);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new UnauthorizedHttpException('Invalid JSON Body: ' . json_last_error_msg());
        }

        return $data;
    }
}