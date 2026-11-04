<?php

declare(strict_types=1);

namespace App\Http\Requests\Internal;

class FeedRequest extends ViewerRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:50'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            ...parent::queryParameters(),
            'per_page' => ['description' => 'Number of items per page (1-50). Defaults to 15.', 'example' => 15],
        ];
    }

    public function perPage(): int
    {
        return $this->integer('per_page', 15);
    }
}
