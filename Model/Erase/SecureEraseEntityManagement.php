<?php
/**
 * Copyright © Landofcoder, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Lof\Gdpr\Model\Erase;

use Lof\Gdpr\Api\Data\EraseEntityInterface;
use Lof\Gdpr\Api\EraseEntityCheckerInterface;
use Lof\Gdpr\Api\EraseEntityManagementInterface;
use Lof\Gdpr\Model\EraseEntityManagement;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Phrase;

final class SecureEraseEntityManagement implements EraseEntityManagementInterface
{
    /**
     * @var EraseEntityManagement
     */
    private $eraseEntityManagement;

    /**
     * @var EraseEntityCheckerInterface
     */
    private $eraseEntityChecker;

    public function __construct(
        EraseEntityManagement $eraseEntityManagement,
        EraseEntityCheckerInterface $eraseEntityChecker
    ) {
        $this->eraseEntityManagement = $eraseEntityManagement;
        $this->eraseEntityChecker = $eraseEntityChecker;
    }

    public function create(int $entityId, string $entityType): EraseEntityInterface
    {
        if ($this->eraseEntityChecker->canCreate($entityId, $entityType)) {
            return $this->eraseEntityManagement->create($entityId, $entityType);
        }

        throw new LocalizedException(
            new Phrase(
                'Impossible to initiate the erasure, it\'s already processing or there is still pending orders.'
            )
        );
    }

    public function cancel(int $entityId, string $entityType): bool
    {
        if ($this->eraseEntityChecker->canCancel($entityId, $entityType)) {
            return $this->eraseEntityManagement->cancel($entityId, $entityType);
        }

        throw new LocalizedException(new Phrase('The erasure process is running and cannot be undone.'));
    }

    public function process(EraseEntityInterface $entity): EraseEntityInterface
    {
        if ($this->eraseEntityChecker->canProcess($entity->getEntityId(), $entity->getEntityType())) {
            return $this->eraseEntityManagement->process($entity);
        }
        throw new LocalizedException(new Phrase('Impossible to process the erasure, there is still pending orders.'));
    }
}
