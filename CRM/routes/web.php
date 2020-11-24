<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Use Illuminate\Support\Facades\Route;

//登录
Route::get('/login', 'AuthController@showLoginForm')->name('login');
Route::post('/doLogin', 'AuthController@doLogin');
//首页
Route::get('/home', 'HomeController@dashboard');

Route::group(['middleware' => ['auth', 'permission']], function () {

    Route::get('logout', 'AuthController@logout');
//Auth::routes();

    /*
     * ========================
     *  账号管理
     * ========================
     */
    Route::get('/admin/index', 'AdminController@index');
    Route::get('/admin/lists', 'AdminController@lists');
    Route::get('/admin/info', 'AdminController@info');
    Route::post('/admin/save', 'AdminController@save');
    Route::post('/admin/saveStatus', 'AdminController@saveAdminStatus');

    /*
     * ========================
     *  角色管理
     * ========================
     */
    Route::get('/role/index', 'RoleController@index');
    Route::get('/role/lists', 'RoleController@lists');
    Route::get('/role/info', 'RoleController@info');
    Route::post('/role/save', 'RoleController@save');
    Route::post('/role/savePermission', 'RoleController@savePermission');
    Route::post('/role/saveStatus', 'RoleController@saveRoleStatus');

    /*
     * ========================
     *  部门管理
     * ========================
     */
    Route::get('/dep/index', 'DepartmentController@index');
    Route::get('/dep/lists', 'DepartmentController@lists');
    Route::get('/dep/info', 'DepartmentController@info');
    Route::post('/dep/save', 'DepartmentController@save');
    Route::post('/dep/saveStatus', 'DepartmentController@saveDepStatus');

    /*
     * ========================
     *  权限管理
     * ========================
     */
    Route::get('/permission/index', 'PermissionController@index');
    Route::get('/permission/lists', 'PermissionController@lists');
    //角色分配权限列表
    Route::get('/permission/rolePermission', 'PermissionController@rolePermission');
    //权限添加
    Route::post('/permission/save', 'PermissionController@save');
});