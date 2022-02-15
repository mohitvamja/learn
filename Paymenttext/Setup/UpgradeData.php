<?php
namespace Sale\Paymenttext\Setup;

use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Quote\Setup\QuoteSetupFactory;
use Magento\Sales\Setup\SalesSetupFactory;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var QuoteSetupFactory
     */
    protected $quoteSetupFactory;

    /**
     * @var SalesSetupFactory
     */
    protected $salesSetupFactory;

    /**
     * @var EavSetupFactory
     */
    protected $eavSetupFactory;

    /**
     * Constructor
     */
    public function __construct(
        QuoteSetupFactory $quoteSetupFactory,
        SalesSetupFactory $salesSetupFactory,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->quoteSetupFactory = $quoteSetupFactory;
        $this->salesSetupFactory = $salesSetupFactory;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(
        ModuleDataSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {

            /** @var \Magento\Quote\Setup\QuoteSetup $quoteInstaller */
            $quoteInstaller = $this->quoteSetupFactory->create(['resourceName' => 'quote_setup', 'setup' => $setup]);

            /** @var \Magento\Sales\Setup\SalesSetup $salesInstaller */
            $salesInstaller = $this->salesSetupFactory->create(['resourceName' => 'sales_setup', 'setup' => $setup]);

            //Add attributes to quote
            $entityAttributesCodes = [
                'bank_name' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            ];

            foreach ($entityAttributesCodes as $code => $type) {
                $quoteInstaller->addAttribute('quote', $code, ['type' => $type, 'length' => 255, 'visible' => true, 'nullable' => true,]);
                $salesInstaller->addAttribute('order', $code, ['type' => $type, 'length' => 255, 'visible' => true, 'nullable' => true,]);
        }
        $setup->endSetup();
    }
}
}