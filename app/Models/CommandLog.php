<?php

namespace App\Models;

use App\Concerns\GeneratesUuidOnCreation;
use Illuminate\Database\Eloquent\Model;

class CommandLog extends Model
{
    use GeneratesUuidOnCreation;

    protected $fillable = [
        'uuid',
        'command',
        'trigger',
        'started_at',
        'completed_at',
        'was_successful',
    ];

    /**
     * @return void
     */
    public function markSuccessful(): void
    {
        $this->update([
            'completed_at' => now(),
            'was_successful' => true,
        ]);
    }

    /**
     * @return void
     */
    public function markFailed(): void
    {
        $this->update([
            'completed_at' => now(),
            'was_successful' => false,
        ]);
    }
}
