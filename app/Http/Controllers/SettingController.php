<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first() ?? new Setting();

        $currencies = ['BDT', 'USD', 'EUR', 'GBP', 'INR'];

        return view('settings.index', compact('setting', 'currencies'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'currency'      => 'required|string',
            'logo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $setting = Setting::first() ?? new Setting();

        if ($request->hasFile('logo')) {
            if ($setting->logo) {
                Storage::disk('public')->delete($setting->logo);
            }
            $validated['logo'] = $request->file('logo')->store('settings', 'public');
        }

        $setting->fill($validated)->save();

        return redirect()
            ->route('settings.index')
            ->with('success', 'Settings saved successfully.');
    }
}
