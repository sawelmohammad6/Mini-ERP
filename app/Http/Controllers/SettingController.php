<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first() ?? new Setting();

        return view('settings.index', [
            'setting'    => $setting,
            'currencies' => ['BDT', 'USD', 'EUR', 'GBP', 'INR'],
        ]);
    }

    public function update(Request $request)
    {
        try {
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
                $path = $request->file('logo')->store('settings', 'public');
                if ($path === false) {
                    throw new \RuntimeException('Logo upload failed.');
                }
                $validated['logo'] = $path;
            }

            $setting->fill($validated)->save();
        } catch (\Exception $e) {
            Log::error('Settings save failed', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'user_id' => Auth::id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('settings.index')
            ->with('success', 'Settings saved successfully.');
    }
}
