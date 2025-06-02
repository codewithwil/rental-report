<?php

namespace App\Models\Scopes;

use Illuminate\{
    Database\Eloquent\Builder,
    Database\Eloquent\Model,
    Database\Eloquent\Scope,
    Support\Facades\Auth
};

class UserBranchScope implements Scope
{

    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();

        if (!($user && $user->hasRole('admin') && $user->branch_id == null)) {
            $builder->whereHas('user', function ($q) use ($user){
                $q->where('branch_id', $user->branch_id);
            });
        }
    }
}
