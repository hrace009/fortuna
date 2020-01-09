<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TicketRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'subject' => 'required|min:4',
            'category_id' => 'required|exists:ticket_categories,id',
            'message' => 'required|min:4|max:2000',
            //'attachments' => 'nullable|mimes:jpg,jpeg,png|max:1024',
        ];
    }
}
