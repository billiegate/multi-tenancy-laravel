<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\StoreConfigRequest;
use App\Http\Requests\UpdateConfigRequest;
use App\Models\Tenant\Config;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

class ConfigController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Config::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // This method is typically not used in API controllers
        // as it is meant for rendering a view in web applications.
        // You can return a response or redirect if needed.
        return response()->json(['message' => 'Create form not available'], 405);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConfigRequest $request)
    {
        $data = $request->validated();
        $data['uuid'] = Str::uuid()->toString();

        $config = Config::create($data);

        return response()->json($config, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Config $config)
    {
        return response()->json($config);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Config $config)
    {
        // This method is typically not used in API controllers
        // as it is meant for rendering a view in web applications.
        // You can return a response or redirect if needed.
        return response()->json(['message' => 'Edit form not available'], 405);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConfigRequest $request, Config $config)
    {
        $config->update($request->validated());

        return response()->json($config);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Config $config)
    {
        $config->delete();

        return response()->json(null, 204);
    }
}
