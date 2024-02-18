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

/* MAIN ROUTES */
$route['default_controller'] = 'homepage/Homepage';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;


/**
 * ==========================
 *
 * LANDING PAGE ROUTES
 *
 * ==========================
 */

/* fe routes*/
$route['homepage'] 					= 'homepage/Homepage';
$route['tentang-kami'] 				= 'homepage/About';
$route['visi-misi'] 				= 'homepage/Vision';
$route['ad-art'] 					= 'homepage/Regulations';
$route['struktur-organisasi'] 		= 'homepage/Organizations';
$route['program-koperasi'] 			= 'homepage/Programs';
/* end of fe routes*/

/**
 * ==========================
 *
 * AUTHENTICATION ROUTES
 *
 * ==========================
 */

// Admin Auth
$route['pengurus/login']							= 'authentication/AdminAuth/login';
$route['pengurus/authentication']					= 'authentication/AdminAuth/authentication';
$route['pengurus/verify']							= 'authentication/AdminAuth/verify';
$route['pengurus/logout']							= 'authentication/AdminAuth/logout';
$route['pengurus/profile/password']					= 'authentication/AdminAuth/password';
$route['pengurus/profile/password/update']			= 'authentication/AdminAuth/passwordUpdate';
$route['pengurus/notification/setStatus/(:num)'] 	= 'authentication/AdminAuth/setNotificationOpened/$1';

// Member Auth
$route['member/login']						= 'authentication/MemberAuth/login';
$route['member/authentication']				= 'authentication/MemberAuth/authentication';
$route['member/register']					= 'authentication/MemberAuth/register';
$route['member/verify']						= 'authentication/MemberAuth/verify';
$route['member/logout']						= 'authentication/MemberAuth/logout';
$route['member/profile/password']			= 'authentication/MemberAuth/password';
$route['member/profile/password/update']	= 'authentication/MemberAuth/passwordUpdate';
$route['member/notification/setStatus/(:num)'] 	= 'authentication/MemberAuth/setNotificationOpened/$1';

/**
 * ==========================
 *
 * ADMIN ROUTES
 *
 * ==========================
 */

/* Admin */
$route['admin/dashboard'] 	= 'admin/Admin/index';

// Admin Keanggotaan
$route['admin/keanggotaan/index'] 		= 'admin/operasional/AdminMember/index';
$route['admin/keanggotaan/create']		= 'admin/operasional/AdminMember/create';
$route['admin/keanggotaan/save']		= 'admin/operasional/AdminMember/save';
$route['admin/keanggotaan/edit/(:num)']	= 'admin/operasional/AdminMember/edit/$1';
$route['admin/keanggotaan/update']		= 'admin/operasional/AdminMember/update';
$route['admin/keanggotaan/report']		= 'admin/operasional/AdminMember/report';

// Admin Simpanan Content
$route['admin/simpanan/content/index']			= 'admin/operasional/AdminSimpananContent/index';
$route['admin/simpanan/content/create']			= 'admin/operasional/AdminSimpananContent/create';
$route['admin/simpanan/content/save']			= 'admin/operasional/AdminSimpananContent/save';
$route['admin/simpanan/content/edit/(:num)']	= 'admin/operasional/AdminSimpananContent/edit/$1';
$route['admin/simpanan/content/update']			= 'admin/operasional/AdminSimpananContent/update';

// Admin Simpanan Member
$route['admin/simpanan/anggota/index']			= 'admin/operasional/AdminSimpananAnggota/index'; //undone
$route['admin/simpanan/anggota/edit/(:num)']	= 'admin/operasional/AdminSimpananAnggota/edit/$1'; //undone
$route['admin/simpanan/anggota/update']			= 'admin/operasional/AdminSimpananAnggota/update'; //undone

// Admin Laporan Simpanan Anggota
$route['admin/simpanan/laporan/index']			= 'admin/operasional/AdminSimpananReport/index';
$route['admin/simpanan/laporan/show/(:any)']	= 'admin/operasional/AdminSimpananReport/show/$1';

// Admin Pinjaman Content
$route['admin/pinjaman/content/index']			= 'admin/operasional/AdminPinjamanContent/index';
$route['admin/pinjaman/content/create']			= 'admin/operasional/AdminPinjamanContent/create';
$route['admin/pinjaman/content/save']			= 'admin/operasional/AdminPinjamanContent/save';
$route['admin/pinjaman/content/edit/(:num)']	= 'admin/operasional/AdminPinjamanContent/edit/$1';
$route['admin/pinjaman/content/update']			= 'admin/operasional/AdminPinjamanContent/update';

// Admin Pinjaman Anggota
$route['admin/pinjaman/permohonan-pinjaman']		= 'admin/operasional/AdminPinjamanAnggota/index';
$route['admin/pinjaman/permohonan-pinjaman/update']	= 'admin/operasional/AdminPinjamanAnggota/update';
$route['admin/pinjaman/semua-pinjaman']				= 'admin/operasional/AdminPinjamanAnggota/index';
$route['admin/pinjaman/detail/(:num)']				= 'admin/operasional/AdminPinjamanAnggota/show/$1';
$route['admin/pinjaman/pembayaran-pinjaman']		= 'admin/operasional/AdminPinjamanAnggota/index';

// Admin Tabungan Anggota
$route['admin/tabungan/pengaturan-program']			= 'admin/operasional/AdminTabunganAnggotaSetting/index';
$route['admin/tabungan/pengaturan-program/update']	= 'admin/operasional/AdminTabunganAnggotaSetting/update';
$route['admin/tabungan/anggota']					= 'admin/operasional/AdminTabunganAnggota/index';
$route['admin/tabungan/anggota/update']				= 'admin/operasional/AdminTabunganAnggota/update';
$route['admin/tabungan/transfer-tabungan']			= 'admin/operasional/AdminTabunganAnggotaTf/index';
$route['admin/tabungan/transfer-tabungan/update']	= 'admin/operasional/AdminTabunganAnggotaTf/update';
$route['admin/tabungan/laporan']					= 'admin/operasional/AdminTabunganAnggotaReport/index';

// Admin Deposit
$route['admin/deposit/(:any)']	= 'admin/operasional/AdminDeposit/index';
$route['admin/deposit-update']	= 'admin/operasional/AdminDeposit/update';

// Admin Withdrawal
$route['admin/withdrawal/(:any)']	= 'admin/operasional/AdminWithdrawal/index';
$route['admin/withdrawal-update']	= 'admin/operasional/AdminWithdrawal/update';

// Admin Olshop
$route['admin/olshop/index']		= 'admin/operasional/AdminOlshop/index';
$route['admin/olshop/create']		= 'admin/operasional/AdminOlshop/create';
$route['admin/olshop/save']			= 'admin/operasional/AdminOlshop/save';
$route['admin/olshop/edit/(:num)']	= 'admin/operasional/AdminOlshop/edit/$1';
$route['admin/olshop/update']		= 'admin/operasional/AdminOlshop/update';

// Admin Olshop Checkout
$route['admin/checkout/product'] 		= 'admin/operasional/AdminOlshopCheckoutProduct/index';
$route['admin/checkout/product/update']	= 'admin/operasional/AdminOlshopCheckoutProduct/update';

// Admin Pengurus
$route['admin/pengurus/index']			= 'admin/setting/AdminPengurus/index';
$route['admin/pengurus/create']			= 'admin/setting/AdminPengurus/create';
$route['admin/pengurus/save']			= 'admin/setting/AdminPengurus/save';
$route['admin/pengurus/edit/(:num)']	= 'admin/setting/AdminPengurus/edit/$1';
$route['admin/pengurus/update']			= 'admin/setting/AdminPengurus/update';

// Admin Setting
$route['admin/setting/basic'] 						= 'admin/setting/AdminSiteSetting/index';
$route['admin/setting/basic/save'] 					= 'admin/setting/AdminSiteSetting/save';
$route['admin/setting/basic/edit/(:any)/(:num)'] 	= 'admin/setting/AdminSiteSetting/edit';
$route['admin/setting/company'] 					= 'admin/setting/AdminCompanySetting/index';
$route['admin/setting/company/save'] 				= 'admin/setting/AdminCompanySetting/save';


// Admin Account
$route['admin-profile'] 				= 'admin/account/AdminProfile/index';
$route['admin-profile/edit/(:num)']		= 'admin/account/AdminProfile/edit/$1';
$route['admin-profile/update']			= 'admin/account/AdminProfile/update';

/* ./end of Admin routes */


/**
 * ==========================
 *
 * MEMBER ROUTES
 *
 * ==========================
 */

/* Member */
$route['member/dashboard'] 			= 'member/Member/index';

// Keanggotaan
$route['member/keanggotaan/index']	= 'member/team/MemberTeam/index';
$route['member/keanggotaan/save']	= 'member/team/MemberTeam/save';
$route['member/keanggotaan/aturan']	= 'member/team/MemberRegulation/index';
$route['member/keanggotaan/ad-art']	= 'member/team/MemberRegulation/index';

// Deposit
$route['member-deposit']						= 'member/finance/Deposit/index';
$route['member-deposit/save']					= 'member/finance/Deposit/save';

// Simpanan
$route['member/simpanan/index']					= 'member/finance/Simpanan/index';
$route['member/simpanan/save']					= 'member/finance/Simpanan/save';
$route['member/simpanan/program-funding/index']	= 'member/finance/Funding/index';
$route['member/simpanan/program-funding/save']	= 'member/finance/Funding/save';

// Pinjaman
$route['member/pinjaman/summary']				= 'member/finance/Pinjaman/index';
$route['member/pinjaman/pengajuan']				= 'member/finance/Pinjaman/create';
$route['member/pinjaman/pengajuan/save']		= 'member/finance/Pinjaman/save';
$route['member/pinjaman/pembayaran']			= 'member/finance/Pinjaman/index';
$route['member/pinjaman/pembayaran/update']		= 'member/finance/Pinjaman/update';
$route['member/pinjaman/laporan/pembayaran']	= 'member/finance/Pinjaman/index';

// Tabungan
$route['member/tabungan/summary']					= 'member/finance/Tabungan/index';
$route['member/tabungan/buka-tabungan']				= 'member/finance/Tabungan/create';
$route['member/tabungan/buka-tabungan/save']		= 'member/finance/Tabungan/save';
$route['member/tabungan/daftar-pemindahan']			= 'member/finance/TabunganTf/index';
$route['member/tabungan/pengajuan-pemindahan']		= 'member/finance/TabunganTf/create';
$route['member/tabungan/pengajuan-pemindahan/save']	= 'member/finance/TabunganTf/save';
$route['member/tabungan/laporan']					= 'member/finance/TabunganReport/index';

// Withdrawal
$route['member/withdrawal/index']		= 'member/finance/Withdrawal/index';
$route['member/withdrawal/save']		= 'member/finance/Withdrawal/save';

// Online Shop
$route['member/onlineShop/index']	= 'member/onlineShop/MemberOnlineShop/index';
$route['member/onlineShop/save']	= 'member/onlineShop/MemberOnlineShop/save';

// Olshop Checkout
$route['member/checkout/product'] 			= 'member/onlineShop/MemberOlshopCheckoutProduct/index';

// Account
$route['member-profile'] 				= 'member/account/MyProfile/index';
$route['member-profile/edit/(:num)']	= 'member/account/MyProfile/edit/$1';
$route['member-profile/update']			= 'member/account/MyProfile/update';

/* ./end oF Member Routes*/

/* /Member Testing Routes*/
$route['cronJobs/index'] 						= 'cronJobs/Jobs/index';
$route['cronJobs/imbal-jasa/simpanan/jobs']		= 'CronJobs/Jobs/doJobsSimpananMemberImbalJasa';
$route['cronJobs/imbal-jasa/tabungan/jobs'] 	= 'cronJobs/Jobs/doJobsTabunganImbalJasa';
$route['cronJobs/simpanan/expired-tenor/jobs']	= 'CronJobs/Jobs/doJobsSimpananExpiredTenor';
