<?php
namespace Support\BaseClasses;

use App\Exceptions\Api\ValidationFailedApiException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class BaseFormRequest extends FormRequest
{
    public function resolveRouteParam($key, $modelClass)
    {
        $model = $this->route($key);

        if (! $model || ! is_a($model, $modelClass)) {
            $model = $modelClass::findOrFail($this->route($key))->first();
        }

        return $model;
    }

    /**
     * @param  Validator  $validator
     *
     * @throws ValidationFailedApiException
     */
    protected function failedValidation(Validator $validator): void
    {
        throw new ValidationFailedApiException($validator);
    }
}
