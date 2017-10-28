<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = "frontend";
$route['404_override'] = '';
//member
$route['register'] = 'member/register';
$route['login'] = 'member/login';
$route['profile'] = 'member';
$route['logout'] = 'member/logout';
$route['verify-email/(:any)'] = 'member/verify_email/$1';
$route['forgot-password'] = 'member/forgot_password';
$route['reset-password/(:any)'] = 'member/reset_password/$1';
$route['re-send-email/(:any)'] = 'member/resendemail/$1';
//orders
$route['my-orders'] = 'orders/my_orders';
$route['shopping-carts'] = 'orders/carts';
$route['checkout/delivery-info'] = 'orders/delivery';
$route['checkout/payment'] = 'orders/payment';
$route['checkout/result'] = 'orders/result';
$route['get-coupon'] = 'orders/ajax_get_coupon';
$route['checkout/confirm-order'] = 'orders/confirm_order';
$route['order/view/(:num)'] = 'orders/view/$1';
$route['order/print/(:num)'] = 'orders/print_file/$1';
$route['order/confirm-payment/(:num)'] = 'orders/confirm_payment/$1';
$route['order/document/(:num)'] = 'orders/document/$1';
$route['download/document/(:num)'] = 'orders/download_file/$1';

//admin
$route['admin'] = 'admin/index';
$route['admin/index'] = 'admin/index';
$route['admin/logout'] = 'admin/logout';
$route['admin/session'] = 'admin/chk_online';

$route['admin/(:any)'] = 'admin_ct/$1';
$route['admin/(:any)/(:any)'] = 'admin_ct/$1/$2';
$route['admin/(:any)/(:any)/(:num)'] = 'admin_ct/$1/$2/$3';


// Download PDF //
$route['pdf/(:num)-(:any)'] = 'frontend/product_pdf_download/$1';

// catalog //
$route['catalog/(:num)/(:any)/(:num)/(:any)'] = 'frontend/catalog/$1/$3';
$route['catalog/(:num)/(:any)/(:num)/(:any)/(:num)'] = 'frontend/catalog/$1/$3/$4';
$route['contact'] = 'frontend/contact';

//product
$route['ajax_check'] = 'frontend/ajax_check_product_data';
$route['product/(:num)/(:any)'] = 'frontend/product_get/$1';

// News //
$route['news'] = 'frontend/news';
$route['news/page'] = 'frontend/news/page/0';
$route['news/page/(:num)'] = 'frontend/news/page/$1';
$route['news/(:num)/(:any)'] = 'frontend/news/$1';

// Services //
$route['services/(:any)'] = 'frontend/services/$1';

$route['search'] = 'frontend/search';

$route['translate_uri_dashes'] = FALSE;
