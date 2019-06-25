<?php
namespace Agc\DeliveryDate\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * install tables
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @return void
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();
        $connection->addColumn(
            $installer->getTable('mgwl_sales_order'),
            'expected_delivery_date',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                'nullable' => true,
                'comment' => 'Expected',
                'after' => ''
            ]
        );
	    $connection->addColumn(
            $installer->getTable('mgwl_sales_order'),
            'actual_delivery_date',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                'nullable' => true,
                'comment' => 'Actual',
                'after' => ''
            ]
        );
        $installer->endSetup();
    }
}