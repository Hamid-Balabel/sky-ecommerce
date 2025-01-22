<?php

namespace App\Http\Controllers\API\Orders;

use App\Enums\OrderStatusEnum;
use App\Filters\Global\NameFilter;
use App\Filters\Global\OrderByFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Global\Other\PageRequest;
use App\Http\Requests\Order\OrderRequest;
use App\Http\Requests\Order\OrderStatusRequest;
use App\Http\Resources\Order\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * @param PageRequest $request
     * @return JsonResponse
     */
    public function index(PageRequest $request): JsonResponse
    {
        $query = app(Pipeline::class)
            ->send(Order::query()->with(['products', 'customer']))
            ->through([
                NameFilter::class,
                OrderByFilter::class,
            ])
            ->thenReturn();

        return successResponse(data: fetchData($query, $request->pageSize, OrderResource::class));
    }

    /**
     * @param OrderRequest $request
     * @return JsonResponse|null
     */
    public function store(OrderRequest $request): ?JsonResponse
    {
        return DB::transaction(static function () use ($request) {

            $products = $request->products;

            $total_amount = collect($products)->reduce(function ($carry, $product) {
                return $carry + ($product['price'] * $product['quantity']);
            }, 0);

            $data = [
                'customer_id' => auth()->id(),
                'total_amount' => $total_amount * 1.1
            ];

            $order = Order::create($data);

            foreach ($products as $product) {
                $order->products()->attach($product['id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                ]);
            }

            return successResponse(trans('api.created_success'), new OrderResource($order->load('products', 'customer')->refresh()), 201);
        });
    }

    /**
     * @param Order $order
     * @return JsonResponse
     */
    public function show(Order $order): JsonResponse
    {
        return successResponse(data: new OrderResource($order->load('products', 'customer')));
    }

    /**
     * @param OrderStatusRequest $request
     * @param Order $order
     * @return JsonResponse
     */
    public function changeStatus(OrderStatusRequest $request, Order $order): JsonResponse
    {
        try {

            if (!OrderStatusEnum::tryFrom($request->status)) {
                return failResponse(__('api.update_failed'), 422);
            }

            $order->update(['status' => $request->status]);

            return successResponse(__('api.updated_success'), new OrderResource($order->refresh()));
        } catch (\Exception $e) {
            return failResponse(__('api.update_failed'), 500);
        }
    }


    /**
     * @param Order $order
     * @return JsonResponse
     */
    public function destroy(Order $order): JsonResponse
    {
        $this->authorize('adminOnly', Order::class);

        return DB::transaction(function () use ($order) {
            $order->delete();

            return successResponse(msg: __('api.deleted_success'));
        });
    }

    public function restore(int $id): JsonResponse
    {
        $this->authorize('adminOnly', Order::class);

        Order::onlyTrashed()->findOrFail($id)->restore();

        return successResponse(msg: __('api.restored_success'));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function forceDelete(int $id): JsonResponse
    {
        $this->authorize('adminOnly', Order::class);

        Order::onlyTrashed()->findOrFail($id)->forceDelete();

        return successResponse(msg: __('api.deleted_success'));
    }

}
