<?php

namespace Fuel\Migrations;

class Create_carts
{
	public function up()
	{
		\DBUtil::create_table('carts', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'user_id' => array('constraint' => 11, 'unsigned' => true, 'null' => false, 'type' => 'int'),
			'product_id' => array('constraint' => 11, 'unsigned' => true, 'null' => false, 'type' => 'int'),
			'quantity' => array('constraint' => 11, 'null' => false, 'type' => 'int'),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));

		\DB::query("CREATE INDEX idx_carts_user_id ON carts(user_id)")->execute();
		\DB::query("CREATE INDEX idx_carts_product_id ON carts(product_id)")->execute();

		\DB::query("
			ALTER TABLE `carts`
			ADD CONSTRAINT `fk_carts_user`
			FOREIGN KEY (`user_id`)
			REFERENCES `users`(`id`)
			ON DELETE CASCADE
			ON UPDATE CASCADE
		")->execute();
		\DB::query("
			ALTER TABLE `carts`
			ADD CONSTRAINT `fk_carts_product`
			FOREIGN KEY (`product_id`)
			REFERENCES `products`(`id`)
			ON DELETE CASCADE
			ON UPDATE CASCADE
		")->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('carts');
	}
}
