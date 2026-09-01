<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAuthenticationSettingRequest;
use App\Http\Requests\UpdateAuthenticationSettingRequest;
use App\Models\AuthenticationSetting;

class AuthenticationSettingController extends Controller
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
    public function store(StoreAuthenticationSettingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AuthenticationSetting $authenticationSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AuthenticationSetting $authenticationSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthenticationSettingRequest $request, AuthenticationSetting $authenticationSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AuthenticationSetting $authenticationSetting)
    {
        //
    }
}
