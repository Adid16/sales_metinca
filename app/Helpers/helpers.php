<?php

use App\Models\SystemSetting;

if (!function_exists('system_setting')) {
    /**
     * Get or set system settings.
     *
     * @param  string|array  $key
     * @param  mixed  $default
     * @return mixed
     */
    function system_setting($key = null, $default = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                SystemSetting::updateOrCreate(['key' => $k], ['value' => $v]);
            }
            return true;
        }

        if (is_null($key)) {
            return SystemSetting::all()->pluck('value', 'key');
        }

        $setting = SystemSetting::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
