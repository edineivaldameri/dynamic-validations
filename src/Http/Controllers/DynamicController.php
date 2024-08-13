<?php

declare(strict_types=1);

namespace EdineiValdameri\Laravel\DynamicValidation\Http\Controllers;

use EdineiValdameri\Laravel\DynamicValidation\Http\Requests\DynamicFormRequest;
use EdineiValdameri\Laravel\DynamicValidation\Http\Resources\RuleResource;
use EdineiValdameri\Laravel\DynamicValidation\Repositories\RuleRepository;
use EdineiValdameri\Laravel\DynamicValidation\Services\JsonSchemaService;
use Illuminate\Http\JsonResponse;

class DynamicController
{
    public function __construct(
        protected RuleRepository $repository,
        protected JsonSchemaService $jsonSchemaGenerator
    ) {
    }

    public function index(): JsonResponse
    {
        $rules = $this->repository->list();

        return response()->json($rules);
    }

    public function store(DynamicFormRequest $request): JsonResponse
    {
        $rule = $this->repository->store($request);

        return response()->json($rule);
    }

    public function update(DynamicFormRequest $request, int $id): JsonResponse
    {
        $rule = $this->repository->update($request, $id);

        return response()->json($rule);
    }

    public function show(string $action): JsonResponse
    {
        $rules = $this->repository->getRulesByAction($action);

        return response()->json(
            RuleResource::collection($rules)
        );
    }

    public function schema(string $action): JsonResponse
    {
        $schema = $this->jsonSchemaGenerator->generate($action);

        return response()->json($schema);
    }
}
