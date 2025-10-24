<?php

namespace App\Traits;

use App\Models\AuditLog;

trait LogsActions
{
    protected function logAction(string $action, string $entity, int $id = null, array $data = []): void
    {
        AuditLog::create([
            'action'      => $action,
            'entity_type' => $entity,
            'entity_id'   => $id,
            'data'        => $data,
            'ip'          => request()->ip(),
        ]);
    }
}
