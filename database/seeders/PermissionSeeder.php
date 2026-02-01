<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Filament\Facades\Filament;
use BezhanSalleh\FilamentShield\Facades\FilamentShield;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $panels = ['admin', 'app'];
        $actions = ['view', 'view_any', 'create', 'update', 'restore', 'restore_any', 'replicate', 'reorder', 'delete', 'delete_any', 'force_delete', 'force_delete_any'];

        foreach ($panels as $panelId) {
            $panel = Filament::getPanel($panelId);
            if (!$panel) continue;

            foreach ($panel->getResources() as $resource) {
                $identifier = FilamentShield::getPermissionIdentifier($resource);
                foreach ($actions as $action) {
                    Permission::firstOrCreate([
                        'name' => "{$action}_{$identifier}",
                        'guard_name' => 'web',
                    ]);
                }
            }

            foreach ($panel->getPages() as $page) {
                $identifier = FilamentShield::getPermissionIdentifier($page);
                Permission::firstOrCreate([
                    'name' => "page_{$identifier}",
                    'guard_name' => 'web',
                ]);
            }

            foreach ($panel->getWidgets() as $widget) {
                $identifier = FilamentShield::getPermissionIdentifier($widget);
                Permission::firstOrCreate([
                    'name' => "widget_{$identifier}",
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
