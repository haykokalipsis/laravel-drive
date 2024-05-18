<?php

namespace App\Http\Requests;

use App\Models\File;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ParentIdBaseRequest extends FormRequest
{
    public ?File $parent = null;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // if parent id does not exist , we assume user wants to create something in the root, so we just return true
        $this->parent = File::query()->where('id', $this->input('parent_id'))->first();

        // But if parent id exists and is NOT owned by current user, we return false
        if (
            $this->parent &&
            !$this->parent->isOwnedBy(Auth::id())
        )
        {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'parent_id' => [
                Rule::exists(File::class, 'id')
                ->where(function (Builder $query) {
                    return $query
                        ->where('is_folder', '=', 1)
                        ->where('created_by', '=', Auth::id());
                })
            ]
        ];
    }
}
