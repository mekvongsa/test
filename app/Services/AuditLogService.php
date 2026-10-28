<?php
namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AuditLogService
{
    /** Fields that must NEVER be written to logs. */
    private const REDACT = ['password','password_confirmation','token','qr_token','access_token','remember_token','secret'];

    public function log(?int $userId, string $action, string $module, ?string $recordId = null,
                       ?string $description = null, array $meta = [], string $status = 'success', ?Request $req = null): ActivityLog
    {
        $clean = array_diff_key($meta, array_flip(self::REDACT));
        return ActivityLog::create([
            'user_id' => $userId,
            'action' => $action,
            'module' => $module,
            'record_id' => $recordId,
            'description' => $description,
            'meta' => $clean ?: null,
            'ip_address' => $req?->ip(),
            'user_agent' => substr((string) $req?->userAgent(), 0, 250),
            'status' => $status,
        ]);
    }
}
