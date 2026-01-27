<?php

namespace App\Http\Controllers;

use App\Models\ShipStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShipStatusRequest;
use App\Http\Requests\UpdateShipStatusRequest;

class ShipStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShipStatusRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ShipStatus $shipStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ShipStatus $shipStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShipStatusRequest $request, ShipStatus $shipStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShipStatus $shipStatus)
    {
        //
    }
}
