<?php

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Responses Methods
|--------------------------------------------------------------------------
*/
if (!function_exists('successResponse')) {
    function successResponse($data = [], $msg = 'success', $code = 200): JsonResponse
    {
        return response()->json(['status' => true, 'code' => $code, 'message' => $msg, 'data' => $data], $code);
    }
}

if (!function_exists('failResponse')) {
    function failResponse($msg = 'Fail', $code = 400): JsonResponse
    {
        return response()->json(['status' => false, 'message' => $msg, 'code' => $code], $code);
    }
}

if (!function_exists('abort403')) {
    function abort403(): JsonResponse
    {
        abort(403, trans('api.no_required_permissions'));
    }
}

if (!function_exists('unKnownError')) {
    function unKnownError($message = null): JsonResponse|RedirectResponse
    {
        $message = trans('dashboard.something_error') . '' . (config('debug') ? " : $message" : '');

        return request()?->expectsJson()
            ? response()->json(['message' => $message], 400)
            : redirect()->back()->with(['status' => 'error', 'message' => $message]);
    }
}


/*
|--------------------------------------------------------------------------
| Resolves Methods
|--------------------------------------------------------------------------
*/
if (!function_exists('resolveTrans')) {
    function resolveTrans($trans = '', $page = 'api', $lang = null, $snaked = true): ?string
    {
        if (empty($trans)) {
            return '---';
        }

        app()->setLocale($lang ?? app()->getLocale());

        $key = $snaked ? Str::snake($trans) : $trans;

        return Str::startsWith(__("$page.$key"), "$page.") ? $trans : __("$page.$key");
    }
}

if (!function_exists('resolvePhoto')) {
    function resolvePhoto($image = null, $type = 'user')
    {
        $result = ($type === 'user'
            ? asset('media/avatar.png')
            : asset('media/blank.png'));

        if (is_null($image)) {
            return $result;
        }

        if (Str::startsWith($image, 'http')) {
            return $image;
        }

        return Storage::exists($image)
            ? Storage::url($image)
            : $result;
    }
}

/*
|--------------------------------------------------------------------------
| App Global Methods
|--------------------------------------------------------------------------
*/

if (!function_exists('fetchData')) {
    function fetchData(Builder $query, string|int|null $pageSize = null, $resource = null, $meta = [])
    {
        if ($pageSize && (int)$pageSize !== -1) {
            $data = $query->paginate($pageSize);

            if ($resource) {
                $data->data = $resource::collection($data);
            }
        } else {
            $data = $resource ? $resource::collection($query->get()) : $query->get();
        }

        if (count($meta)) {
            $data = [
                'data' => $data,
                ...$meta,
            ];
        }

        return $data;
    }
}

if (!function_exists('vImage')) {
    function vImage($ext = null): string
    {
        return ($ext === null) ? 'mimes:jpg,png,jpeg,png,gif,bmp' : 'mimes:' . $ext;
    }
}

if (!function_exists('logError')) {
    function logError($exception): void
    {
        info("Error In Line => " . $exception->getLine() . " in File => {$exception->getFile()} , ErrorDetails => " . $exception->getMessage());
    }
}
