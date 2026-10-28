<?php
namespace App\Policies;

use App\Models\User;

/** Generic permission-gated policy: module name derived from model class. */
abstract class BaseModulePolicy
{
    protected string $module;

    public function viewAny(User $u): bool { return $u->hasPermission($this->module.'.view'); }
    public function view(User $u, mixed $m): bool { return $u->hasPermission($this->module.'.view'); }
    public function create(User $u): bool { return $u->hasPermission($this->module.'.create'); }
    public function update(User $u, mixed $m): bool { return $u->hasPermission($this->module.'.update'); }
    public function delete(User $u, mixed $m): bool { return $u->hasPermission($this->module.'.delete'); }
    public function export(User $u): bool { return $u->hasPermission($this->module.'.export'); }
}
