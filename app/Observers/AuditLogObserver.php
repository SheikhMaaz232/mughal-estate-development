<?php

namespace App\Observers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLogObserver
{
    public function created(Model $model)
    {
        $this->logAction('created', $model);
    }

    public function updated(Model $model)
    {
        $this->logAction('updated', $model, $model->getDirty());
    }

    public function deleted(Model $model)
    {
        $this->logAction('deleted', $model);
    }

    protected function logAction(string $action, Model $model, array $changes = [])
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'changes' => $changes,
        ]);
    }
}
