<?php
/**
 * Copyright © Landofcoder, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Lof\Gdpr\Cron;

use Exception;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Lof\Gdpr\Api\Data\ExportEntityInterface;
use Lof\Gdpr\Api\ExportEntityManagementInterface;
use Lof\Gdpr\Api\ExportEntityRepositoryInterface;
use Lof\Gdpr\Model\Config;
use Psr\Log\LoggerInterface;

/**
 * Export all scheduled entities
 */
final class ExportEntity
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var ExportEntityRepositoryInterface
     */
    private $exportEntityRepository;

    /**
     * @var ExportEntityManagementInterface
     */
    private $exportEntityManagement;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    public function __construct(
        LoggerInterface $logger,
        Config $config,
        ExportEntityRepositoryInterface $exportEntityRepository,
        ExportEntityManagementInterface $exportEntityManagement,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->logger = $logger;
        $this->config = $config;
        $this->exportEntityRepository = $exportEntityRepository;
        $this->exportEntityManagement = $exportEntityManagement;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function execute(): void
    {
        if ($this->config->isModuleEnabled() && $this->config->isExportEnabled()) {
            $this->searchCriteriaBuilder->addFilter(ExportEntityInterface::EXPORTED_AT, true, 'null');
            $this->searchCriteriaBuilder->addFilter(ExportEntityInterface::FILE_PATH, true, 'null');

            try {
                $exportList = $this->exportEntityRepository->getList($this->searchCriteriaBuilder->create());

                foreach ($exportList->getItems() as $exportEntity) {
                    $this->exportEntityManagement->export($exportEntity);
                }
            } catch (Exception $e) {
                $this->logger->error($e->getMessage(), $e->getTrace());
            }
        }
    }
}
