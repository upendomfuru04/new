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
/*User account mngnt*/
// $route['my_profile'] = 'users/my_profile';
$route['users/register'] = 'users/register';
$route['users'] = 'users/index/$1';
// $route['users/(:any)'] = 'users/index/$1';
$route['account_activation'] = 'users/account_activation';
$route['resend_activation_code'] = 'users/resend_activation_code';
$route['login'] = 'users/view/$1';
$route['logout'] = 'users/logout';

/*Seller*/
// $route['seller/vendor'] = 'seller/view/vendor';
// $route['seller/insider'] = 'seller/view/insider';
// $route['seller/outsider'] = 'seller/view/outsider';
// $route['seller/contributor'] = 'seller/view/contributor';
/*$route['seller/delete_product'] = 'seller/delete_product';
$route['seller/product_details'] = 'seller/load_product_details';*/

// $route['seller'] = 'seller/home/$1';
$route['seller/my_profile'] = 'users/my_profile';
$route['seller/logout'] = 'users/logout';
// $route['seller/(:any)'] = 'seller/view/$1';
$route['seller/(:any)'] = 'seller/home/$1';

/*Admin*/
// $route['admin/my_profile'] = 'users/my_profile';
$route['admin/logout'] = 'users/logout';
$route['admin'] = 'admin/home/index';
// $route['admin/(:any)'] = 'admin/index/$1';

/*Blog*/
$route['blog/(:any)'] = 'home/blog/$1';
$route['blog_post/(:any)'] = 'home/blog_post/$1';
$route['self_help_details/(:any)'] = 'home/self_help_details/$1';

/*Customer links*/
$route['customer'] = 'customer/customer/index/$1';
$route['customer/(:any)'] = 'customer/customer/$1';
// $route['customer/(:any)'] = 'customer/customer/$1/$1';
// $route['customer/my_profile'] = 'users/my_profile';

/*Download restrict*/
$route['products/(:any)'] = 'products/index/$1';
$route['products/(:num)/(:any)'] = "products/medias/$1/$2";

/*API ENDPOINTS*/
$route['v1/authenticate'] = 'users/my_profile';
$route['v1/orders'] = 'users/my_profile';
$route['v1/(:any)'] = 'api/index';

/*app endpoints*/
$route['app'] = 'app/app';
$route['app/user'] = 'app/user/index/$1';
$route['app/(:any)'] = 'app/app/index';
/*end of app*/

$route['api_downloads/(:any)'] = 'api_downloads/index/$1';
$route['api_downloads/(:num)/(:any)'] = "api_downloads/medias/$1/$2";

/*general*/
// $route['ebooks/(:any)'] = 'home/product_details/ebooks';
$route['search_results'] = 'home/search_results';
$route['help'] = 'home/self_help';

$route['prod/(:any)'] = 'home/product_details/$1';
$route['(:any)'] = 'home/index/$1';
$route['default_controller'] = 'home';
$route['404_override'] = 'notfound';
$route['translate_uri_dashes'] = FALSE;
