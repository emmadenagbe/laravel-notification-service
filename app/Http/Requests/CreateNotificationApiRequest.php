<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateNotificationApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'channels' => ['required', 'array', 'min:1'],
            'channels.*' => ['required', 'string', new \Illuminate\Validation\Rules\Enum(\Core\Domain\Notification\Enums\NotificationType::class)],
            'recipient' => ['required', 'string'],
            'payload' => ['required', 'array'],
        ];
    }
}
