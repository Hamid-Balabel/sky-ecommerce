<?php

namespace App\Http\Controllers\API\Products;

use App\Filters\Global\NameFilter;
use App\Filters\Global\OrderByFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Global\Other\PageRequest;
use App\Http\Requests\Product\ProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * @param PageRequest $request
     * @return JsonResponse
     */
    public function index(PageRequest $request): JsonResponse
    {
        $query = app(Pipeline::class)
            ->send(Product::query())
            ->through([
                NameFilter::class,
                OrderByFilter::class,
            ])
            ->thenReturn();

        return successResponse(data: fetchData($query, $request->pageSize, ProductResource::class));
    }

    /**
     * @param ProductRequest $request
     * @return JsonResponse|null
     */
    public function store(ProductRequest $request): ?JsonResponse
    {
        $this->authorize('adminOnly', Product::class);

        return DB::transaction(static function () use ($request) {

            $product = Product::create($request->validated());

            return successResponse(trans('api.created_success'), new ProductResource($product));
        });
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function show(Product $product): JsonResponse
    {
        return successResponse(data: new ProductResource($product));
    }

    /**
     * @param ProductRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $this->authorize('adminOnly', Product::class);

        return DB::transaction(function () use ($request, $product) {
            $product->update($request->validated());

            return successResponse(__('api.updated_success'), new ProductResource($product->refresh()));
        });
    }

    /**
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('adminOnly', Product::class);

        return DB::transaction(function () use ($product) {
            $product->delete();

            return successResponse(msg: __('api.deleted_success'));
        });
    }

    public function restore(int $id): JsonResponse
    {
        $this->authorize('adminOnly', Product::class);

        Product::onlyTrashed()->findOrFail($id)->restore();

        return successResponse(msg: __('api.restored_success'));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function forceDelete(int $id): JsonResponse
    {
        $this->authorize('adminOnly', Product::class);

        Product::onlyTrashed()->findOrFail($id)->forceDelete();

        return successResponse(msg: __('api.deleted_success'));
    }

}
