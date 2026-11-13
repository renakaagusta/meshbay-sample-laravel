<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    private const CONTRACT_VERSION = 'v1';

    public function health(): JsonResponse
    {
        return response()->json(['status' => 'ok', 'version' => self::CONTRACT_VERSION]);
    }

    public function index(): JsonResponse
    {
        $items = DB::table('items')->orderBy('id')->get(['id', 'name', 'created_at']);

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:120']]);
        $itemName = trim($validated['name']);

        $itemID = DB::table('items')->insertGetId([
            'name' => $itemName,
            'created_at' => now(),
        ]);

        return response()->json(DB::table('items')->where('id', $itemID)->first(), 201);
    }

    public function show(int $itemID): JsonResponse
    {
        $item = DB::table('items')->where('id', $itemID)->first(['id', 'name', 'created_at']);
        if ($item === null) {
            return response()->json(['error' => 'not found'], 404);
        }

        return response()->json($item);
    }

    public function update(Request $request, int $itemID): JsonResponse
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:120']]);
        $updated = DB::table('items')->where('id', $itemID)->update(['name' => trim($validated['name'])]);
        if ($updated === 0 && ! DB::table('items')->where('id', $itemID)->exists()) {
            return response()->json(['error' => 'not found'], 404);
        }

        return response()->json(DB::table('items')->where('id', $itemID)->first(['id', 'name', 'created_at']));
    }

    public function destroy(int $itemID): JsonResponse
    {
        $deleted = DB::table('items')->where('id', $itemID)->delete();
        if ($deleted === 0) {
            return response()->json(['error' => 'not found'], 404);
        }

        return response()->json(null, 204);
    }
}
