<?php

namespace Motomedialab\SimpleLaravelAudit\Observers;

use Illuminate\Database\Eloquent\Model;
use Motomedialab\SimpleLaravelAudit\Facades\AuditFacade;

class AuditableModelObserver
{
    public function created(Model $model): void
    {
        $this->auditMessage('Created', $model, $model->getAttributes());
    }

    public function updated(Model $model): void
    {
        $new = $model->getChanges();
        $old = array_filter($model->getOriginal(), fn ($key) => array_key_exists($key, $new), ARRAY_FILTER_USE_KEY);

        $this->auditMessage('Updated', $model, compact('old', 'new'));
    }

    public function deleted(Model $model): void
    {
        $hasSoftDeletes = method_exists($model, 'trashed') && method_exists($model, 'isForceDeleting');

        $action = match (true) {
            $hasSoftDeletes && $model->isForceDeleting() => 'Force Deleted',
            $hasSoftDeletes && $model->trashed() => 'Soft Deleted',
            default => 'Deleted',
        };

        $this->auditMessage($action, $model);
    }

    protected function auditMessage(string $action, Model $model, array $context = []): void
    {
        AuditFacade::record(class_basename($model) . ' ' . $action, [
            ...$context,
            'class' => get_class($model),
            'id' => $model->getKey(),
        ]);
    }

}
