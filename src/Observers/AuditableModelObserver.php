<?php

namespace Motomedialab\SimpleLaravelAudit\Observers;

use Illuminate\Database\Eloquent\Model;
use Motomedialab\SimpleLaravelAudit\Facades\AuditFacade;

class AuditableModelObserver
{
    public function created(Model $model): void
    {
        $attributes = $this->filterExcludedColumns($model, $model->getAttributes());
        $this->auditMessage('Created', $model, $attributes);
    }

    public function updated(Model $model): void
    {
        $new = $this->filterExcludedColumns($model, $model->getChanges());
        $old = array_filter(
            $this->filterExcludedColumns($model, $model->getOriginal()),
            fn ($key) => array_key_exists($key, $new),
            ARRAY_FILTER_USE_KEY
        );

        if (!empty($new)) {
            $this->auditMessage('Updated', $model, compact('old', 'new'));
        }
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
        $contextData = [
            ...$context,
            'class' => get_class($model),
            'id' => $model->getKey(),
        ];

        $contextSortOrder = config('simple-auditor.context_sort_order');

        $sortedContext = match (true) {
            // fully reverse the data if specified
            in_array($contextSortOrder, ['desc', 'reverse']) => array_reverse($contextData),

            // attempt to sort using a supplied custom order
            is_array($contextSortOrder) => collect($contextData)
                ->sortBy(function ($item, $key) use ($contextSortOrder) {
                    $index = array_search($key, $contextSortOrder);
                    return $index === false ? 999 : $index;
                })
                ->toArray(),

            // otherwise, use default
            default => $contextData,
        };

        AuditFacade::record(class_basename($model) . ' ' . $action, $sortedContext);
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
