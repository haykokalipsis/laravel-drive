<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreFolderRequest extends ParentIdBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
// Extended
//    public function authorize(): bool
//    {
//        return true;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(
            parent::rules(),
            [
                'name' => [
                    'required',
                    'unique:files', // Fix from youtube user.
                    // Name should be unique but only for currently authorized user, and only inside the given parent id, hence folder
                    Rule::unique(File::class, 'name')
                        ->where('created_by', Auth::id())
                        ->where('parent_id', $this->input('parent_id')) // fix from youtube user
                        ->whereNull('deleted_at')
//                    Rule::unique(File::class, 'name')
//                        ->where('created_by', Auth::id())
//                        ->where('parent_id', $this->parent_id)
//                        ->whereNull('deleted_at'), // if we deleted test folder we should be able to create new test folder
                ]
            ]
        );
    }

    public function messages()
    {
        return [
            'name.unique' => 'Folder ":input" already exists'
        ];
    }
}
