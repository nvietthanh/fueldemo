<?php

namespace Fuel\Migrations;

class Create_order_products
{
	public function up()
	{
		\DBUtil::create_table('order_products', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'order_id' => array('constraint' => 11, 'unsigned' => true, 'null' => false, 'type' => 'int'),
			'product_id' => array('constraint' => 11, 'unsigned' => true, 'null' => false, 'type' => 'int'),
			'name' => array('constraint' => 255, 'null' => false, 'type' => 'varchar'),
			'quantity' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'price' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));

		\DB::query("
			ALTER TABLE `order_products`
			ADD CONSTRAINT `fk_order_products_order`
			FOREIGN KEY (`order_id`)
			REFERENCES `orders`(`id`)
			ON DELETE CASCADE
			ON UPDATE CASCADE
		")->execute();
		\DB::query("
			ALTER TABLE `order_products`
			ADD CONSTRAINT `fk_order_products_product`
			FOREIGN KEY (`product_id`)
			REFERENCES `products`(`id`)
			ON DELETE CASCADE
			ON UPDATE CASCADE
		")->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('order_products');
	}
}
