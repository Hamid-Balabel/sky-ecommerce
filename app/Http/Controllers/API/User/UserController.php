<?php

namespace App\Http\Controllers\API\User;

use App\Enums\UserTypeEnum;
use App\Filters\Global\OrderByFilter;
use App\Filters\User\UserFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Global\Other\DeleteAllRequest;
use App\Http\Requests\Global\Other\PageRequest;
use App\Http\Requests\User\UserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Services\Global\UploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * @param PageRequest $request
     * @return JsonResponse
     */
    public function index(PageRequest $request): JsonResponse
    {
        $this->authorize('adminOnly', User::class);

        $query = app(Pipeline::class)
            ->send(User::query()->where('type', $request->type ?? UserTypeEnum::Customer->value))
            ->through([UserFilter::class])
            ->thenReturn();

        return successResponse(fetchData($query, $request->pageSize, UserResource::class));
    }

    /**
     * @param UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request): JsonResponse
    {
        $this->authorize('adminOnly', User::class);

        return DB::transaction(function () use ($request) {
            $user = User::create($this->prepareData($request));

            return successResponse(new UserResource($user), __('api.created_success'));
        });
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function show(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        return successResponse(new UserResource($user));
    }

    /**
     * @param UserRequest $request
     * @param User $user
     * @return JsonResponse
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        return DB::transaction(function () use ($user, $request) {
            $user->update($this->prepareData($request));

            return successResponse(new UserResource($user->refresh()), __('api.updated_success'));
        });
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('adminOnly', User::class);

        UploadService::delete($user->avatar);
        $user->delete();

        return successResponse(msg: __('api.deleted_success'));
    }

    /**
     * @param DeleteAllRequest $request
     * @return JsonResponse
     */
    public function destroyAll(DeleteAllRequest $request): JsonResponse
    {
        $this->authorize('adminOnly', User::class);

        User::whereIn('id', $request->ids)->delete();

        return successResponse(msg: __('api.deleted_success'));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */

    public function restore(int $id): JsonResponse
    {
        $this->authorize('restore', User::class);

        User::onlyTrashed()->findOrFail($id)->restore();

        return successResponse(msg: __('api.restored_success'));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function forceDelete(int $id): JsonResponse
    {
        $this->authorize('forceDelete', User::class);

        User::onlyTrashed()->findOrFail($id)->forceDelete();

        return successResponse(msg: __('api.deleted_success'));
    }

    /**
     * Prepare user data for storing or updating.
     */
    private function prepareData(UserRequest $request): array
    {
        $data = Arr::except($request->validated(), ['avatar']);
        $data['avatar'] = UploadService::store($request->avatar, 'users');

        return $data;
    }
}
