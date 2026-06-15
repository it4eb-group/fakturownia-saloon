<?php

declare(strict_types=1);

namespace It4eb\Fakturownia\Resources;

use It4eb\Fakturownia\Data\Requests\DepartmentData;
use It4eb\Fakturownia\Data\Responses\DepartmentResponse;
use It4eb\Fakturownia\Requests\Departments\CreateDepartment;
use It4eb\Fakturownia\Requests\Departments\GetDepartment;
use It4eb\Fakturownia\Requests\Departments\ListDepartments;
use It4eb\Fakturownia\Requests\Departments\UpdateDepartment;
use Saloon\Http\BaseResource;

final class DepartmentResource extends BaseResource
{
    /**
     * @return list<DepartmentResponse>
     */
    public function list(): array
    {
        return $this->connector->send(new ListDepartments)->dtoOrFail();
    }

    public function get(int $id): DepartmentResponse
    {
        return $this->connector->send(new GetDepartment($id))->dtoOrFail();
    }

    /**
     * @param  DepartmentData|array<string, mixed>  $department
     */
    public function create(DepartmentData|array $department): DepartmentResponse
    {
        $payload = $department instanceof DepartmentData ? $department->toApiArray() : $department;

        return $this->connector->send(new CreateDepartment($payload))->dtoOrFail();
    }

    /**
     * @param  DepartmentData|array<string, mixed>  $department
     */
    public function update(int $id, DepartmentData|array $department): DepartmentResponse
    {
        $payload = $department instanceof DepartmentData ? $department->toApiArray() : $department;

        return $this->connector->send(new UpdateDepartment($id, $payload))->dtoOrFail();
    }
}
