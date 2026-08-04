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
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'home';
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
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Unified authentication routes
$route['login'] = 'customer/auth/login';
$route['admin/login'] = 'customer/auth/login';
$route['admin/logout'] = 'admin/auth/logout';
$route['admin/auth/verify'] = 'admin/auth/verify';
$route['admin/dashboard'] = 'admin/dashboard';
$route['admin/products'] = 'admin/products';
$route['admin/products/create'] = 'admin/products/create';
$route['admin/products/edit/(:num)'] = 'admin/products/edit/$1';
$route['admin/products/delete/(:num)'] = 'admin/products/delete/$1';
$route['admin/categories'] = 'admin/categories';
$route['admin/categories/create'] = 'admin/categories/create';
$route['admin/categories/edit/(:num)'] = 'admin/categories/edit/$1';
$route['admin/categories/delete/(:num)'] = 'admin/categories/delete/$1';
$route['admin/users'] = 'admin/users';
$route['admin/users/create'] = 'admin/users/create';
$route['admin/users/edit/(:num)'] = 'admin/users/edit/$1';
$route['admin/users/delete/(:num)'] = 'admin/users/delete/$1';
$route['admin/pos'] = 'admin/pos';
$route['admin/pos/process'] = 'admin/pos/process';
$route['admin/pos/print_receipt/(:num)'] = 'admin/pos/print_receipt/$1';
$route['admin/transactions'] = 'admin/transactions';
$route['admin/transactions/detail/(:num)'] = 'admin/transactions/detail/$1';
$route['admin/transactions/verify/(:num)'] = 'admin/transactions/verify/$1';
$route['admin/customers'] = 'admin/customers';
$route['admin/customers/detail/(:num)'] = 'admin/customers/detail/$1';
$route['admin/feedback'] = 'admin/feedback';
$route['admin/feedback/reply/(:num)'] = 'admin/feedback/reply/$1';
$route['admin/discounts'] = 'admin/discounts';
$route['admin/discounts/create'] = 'admin/discounts/create';
$route['admin/discounts/edit/(:num)'] = 'admin/discounts/edit/$1';
$route['admin/discounts/delete/(:num)'] = 'admin/discounts/delete/$1';
$route['admin/crm'] = 'admin/crm';
$route['admin/crm/passive'] = 'admin/crm/passive';
$route['admin/crm/loyalty'] = 'admin/crm/loyalty';

// Customer routes
$route['customer/login'] = 'customer/auth/login';
$route['customer/auth/login_process'] = 'customer/auth/login_process';
$route['customer/auth/google'] = 'customer/auth/google';
$route['customer/auth/callback'] = 'customer/auth/google_callback';
$route['customer/logout'] = 'customer/auth/logout';
$route['customer/dashboard'] = 'customer/dashboard';
$route['customer/shop'] = 'customer/shop';
$route['customer/cart'] = 'customer/cart';
$route['customer/cart/add/(:num)'] = 'customer/cart/add/$1';
$route['customer/cart/remove/(:num)'] = 'customer/cart/remove/$1';
$route['customer/cart/update'] = 'customer/cart/update';
$route['customer/checkout'] = 'customer/checkout';
$route['customer/orders'] = 'customer/orders';
$route['customer/orders/detail/(:num)'] = 'customer/orders/detail/$1';
$route['customer/orders/upload_proof/(:num)'] = 'customer/orders/upload_proof/$1';
$route['customer/feedback/create/(:num)'] = 'customer/feedback/create/$1';
