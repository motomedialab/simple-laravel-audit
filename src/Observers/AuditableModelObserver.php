<?php

namespace Motomedialab\SimpleLaravelAudit\Observers;

use Illuminate\Database\Eloquent\Model;
use Motomedialab\SimpleLaravelAudit\Facades\AuditFacade;

class AuditableModelObserver
{
    public function created(Model $model): void
    {
        $attributes = $this->filterExcludedColumns($model, $model->getAttributes());

        $this->recordAudit($model, 'Created', $attributes);
    }

    public function updated(Model $model): void
    {
        $new = $this->filterExcludedColumns($model, $model->getChanges());

        if (empty($new)) {
            return;
        }

        $old = array_intersect_key($model->getRawOriginal(), $new);

        $this->recordAudit($model, 'Updated', compact('old', 'new'));
    }

    protected function recordAudit(Model $model, string $action, array $context = []): void
    {
        $message = method_exists($model, 'getAuditMessage')
            ? $model->getAuditMessage($action)
            : class_basename($model) . ' ' . $action;

        AuditFacade::audit($message, [
            'id' => $model->getKey(),
            'class' => $model::class,
            ...$context,
        ]);
    }

    public function deleted(Model $model): void
    {
        $hasSoftDeletes = method_exists($model, 'trashed') && method_exists($model, 'isForceDeleting');

        $action = match (true) {
            $hasSoftDeletes && $model->isForceDeleting() => 'Force Deleted',
            $hasSoftDeletes && $model->trashed() => 'Soft Deleted',
            default => 'Deleted',
        };

        $this->recordAudit($model, $action);
    }

    protected function filterExcludedColumns(Model $model, array $attributes): array
    {
        if (!method_exists($model, 'getExcludedFromAuditing')) {
            return $attributes;
        }

        $excludedColumns = $model->getExcludedFromAuditing();

        if ($excludedColumns === []) {
            return $attributes;
        }

        return array_diff_key($attributes, array_flip($excludedColumns));
    }
}
