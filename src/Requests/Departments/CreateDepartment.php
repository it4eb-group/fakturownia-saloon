<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Requests\Departments;

use It4eb\Fakturownia\Data\Responses\DepartmentResponse;
use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;
use Saloon\Traits\Body\HasJsonBody;

final class CreateDepartment extends Request implements HasBody
{
    use HasJsonBody;

    protected Method $method = Method::POST;

    /**
     * @param  array<string, mixed>  $department
     */
    public function __construct(private readonly array $department) {}

    public function resolveEndpoint(): string
    {
        return '/departments.json';
    }

    public function createDtoFromResponse(Response $response): DepartmentResponse
    {
        return DepartmentResponse::fromApi($response->json());
    }

    protected function defaultBody(): array
    {
        return ['department' => $this->department];
    }
}
