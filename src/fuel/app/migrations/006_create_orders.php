<?php

namespace Fuel\Migrations;

class Create_orders
{
	public function up()
	{
		\DBUtil::create_table('orders', array(
			'id' => array('type' => 'int', 'unsigned' => true, 'null' => false, 'auto_increment' => true, 'constraint' => 11),
			'user_id' => array('constraint' => 11, 'unsigned' => true, 'null' => false, 'type' => 'int'),
			'customer' => array('null' => false, 'type' => 'json'),
			'created_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
			'updated_at' => array('constraint' => 11, 'null' => true, 'type' => 'int', 'unsigned' => true),
		), array('id'));

		\DB::query("
			ALTER TABLE `orders`
			ADD CONSTRAINT `fk_orders_user`
			FOREIGN KEY (`user_id`)
			REFERENCES `users`(`id`)
			ON DELETE CASCADE
			ON UPDATE CASCADE
		")->execute();
	}

	public function down()
	{
		\DBUtil::drop_table('orders');
	}
}
