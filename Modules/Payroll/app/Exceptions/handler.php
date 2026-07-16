<?php

namespace Modules\Payroll\App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\App;

class handler extends ExceptionHandler
{
    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'message' => __('The given data was invalid.'),
            'errors' => collect($exception->errors())->map(function ($messages, $field) {
                // Determine language based on field name (e.g., title_en -> en, title_ur -> ur)
                $locale = str_ends_with($field, '_ur') ? 'ur' : App::getLocale();
                
                return array_map(function ($message) use ($locale) {
                    // Translate the message to the target locale
                    return trans($message, [], $locale);
                }, $messages);
            }),
        ], 422);
    }
}