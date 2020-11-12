<?php
/**
 * Copyright © Landofcoder, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Lof\Gdpr\Model\Action;

use Lof\Gdpr\Api\Data\ActionContextInterface;

final class ArgumentReader
{
    public const ENTITY_TYPE = 'entity_type';
    public const ENTITY_ID = 'entity_id';

    public static function getEntityType(ActionContextInterface $actionContext): ?string
    {
        if (!is_string($actionContext->getParameters()[self::ENTITY_TYPE])) {
            return ($actionContext->getParameters()[self::ENTITY_TYPE]->getData(self::ENTITY_TYPE)) ?? null;
        } else {
            return $actionContext->getParameters()[self::ENTITY_TYPE] ?? null;
        }
    }

    public static function getEntityId(ActionContextInterface $actionContext): ?int
    {
        return $actionContext->getParameters()[self::ENTITY_ID] ?? null;
    }

    public static function getEraseId(ActionContextInterface  $actionContext): ?int
    {
        if (is_object($actionContext->getParameters()[self::ENTITY_TYPE])) {
            return (int)($actionContext->getParameters()[self::ENTITY_TYPE]->getData('erase_id')) ?? null;
        } else {
            return null;
        }
    }
}
