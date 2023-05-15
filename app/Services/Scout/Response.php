<?php

namespace App\Services\Scout;

use App\Concerns\Services\ScoutResponse;
use Exception;

class Response implements ScoutResponse
{
    private int $status;

    private bool $wasSuccessful;

    private ?Exception $exception = null;

    private string $body;

    public static function fromHttpResponse(\Illuminate\Http\Client\Response $response): static
    {
        $instance = new static;

        $instance->status = $response->status();

        $instance->wasSuccessful = $response->successful();

        $instance->body = $response->body();

        // Store the exception if the request was unsuccessful
        if ($instance->wasSuccessful === false) {
            $instance->exception = $response->toException();
        }

        return $instance;
    }

    /**
     * @return bool
     */
    public function isWasSuccessful(): bool
    {
        return $this->wasSuccessful;
    }

    /**
     * @return Exception|null
     */
    public function getException(): ?Exception
    {
        return $this->exception;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return array
     */
    public function getDecodedBody(): array
    {
        try {
            return json_decode($this->body, true);
        } catch (Exception $ex) {
            dd($ex->getMessage());
        }
    }
}