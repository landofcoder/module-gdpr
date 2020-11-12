<?php
/**
 * Copyright © Landofcoder, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Lof\Gdpr\Model\Customer\Erase;

use Lof\Gdpr\Api\Data\EraseEntityInterface;
use Lof\Gdpr\Model\Customer\Notifier\SenderInterface;
use Lof\Gdpr\Model\Erase\NotifierInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

final class Notifier implements NotifierInterface
{
    /**
     * @var SenderInterface[]
     */
    private $senders;

    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    public function __construct(
        array $senders,
        CustomerRepositoryInterface $customerRepository
    ) {
        $this->senders = (static function (SenderInterface ...$senders): array {
            return $senders;
        })(...\array_values($senders));
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param EraseEntityInterface $eraseEntity
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function notify(EraseEntityInterface $eraseEntity): void
    {
        $customer = $this->customerRepository->getById($eraseEntity->getEntityId());

        foreach ($this->senders as $sender) {
            $sender->send($customer);
        }
    }
}
