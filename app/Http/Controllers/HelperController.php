<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class HelperController extends Controller
{
    public function keepAlive(Request $request)
    {
        $request->session()->put('_last_keep_alive', now()->timestamp);

        return response()->json([
            'success' => true,
            'csrf_token' => csrf_token(),
        ]);
    }

    public function toggleStatus(Request $request)
    {

        $validatedData = $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
            'value' => 'required|boolean',
            'attribute' => 'required|string|in:is_active,show_in_home',
        ]);

        $model = "App\\Models\\" . Str::studly(Str::singular($validatedData['model']));

        if (!class_exists($model)) {
            return response()->json([
                'success' => false,
                'message' => 'Model not found!',
            ], 404);
        }

        $modelInstance = $model::find($validatedData['id']);

        if (!$modelInstance) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found!',
            ], 404);
        }

        $column = $validatedData['attribute'];

        if (!Schema::hasColumn($modelInstance->getTable(), $column)) {
            return response()->json([
                'success' => false,
                'message' => 'Column not found!',
            ], 422);
        }

        $value = $request->boolean('value');
        $modelInstance->$column = $value;
        $modelInstance->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
            'attribute' => $column,
            'value' => (bool) $modelInstance->$column,
        ]);
    }
}
