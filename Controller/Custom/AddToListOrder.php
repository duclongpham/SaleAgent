<?php

namespace AHT\SaleAgent\Controller\Custom;

class AddToListOrder extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    /**
     * @param \Magento\Framework\Controller\Result\JsonFactory
     */
    private $_jsonFactory;

    /**
     * @param \Magento\Framework\Serialize\Serializer\Json
     */
    private $_json;

    /**
     * @param \AHT\SaleAgent\Helper\Data
     */
    private $_helperData;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $jsonFactory,
        \Magento\Framework\Serialize\Serializer\Json $_json,
        \AHT\SaleAgent\Helper\Data $helperData
    ) {

        $this->_pageFactory = $pageFactory;
        $this->_jsonFactory = $jsonFactory;
        $this->_json = $_json;
        $this->_helperData = $helperData;
        return parent::__construct($context);
    }
    /**
     * View page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->_json->unserialize(
            $this->getRequest()->getContent()
        );
        $result = $this->_helperData->saveListOrder(
            $data['action'],
            $data['id'],
            $data['qty']
        );
        $resultJson = $this->_jsonFactory->create();
        return $resultJson->setData($result);
    }
}
