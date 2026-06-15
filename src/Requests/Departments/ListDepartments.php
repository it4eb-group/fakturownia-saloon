<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Departments;

use It4eb\Fakturownia\Data\Responses\DepartmentResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

final class ListDepartments extends Request
{
    protected Method $method = Method::GET;

    public function resolveEndpoint(): string
    {
        return '/departments.json';
    }

    /**
     * @return list<DepartmentResponse>
     */
    public function createDtoFromResponse(Response $response): array
    {
        return array_map(
            static fn (array $department): DepartmentResponse => DepartmentResponse::fromApi($department),
            $response->json(),
        );
    }
}
