<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // Use hasUser() to check if user is already loaded in memory.
        // Auth::check() would trigger a DB query if not loaded, causing infinite loop 
        // if this scope is applied to the User model.
        if (Auth::hasUser()) {
            $user = Auth::user();

            // Super Admin sees everything (Bypass Scope)
            if ($user->role && $user->role->slug === 'super-admin') {
                return;
            }
            
            // Standard User: Scoped to their Clinic
            if ($user->clinic_id) {
                $builder->where($model->getTable() . '.clinic_id', $user->clinic_id);
            }
        }
    }
}
