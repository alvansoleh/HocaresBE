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
$route['default_controller'] = 'welcome';

$route["login"]["POST"] = "auth/login";
$route["registration"]["POST"] = "auth/registration";

$route["profile"]["GET"] = "users/profile";
$route["profile"]["PUT"] = "users/update_profile";
$route["change_password"]["PUT"] = "users/change_password";

$route["user_list"]["GET"] = "users/list";
$route["user_detail"]["GET"] = "users/detail";
$route["user_new"]["POST"] = "users/create";
$route["user_edit"]["PUT"] = "users/update";
$route["user_delete"]["DELETE"] = "users/remove";

$route["aset_list"]["GET"] = "asset/list";
$route["aset_detail"]["GET"] = "asset/detail";
$route["aset_new"]["POST"] = "asset/create";
$route["aset_edit"]["PUT"] = "asset/update";
$route["aset_delete"]["DELETE"] = "asset/remove";

$route["used_aset_list"]["GET"] = "asset/list_used";
$route["used_aset_list_all"]["GET"] = "asset/list_used_all";
$route["used_aset_in"]["POST"] = "asset/create_used_in";
$route["used_aset_out"]["POST"] = "asset/create_used_out";
$route["close_used_aset_out"]["POST"] = "asset/close_used_out";

$route["mapping_aset_list"]["GET"] = "asset/list_mapping";
$route["mapping_aset_new"]["POST"] = "asset/create_mapping";
$route["mapping_aset_delete"]["DELETE"] = "asset/remove_mapping";

$route["gedung_list"]["GET"] = "gedung/list";
$route["gedung_detail"]["GET"] = "gedung/detail";
$route["gedung_new"]["POST"] = "gedung/create";
$route["gedung_edit"]["PUT"] = "gedung/update";
$route["gedung_delete"]["DELETE"] = "gedung/remove";

$route["lantai_list"]["GET"] = "lantai/list";
$route["lantai_gedung_list"]["GET"] = "lantai/gedung_list";
$route["lantai_detail"]["GET"] = "lantai/detail";
$route["lantai_new"]["POST"] = "lantai/create";
$route["lantai_edit"]["PUT"] = "lantai/update";
$route["lantai_delete"]["DELETE"] = "lantai/remove";

$route["ruangan_list"]["GET"] = "ruangan/list";
$route["ruangan_lantai_list"]["GET"] = "ruangan/lantai_list";
$route["ruangan_detail"]["GET"] = "ruangan/detail";
$route["ruangan_new"]["POST"] = "ruangan/create";
$route["ruangan_edit"]["PUT"] = "ruangan/update";
$route["ruangan_delete"]["DELETE"] = "ruangan/remove";

$route["schedule_list"]["GET"] = "schedule/list";
$route["schedule_detail"]["GET"] = "schedule/detail";
$route["schedule_new"]["POST"] = "schedule/create";
$route["schedule_edit"]["PUT"] = "schedule/update";
$route["schedule_delete"]["DELETE"] = "schedule/remove";

$route["count_place_report"]["GET"] = "report/count_of_place";
$route["download_place_building_report"]["GET"] = "report/download_of_place_building";
$route["download_place_floor_report"]["GET"] = "report/download_of_place_floor";
$route["download_place_room_report"]["GET"] = "report/download_of_place_room";
$route["count_aset_report"]["GET"] = "report/count_of_aset";
$route["download_aset_report"]["GET"] = "report/download_of_aset";
$route["count_aset_transaction_report"]["GET"] = "report/count_of_aset_transaction";
$route["download_aset_transaction_report"]["GET"] = "report/download_of_aset_transaction";
$route["count_schedule_report"]["GET"] = "report/count_of_schedule";
$route["download_schedule_report"]["GET"] = "report/download_of_schedule";
$route["print_label_asset"]["GET"] = "asset/download_label_asset";

$route["menu_list"]["GET"] = "menu/list";
$route["menu_new"]["POST"] = "menu/create";
$route["menu_edit"]["PUT"] = "menu/update";
$route["menu_delete"]["DELETE"] = "menu/remove";

$route["user_access_menu_list"]["GET"] = "access/list";
$route["user_access_menu_list_by_id"]["GET"] = "access/list_by_id";
$route["user_access_menu_new"]["POST"] = "access/create";
$route["user_access_menu_edit"]["PUT"] = "access/update";
$route["user_access_menu_delete"]["DELETE"] = "access/remove";

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
