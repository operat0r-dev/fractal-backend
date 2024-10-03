<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\IntegrationSettingRequest;
use App\Http\Responses\ApiResponse;
use App\Models\IntegrationSetting;
use Auth;

class IntegrationSettingController extends Controller
{

    public function index(): ApiResponse
    {
        $user = Auth::user();

        $IntegrationSetting = IntegrationSetting::where("user_id", $user->id)->get();

        return ApiResponse::ok($IntegrationSetting->toArray());
    }

    public function store(IntegrationSettingRequest $request): ApiResponse
    {
        $user = Auth::user();

        $IntegrationSetting = IntegrationSetting::create([
            'type' => $request->get('type'),
            'value' => $request->get('value'),
            'user_id' => $user->id,
        ]);

        return ApiResponse::created($IntegrationSetting->toArray());
    }

    public function update(IntegrationSettingRequest $request, int $id): ApiResponse
    {
        $user = Auth::user();

        $IntegrationSetting = IntegrationSetting::find($id);
        $IntegrationSetting->update([
            'type' => $request->get('type'),
            'value' => $request->get('value'),
            'user_id' => $user->id,
        ]);
        $IntegrationSetting->save();

        return ApiResponse::created($IntegrationSetting->toArray());
    }

    public function delete(int $id): ApiResponse
    {
        $IntegrationSetting = IntegrationSetting::find($id);
        $IntegrationSetting->delete();

        return ApiResponse::ok();
    }
}
