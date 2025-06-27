<?php

namespace App\JsonApi\V1\Bills;

use Illuminate\Validation\Rule;
use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;
use LaravelJsonApi\Validation\Rule as JsonApiRule;

class BillRequest extends ResourceRequest
{

    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'customerName' => ['required', 'string'],
            'currency' => ['required', 'string'],
            'status' => ['required', 'string'],
            'issuedAt' => ['nullable', JsonApiRule::dateTime()],
            'dueAt' => ['nullable', JsonApiRule::dateTime()],
            'discount' => ['nullable', 'numeric'],
            'articles' => JsonApiRule::toMany(),
        ];
    }

}
