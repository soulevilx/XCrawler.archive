<?php

namespace App\Core\Models\Traits;

use App\Core\Models\State;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property string $state_code
 * @property State  $state
 */
trait HasStates
{
    protected function loadHasStatesTrait()
    {
        $this->fillable = array_merge($this->fillable, [
            'state_code'
        ]);
        $this->casts = array_merge($this->casts, [
            'state_code' => 'string',
        ]);
    }

    public function scopeByState(Builder $builder, string $stateCode)
    {
        return $builder->where(['state_code' => $stateCode]);
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_code', 'reference_code');
    }

    public function setState(string $stateCode): void
    {
        $this->state_code = $stateCode;
        $this->save();
    }

    public function completed()
    {
        $this->setState(State::STATE_COMPLETED);
    }

    public function isInitState()
    {
        return State::STATE_INIT === $this->state_code;
    }

    public function isPendingState()
    {
        return State::STATE_PENDING === $this->state_code;
    }

    public function isProcessingState()
    {
        return State::STATE_PROCESSING === $this->state_code;
    }

    public function isCompletedState()
    {
        return State::STATE_COMPLETED === $this->state_code;
    }

    public function isFailedState()
    {
        return State::STATE_FAILED === $this->state_code;
    }
}
