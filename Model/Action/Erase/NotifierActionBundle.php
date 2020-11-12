<?php
/**
 * Copyright © Landofcoder, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Lof\Gdpr\Model\Action\Erase;

use InvalidArgumentException;
use Lof\Gdpr\Api\Data\ActionContextInterface;
use Lof\Gdpr\Api\Data\ActionResultInterface;
use Lof\Gdpr\Model\Action\AbstractAction;
use Lof\Gdpr\Model\Action\ArgumentReader as ActionArgumentReader;
use Lof\Gdpr\Model\Action\ResultBuilder;
use Lof\Gdpr\Model\Erase\NotifierInterface;
use Lof\Gdpr\Model\EraseEntityRepository;
use Magento\Framework\ObjectManagerInterface;
use function sprintf;

final class NotifierActionBundle extends AbstractAction
{
    /**
     * @var string[]
     */
    private $notifiers;

    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;
    /**
     * @var EraseEntityRepository
     */
    private $eraseEntity;

    public function __construct(
        ResultBuilder $resultBuilder,
        array $notifiers,
        ObjectManagerInterface $objectManager,
        EraseEntityRepository $eraseEntityRepository
    ) {
        $this->notifiers = $notifiers;
        $this->objectManager = $objectManager;
        $this->eraseEntity = $eraseEntityRepository;
        parent::__construct($resultBuilder);
    }

    public function execute(ActionContextInterface $actionContext): ActionResultInterface
    {
        $eraseId = ActionArgumentReader::getEraseId($actionContext);
        if ($eraseId) {
            $eraseModel = $this->eraseEntity->getById($eraseId);
            $this->resolveNotifier($actionContext)->notify($eraseModel);
        }

        return $this->createActionResult(['is_notify' => true]);
    }

    private function resolveNotifier(ActionContextInterface $actionContext): NotifierInterface
    {
        $entityType = ActionArgumentReader::getEntityType($actionContext);

        if (!isset($this->notifiers[$entityType])) {
            throw new InvalidArgumentException(sprintf('Unknown notifier for entity type "%s".', $entityType));
        }

        return $this->objectManager->get($this->notifiers[$entityType]);
    }
}
