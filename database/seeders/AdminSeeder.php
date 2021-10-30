<?php

namespace Database\Seeders;

use App\Models\User;
use Encore\Admin\Auth\Database\Menu;
use Encore\Admin\Auth\Database\Permission;
use Encore\Admin\Auth\Database\Role;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        optional(User::query()->firstOrCreate([
            'username' => 'admin',
        ], [
            'password' => bcrypt('password'),
        ]) ?? null, function (User $admin) {
            $role = optional(Role::query()->firstOrCreate([
                'slug' => 'administrator',
            ], [
                'name' => 'Administrator',
            ]) ?? null, function (Role $role) {
                return $role;
            });
            $admin->roles()->save($role);

            $permissions = $admin->permissions()->createMany([
                [
                    'name'        => 'All permission',
                    'slug'        => '*',
                    'http_method' => '',
                    'http_path'   => '*',
                ],
                [
                    'name'        => 'Dashboard',
                    'slug'        => 'dashboard',
                    'http_method' => 'GET',
                    'http_path'   => '/',
                ],
                [
                    'name'        => 'Login',
                    'slug'        => 'auth.login',
                    'http_method' => '',
                    'http_path'   => "/auth/login\r\n/auth/logout",
                ],
                [
                    'name'        => 'User setting',
                    'slug'        => 'auth.setting',
                    'http_method' => 'GET,PUT',
                    'http_path'   => '/auth/setting',
                ],
                [
                    'name'        => 'Auth management',
                    'slug'        => 'auth.management',
                    'http_method' => '',
                    'http_path'   => "/auth/roles\r\n/auth/permissions\r\n/auth/menu\r\n/auth/logs",
                ],
            ]);

            optional($permissions[0] ?? null, function (Permission  $permission) use ($role) {
                $role->permissions()->save($permission);
            });

            $menu = Menu::query()->insert([
                [
                'parent_id' => 0,
                'order'     => 1,
                'title'     => 'Dashboard',
                'icon'      => 'fa-bar-chart',
                'uri'       => '/',
            ],
                [
                    'parent_id' => 0,
                    'order'     => 2,
                    'title'     => 'Admin',
                    'icon'      => 'fa-tasks',
                    'uri'       => '',
                ],
                [
                    'parent_id' => 2,
                    'order'     => 3,
                    'title'     => 'Users',
                    'icon'      => 'fa-users',
                    'uri'       => 'auth/users',
                ],
                [
                    'parent_id' => 2,
                    'order'     => 4,
                    'title'     => 'Roles',
                    'icon'      => 'fa-user',
                    'uri'       => 'auth/roles',
                ],
                [
                    'parent_id' => 2,
                    'order'     => 5,
                    'title'     => 'Permission',
                    'icon'      => 'fa-ban',
                    'uri'       => 'auth/permissions',
                ],
                [
                    'parent_id' => 2,
                    'order'     => 6,
                    'title'     => 'Menu',
                    'icon'      => 'fa-bars',
                    'uri'       => 'auth/menu',
                ],
                [
                    'parent_id' => 2,
                    'order'     => 7,
                    'title'     => 'Operation log',
                    'icon'      => 'fa-history',
                    'uri'       => 'auth/logs',
                ],
            ]);
            optional(Menu::query()->find(2) ?? null, function (Menu $menu) use ($role) {
                $menu->roles()->save($role);
            });
        });
    }
}
