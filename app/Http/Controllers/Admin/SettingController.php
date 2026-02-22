<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Traits\HandlesGcsImage;

class SettingController extends Controller
{
    use HandlesGcsImage;
    public function index()
    {
        // Fetch the first settings record, or create a new empty one if none exists
        $setting = Setting::firstOrCreate([], [
            'site_email' => 'admin@store.com'
        ]);
        return view('admin.settings.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::firstOrFail();

        // 1. General validation rules
        $rules = [
            'site_email' => 'nullable|email',
            'site_phone' => 'nullable|string',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'maintenance_mode' => 'nullable',
        ];

        // 2. Dynamically validate language fields based on supported locales
        foreach (config('language.supported') as $key => $lang) {
            $rules["$key.site_name"] = 'required|string|max:255';
            $rules["$key.site_description"] = 'nullable|string';
            $rules["$key.copyright"] = 'nullable|string';
        }

        $request->validate($rules);

        // 3. Prepare data for updating
        $data = $request->except(['site_logo', '_token']);
        $data['maintenance_mode'] = $request->has('maintenance_mode') ? 1 : 0;

        // 4. Handle Logo upload to GCS
        if ($request->hasFile('site_logo')) {
            // Delete the old logo if it exists
            if ($setting->site_logo) {
                $this->deleteImageFromGcs($setting->site_logo);
            }
            $upload = $this->uploadImageToGcs($request->file('site_logo'), 'settings');
            $data['site_logo'] = $upload['path'] ?? null;
        }

        // 5. Update settings (Translations are handled automatically by the package)
        $setting->update($data);

        return back()->with('success', __('dashboard.messages.success'));
    }
}
