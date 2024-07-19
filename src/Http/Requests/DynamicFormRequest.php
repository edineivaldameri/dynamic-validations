<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\DynamicValidation\Http\Requests;

use EdineiValdameri\Laravel\DynamicValidation\Services\ValidationService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Route;

class DynamicFormRequest extends FormRequest
{
    public function __construct(
        protected ValidationService $validationService
    ) {
        parent::__construct();
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Route $route */
        $route = $this->route();

        /** @var string $action */
        $action = $route->getName();

        return $this->validationService->getValidationRules($action);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        /** @var Route $route */
        $route = $this->route();

        /** @var string $action */
        $action = $route->getName();

        return $this->validationService->getValidationMessages($action);
    }
}
