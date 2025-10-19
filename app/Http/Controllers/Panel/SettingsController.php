<?php

namespace App\Http\Controllers\Panel;

use App\Models\Admin;
use App\Models\Constant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SettingsController extends BaseController
{
    function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $key = $request->key;
        if($key == 'business_settings'){
            $setting = Setting::all()->keyBy('key');
            $currency= Constant::getConstants('currency')->children()->pluck('value');
            return response()->json([
                'status' => true,
                'settings' => $setting,
                'currency' => $currency
            ]);
        }

    }


    public function store(Request $request)
    {
        $request->validate([
            'currency' => 'required',
        ]);
        Setting::setSettings($request->except('undefined', '_token', 'website_logo'));
        return response()->json(['status' => true, 'message' => trans('messages.success')]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string|max:190|min:6',
            'new_password' => 'required|string|max:190|min:6|confirmed',
        ]);
        $admin = $this->admin();
        if (Hash::check($request['current_password'], $admin->password)) {
            $admin->update(['password' => bcrypt($request['new_password'])]);
            return response()->json(['status' => true, 'message' => trans('messages.success')]);
        } else {
            return response()->json(['message' => 'The current password is wrong'], 422);
        }

    }

    public function storeFeatures(Request $request)
    {

        if (empty($request->goip_feature)) $request['goip_feature'] = 0;
        if (empty($request->etms_feature)) $request['etms_feature'] = 0;
        if (empty($request->dinstar_feature)) $request['dinstar_feature'] = 0;
        if (empty($request->firebase_feature)) $request['firebase_feature'] = 0;
        if (empty($request->dongle_feature)) $request['dongle_feature'] = 0;
        if (empty($request->cdr_sound_feature)) $request['cdr_sound_feature'] = 0;
        if (empty($request->esps_feature)) $request['esps_feature'] = 0;
        Setting::updateOrCreate(['key' => 'goip_feature'], ['value' => $request['goip_feature']]);
        Setting::updateOrCreate(['key' => 'etms_feature'], ['value' => $request['etms_feature']]);
        Setting::updateOrCreate(['key' => 'dinstar_feature'], ['value' => $request['dinstar_feature']]);
        Setting::updateOrCreate(['key' => 'firebase_feature'], ['value' => $request['firebase_feature']]);
        Setting::updateOrCreate(['key' => 'dongle_feature'], ['value' => $request['dongle_feature']]);
        Setting::updateOrCreate(['key' => 'cdr_sound_feature'], ['value' => $request['cdr_sound_feature']]);
        Setting::updateOrCreate(['key' => 'esps_feature'], ['value' => $request['esps_feature']]);
        Setting::updateOrCreate(['key' => 'server_restart_at'], ['value' => $request['server_restart_at']]);
        return response()->json(['status' => true, 'message' => trans('messages.success')]);
    }
    public function storeNotification(Request $request)
    {
        $request->validate([
            'numbers.*.mobile' => 'required|numeric',
        ]);
        Setting::updateOrCreate(['key' => 'notification_mobile_numbers'], ['value' => json_encode($request['numbers'])]);
        return response()->json(['status' => true, 'message' => trans('messages.success')]);
    }


}
