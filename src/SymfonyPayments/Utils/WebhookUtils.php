<?php

namespace App\SymfonyPayments\Utils;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class WebhookUtils {
    public static function checkEmpty($field, $fieldName) {
        if (empty($field)) {
            throw new BadRequestHttpException('Invalid JSON Body: ' . $fieldName . ' required');
        }

        return $field;
    }
}