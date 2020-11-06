<?php
/**
 * Copyright © Landofcoder, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Lof\Gdpr\Model\Entity\EntityValue;

use Lof\Gdpr\Model\Entity\EntityValueProcessorInterface;
use function array_combine;
use function array_keys;
use function array_values;

final class StrategyProcessor implements EntityValueProcessorInterface
{
    /**
     * @var EntityValueProcessorInterface[]
     */
    private $processors;

    /**
     * @param EntityValueProcessorInterface[] $processors
     */
    public function __construct(
        array $processors
    ) {
        $this->processors = (static function (EntityValueProcessorInterface ...$processors): array {
            return $processors;
        })(...array_values($processors));

        $this->processors = array_combine(array_keys($processors), $this->processors);
    }

    public function process($entity, string $key, $value): void
    {
        ($this->processors[$key] ?? $this->processors['default'])->process($entity, $key, $value);
    }
}
