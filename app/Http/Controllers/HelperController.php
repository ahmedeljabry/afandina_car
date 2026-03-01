<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        $request->validate([
            'model' => 'required|string',
            'id' => 'required|integer',
            'value' => 'required|boolean',
            'attribute' => 'required',
        ]);
        $model = "App\\Models\\" . ucfirst(Str::singular($request->model));
        if (!class_exists($model)) {
            return response()->json([
                'success' => false,
                'message' => 'Model not found!',
            ], 404);
        }
        $modelInstance = $model::find($request->id);
        if (!$modelInstance) {
            return response()->json([
                'success' => false,
                'message' => 'Record not found!',
            ], 404);
        }
        $column = $request->attribute;
        $value = $request->value;
        $modelInstance->$column = $value;
        $modelInstance->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully!',
        ]);
    }
}
