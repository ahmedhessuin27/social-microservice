<?php

declare(strict_types=1);

namespace App\Http\Requests\Internal;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Read requests: who is looking, so each post/comment can say whether
 * that user liked it, and drafts can be shown to their own author.
 */
class ViewerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'viewer_id' => ['sometimes', 'integer', 'min:1'],
        ];
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function queryParameters(): array
    {
        return [
            'viewer_id' => ['description' => 'The id of the user viewing, as used by the calling backend. Adds a "liked" flag to each item.', 'example' => 42],
        ];
    }

    public function viewerId(): ?int
    {
        return $this->filled('viewer_id') ? $this->integer('viewer_id') : null;
    }
}
