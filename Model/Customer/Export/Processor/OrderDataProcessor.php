<?php
/**
 * Copyright © Landofcoder, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Lof\Gdpr\Model\Customer\Export\Processor;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Sales\Api\Data\OrderAddressInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;
use Lof\Gdpr\Model\Entity\DataCollectorInterface;
use Lof\Gdpr\Service\Export\Processor\AbstractDataProcessor;

final class OrderDataProcessor extends AbstractDataProcessor
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        DataCollectorInterface $dataCollector
    ) {
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        parent::__construct($dataCollector);
    }

    public function execute(int $customerId, array $data): array
    {
        $this->searchCriteriaBuilder->addFilter(OrderInterface::CUSTOMER_ID, $customerId);
        $orderList = $this->orderRepository->getList($this->searchCriteriaBuilder->create());

        /** @var Order $order */
        foreach ($orderList->getItems() as $order) {
            $key = 'order_id_' . $order->getEntityId();
            $data['orders'][$key] = $this->collectData($order);

            /** @var OrderAddressInterface|null $orderAddress */
            foreach ([$order->getBillingAddress(), $order->getShippingAddress()] as $orderAddress) {
                if ($orderAddress) {
                    $data['orders'][$key][$orderAddress->getAddressType()] = $this->collectData($orderAddress);
                }
            }
        }

        return $data;
    }
}