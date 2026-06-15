<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Departments;

use It4eb\Fakturownia\Data\Responses\DepartmentResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class GetDepartment extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private readonly int $id) {}

    public function resolveEndpoint(): string
    {
        return "/departments/{$this->id}.json";
    }

    public function createDtoFromResponse(Response $response): DepartmentResponse
    {
        return DepartmentResponse::fromApi($response->json());
    }
}
