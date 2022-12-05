<?php

namespace App\Http\Requests\V1;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        return $user != null && $user->tokenCan('update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $method = $this->method();
        if($method == 'PUT'){
            return [
                "*.customerId" => ['required','integer'],
                "*.amount" => ['required','numeric'],
                "*.status" => ['required', Rule::in(['B','P','V', 'b','p','v'])], //B = billed, P = payed V = void
                "*.billedDate" => ['required','date_format:Y-m-d H:i:s'],
                "*.paidDate" => ['nullable','date_format:Y-m-d H:i:s'],
            ];
        }else {
            return [
                "*.customerId" => ['sometimes','required','integer'],
                "*.amount" => ['sometimes','required','numeric'],
                "*.status" => ['sometimes','required', Rule::in(['B','P','V', 'b','p','v'])], //B = billed, P = payed V = void
                "*.billedDate" => ['sometimes','required','date_format:Y-m-d H:i:s'],
                "*.paidDate" => ['sometimes','nullable','date_format:Y-m-d H:i:s'],
            ];;
        }
        
    }
    protected function prepareForValidation()
    {
        $data = [];
        //input to array { format underscore fields } so we can validate
        foreach ($this->toArray() as $obj) {
            $obj['customer_id'] = $obj['customerId'] ?? null;
            $obj['billed_date'] = $obj['billedDate'] ?? null;
            $obj['paid_date'] = $obj['paidDate'] ?? null;
            $data[] = $obj;
        }

        $this->merge($data);
    }
}
