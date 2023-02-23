<?php

namespace App\Concerns\Jobs;

trait CanBeRedispatched
{
    /**
     * Redispatches this job assuming it passes the related check.
     *
     * @return void
     */
    protected function redispatchJob(): void
    {
        if ($this->shouldRedispatch()) {
            static::class::dispatch($this->fixture->id)
                ->delay(now()->addMinutes($this->redispatchFrequency()));
        }
    }

    /**
     * Defines the logic to determine whether the Job is in
     * a state that should be redispatched.
     *
     * @return bool
     */
    protected function shouldRedispatch(): bool
    {
        return false;
    }

    /**
     * Defines how many minutes of delay should be added before
     * the next dispatch.
     *
     * @return int
     */
    protected function redispatchFrequency(): int
    {
        return 30;
    }
}