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
}
