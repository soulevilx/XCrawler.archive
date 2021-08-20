<?php

namespace App\Core\Models;

use App\Core\Models\Traits\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $reference_code
 * @property string $entity
 * @property string $state
 */
class State extends Model
{
    use HasFactory;

    public const STATE_INIT = 'CSIN';
    public const STATE_PENDING = 'CSPE';
    public const STATE_PROCESSING = 'CSPR';
    public const STATE_COMPLETED = 'CSCE';
    public const STATE_FAILED = 'CSFA';

    public const STATES = [
        self::STATE_INIT => 'init',
        self::STATE_PENDING => 'pending',
        self::STATE_PROCESSING => 'processing',
        self::STATE_COMPLETED => 'completed',
        self::STATE_FAILED => 'failed',
    ];

    protected $fillable = [
        'reference_code',
        'entity',
        'state',
    ];

    protected $casts= [
        'reference_code' => 'string',
        'entity' => 'string',
        'state' => 'string',
    ];

    protected $table = 'states';
}
