<?php
/**
 * Copyright © Landofcoder, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Lof\Gdpr\Model\Customer\Delete\Processor;

use Magento\Customer\Api\AddressRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Lof\Gdpr\Service\Erase\ProcessorInterface;

final class CustomerAddressDataProcessor implements ProcessorInterface
{
    /**
     * @var AddressRepositoryInterface
     */
    private $customerAddressRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    public function __construct(
        AddressRepositoryInterface $customerAddressRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->customerAddressRepository = $customerAddressRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritdoc
     * @throws LocalizedException
     */
    public function execute(int $customerId): bool
    {
        $this->searchCriteriaBuilder->addFilter('parent_id', $customerId);
        $addressList = $this->customerAddressRepository->getList($this->searchCriteriaBuilder->create());

        foreach ($addressList->getItems() as $address) {
            $this->customerAddressRepository->delete($address);
        }

        return true;
    }
}