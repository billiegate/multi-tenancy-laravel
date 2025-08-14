<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helper\TenantCache;

/**
 * CacheController handles cache operations for tenant-specific data.
 * It allows storing cache items with a key, value, and time-to-live (TTL).
 */
class CacheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all cache items for the current tenant
        $cacheItems = TenantCache::get('*') ?? []; // Using wildcard to get all items

        return response()->json($cacheItems, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'key' => 'required|string|max:255',
            'value' => 'required',
            'ttl' => 'required|integer|min:1', // Time to live in seconds
        ]);

        // Store the cache item
        TenantCache::put($validatedData['key'], $validatedData['value'], $validatedData['ttl']);

        return response()->json(['message' => 'Cache item stored successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Retrieve the cache item by key
        $value = TenantCache::get($id);

        if ($value === null) {
            return response()->json(['message' => 'Cache item not found'], 404);
        }

        return response()->json(['key' => $id, 'value' => $value]); 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'value' => 'required',
            'ttl' => 'required|integer|min:1', // Time to live in seconds
        ]);

        // Update the cache item
        TenantCache::put($id, $validatedData['value'], $validatedData['ttl']);

        return response()->json(['message' => 'Cache item updated successfully']);  
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Remove the cache item by key
        if (TenantCache::get($id) === null) {
            return response()->json(['message' => 'Cache item not found'], 404);
        }

        TenantCache::put($id, null, 0); // Set TTL to 0 to remove the item

        return response()->json(['message' => 'Cache item deleted successfully'], 204);
    }
}
