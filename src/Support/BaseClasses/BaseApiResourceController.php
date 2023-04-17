<?php
namespace Support\BaseClasses;

use App\Exceptions\Api\NotFoundApiException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use Support\Helper;

abstract class BaseApiResourceController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected string $niceName = '';

    protected string $model = '';

    protected string $singleResource = '';

    protected string $collectionResource = '';

    protected array $includes = [];

    protected array $defaultIncludes = [];

    public function __construct()
    {
        if (empty($this->niceName)) {
            $this->niceName = substr($this->model, strrpos($this->model, '\\') + 1);
        }
    }

    /**
     * @param  array|null  $include
     */
    public function getResource($resource, ?array $include = null)
    {
        if (Helper::isValidUuid($resource)) {
            $resource = $this->getResourceByUuid($resource, $include);
            /*
        }
        if (is_numeric($resource))
        {
            $resource = $this->getResourceByUuid($resource, $include);
            */
        } /*elseif ($resource instanceof $this->model) {
        }
        */

        if (! empty($include)) {
            $resource->load($include);
        }

        if ($resource === null) {
            throw new NotFoundApiException('Requested '.$this->niceName.' was not found. Check your UUID or your URL.');
        }

        return $resource;
    }

    public function getResourceByUuid(string $resource_uuid, ?array $include = null): Model
    {
        if (! empty($include)) {
            $resource = $this->model::with($include)->where('uuid', $resource_uuid)->first();
        } else {
            $resource = $this->model::where('uuid', $resource_uuid)->first();
        }

        if ($resource === null) {
            throw new NotFoundApiException('Requested '.$this->niceName.' was not found. Check your UUID or your URL.');
        }

        return $resource;
    }

    public function getPerPage(): int
    {
        $perPage = (int) request()?->input('per_page');

        if (! $perPage) {
            $perPage = 10;
        }
        if ($perPage > 100) {
            $perPage = 100;
        }

        return $perPage;
    }

    /**
     * @param  string|null  $resourceName
     */
    public function getCrudMessage(Request $request, $resourceName = null): string
    {
        if ($request->isMethod('patch')) {
            $format = '%s was updated';
        } elseif ($request->isMethod('delete')) {
            $format = '%s was deleted';
        } elseif ($request->isMethod('post')) {
            $format = '%s was created';
        } else {
            $format = '%s was updated';
        }

        if (! $resourceName) {
            $resourceName = $this->niceName;
        }

        return sprintf($format, $resourceName);
    }

    /**
     * Get optional included relations from get param.
     *
     * @param  array<string>  $defaultIncludes
     *
     * @return array
     */
    protected function parseInclude(array $defaultIncludes = []): array
    {
        $includes = request()->input('include');

        if ($includes) {
            if ($includes === 'all') {
                return $this->formatIncludes($this->includes);
            }
            $includes = explode(',', $includes);
        } elseif (! empty($defaultIncludes)) {
            $includes = $defaultIncludes;
        } else {
            $includes = $this->defaultIncludes;
        }

        $includes = array_intersect($this->includes, $includes);

        return $this->formatIncludes($includes);
    }

    /**
     * @return array<string>
     *
     * @psalm-return array<array-key, string>
     */
    protected function formatIncludes(array $includes): array
    {
        return array_map(function ($value) {
            return Str::camel($value);
        }, $includes);
    }
}
