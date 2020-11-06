<?php
/**
 * Copyright © Landofcoder, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Lof\Gdpr\Model\Entity;

final class DataCollector implements DataCollectorInterface
{
    /**
     * @var EntityIteratorInterface
     */
    private $entityIterator;

    /**
     * @var DocumentInterface
     */
    private $document;

    public function __construct(
        EntityIteratorInterface $entityIterator,
        DocumentInterface $document
    ) {
        $this->entityIterator = $entityIterator;
        $this->document = $document;
    }

    public function collect(object $entity): array
    {
        $this->entityIterator->iterate($entity);
        $data = $this->document->getData();
        $this->document->setData([]);

        return $data;
    }
}
