<?php
namespace Lof\Gdpr\Block\Seller;

class DeleteProfile extends \Magento\Framework\View\Element\Template {

	/**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
	protected $_coreRegistry = null;
    /**
     * @var \Lof\MarketPlace\Model\Seller
     */
    protected $_sellerFactory;
    /**
     * @var \Lof\MarketPlace\Model\Data
     */
    protected $_helper;
    /**
     * @var \ResourceConnection
     */
    protected $_resource;

    /**
     * @param \Magento\Framework\View\Element\Template\Context
     * @param \Magento\Framework\Registry
     * @param \Lof\MarketPlace\Model\Seller
     * @param \Lof\MarketPlace\Helper\Data $helper
     * @param \Magento\Framework\App\ResourceConnection
     */
	public function __construct(
        \Magento\Framework\View\Element\Template\Context $context, \Magento\Framework\Registry $registry, \Lof\MarketPlace\Model\Seller $sellerFactory, \Lof\MarketPlace\Helper\Data $helper, \Magento\Framework\App\ResourceConnection $resource
        ) {
		$this->_helper        = $helper;
		$this->_coreRegistry  = $registry;
		$this->_sellerFactory = $sellerFactory;
		$this->_resource      = $resource;
        parent::__construct($context);
    }
    /**
     *  get Seller Colection
     *
     * @return Object
     */
     public function getSellerCollection(){
        $sellerCollection = $this->_sellerFactory->getCollection();
        return $sellerCollection;
    }

}
