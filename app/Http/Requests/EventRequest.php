<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title'        => 'required|string|min:5|max:200',
            'description'  => 'required|string|min:20|max:5000',
            'category_id'  => 'required|string',
            'location'     => 'required|string|max:200',
            'address'      => 'required|string|max:300',
            'city'         => 'required|string|max:100',
            'state'        => 'nullable|string|max:100',
            'country'      => 'required|string|max:100',
            'start_date'   => 'required|date|after:now',
            'end_date'     => 'nullable|date|after:start_date',
            'is_free'      => 'boolean',
            'price'        => 'nullable|numeric|min:0|required_if:is_free,false',
            'max_attendees'=> 'nullable|integer|min:1|max:100000',
            'tags'         => 'nullable|string|max:500',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Event title is required.',
            'title.min'            => 'Title must be at least 5 characters.',
            'description.required' => 'Event description is required.',
            'description.min'      => 'Description must be at least 20 characters.',
            'category_id.required' => 'Please select a category.',
            'location.required'    => 'Event location is required.',
            'address.required'     => 'Street address is required.',
            'city.required'        => 'City is required.',
            'country.required'     => 'Country is required.',
            'start_date.required'  => 'Start date is required.',
            'start_date.after'     => 'Start date must be in the future.',
            'end_date.after'       => 'End date must be after start date.',
            'price.required_if'    => 'Price is required for paid events.',
            'image.image'          => 'Event image must be a valid image file.',
            'image.max'            => 'Image size must not exceed 4MB.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_free' => $this->boolean('is_free'),
        ]);
    }
}
