<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * RBAC seed — idempotent (safe to re-run).
 */
class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            'dashboard' => 'ໜ້າຫຼັກ',
            'departments' => 'ພາກວິຊາ',
            'academic-years' => 'ສົກຮຽນ',
            'semesters' => 'ພາກຮຽນ',
            'programs' => 'ຫຼັກສູດ/ສາຂາວິຊາ',
            'courses' => 'ວິຊາຮຽນ',
            'classes' => 'ຫ້ອງຮຽນ',
            'students' => 'ນັກສຶກສາ',
            'teachers' => 'ອາຈານ-ພະນັກງານ',
            'teaching-assignments' => 'ການມອບໝາຍສອນ',
            'lessons' => 'ບົດຮຽນ',
            'attendance' => 'ການເຊັກຊື່',
            'grades' => 'ຄະແນນ',
            'documents' => 'ເອກະສານ-ສື່',
            'reports' => 'ລາຍງານ',
            'users' => 'ຜູ້ໃຊ້',
            'roles' => 'ບົດບາດ-ສິດ',
            'audit-logs' => 'Audit Logs',
            'backups' => 'ສຳຮອງຂໍ້ມູນ',
            'announcements' => 'ຂ່າວສານ-ປະກາດ',
        ];

        $actions = ['view' => 'ເບິ່ງ', 'create' => 'ສ້າງ', 'update' => 'ແກ້ໄຂ', 'delete' => 'ລຶບ', 'approve' => 'ອະນຸມັດ', 'export' => 'ສົ່ງອອກ'];

        foreach ($modules as $mod => $lo) {
            foreach ($actions as $act => $actLo) {
                Permission::updateOrCreate(
                    ['name' => "$mod.$act"],
                    ['display_name_lo' => "$actLo$lo", 'module' => $mod]
                );
            }
        }

        $all = Permission::pluck('id')->all();

        $roleDefs = [
            'super-admin' => ['display' => 'ຜູ້管理ສູງສຸດ', 'perms' => 'all'],
            'administrator' => ['display' => 'ຜູ້ບໍລິຫານ', 'except' => []],
            'head-of-department' => ['display' => 'ຫົວໜ້າພາກວິຊາ', 'only_modules' => ['dashboard','departments','academic-years','semesters','programs','courses','classes','students','teachers','teaching-assignments','lessons','attendance','grades','documents','reports','announcements']],
            'teacher' => ['display' => 'ອາຈານ', 'only_modules' => ['dashboard','lessons','attendance','grades','documents','reports']],
            'staff' => ['display' => 'ພະນັກງານ', 'only_modules' => ['dashboard','students','teachers','documents','reports','announcements']],
            'student' => ['display' => 'ນັກສຶກສາ', 'custom' => [
                'dashboard.view', 'attendance.view', 'grades.view', 'documents.view', 'documents.create', 'announcements.view', 'lessons.view',
            ]],
        ];

        foreach ($roleDefs as $name => $def) {
            $role = Role::updateOrCreate(['name' => $name], ['display_name_lo' => $def['display']]);

            if ($name === 'super-admin') {
                $role->permissions()->sync($all);
                continue;
            }
            if (isset($def['custom'])) {
                $ids = Permission::whereIn('name', $def['custom'])->pluck('id')->all();
                $role->permissions()->sync($ids);
                continue;
            }
            $q = Permission::query();
            if (isset($def['only_modules'])) {
                $q->whereIn('module', $def['only_modules']);
            }
            $role->permissions()->sync($q->pluck('id')->all());
        }
    }
}
