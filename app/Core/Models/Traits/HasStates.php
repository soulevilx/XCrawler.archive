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
    public function initializeHasStates()
    {
        $this->mergeFillable([
            'state_code'
        ]);

        $this->mergeCasts([
            'state_code' => 'string',
        ]);
    }

    public static function bootHasStates()
    {
        static::creating(function ($model) {
            $model->state_code = $model->state_code ?? State::STATE_INIT;
        });
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
