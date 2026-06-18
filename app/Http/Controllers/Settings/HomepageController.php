<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomepageController extends Controller
{
    public function edit()
    {
        return Inertia::render('settings/Homepage', [
            'homepageVariant' => SiteSetting::getValue('homepage_variant', 'classic'),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'homepage_variant' => 'required|in:classic,redesign',
        ]);

        SiteSetting::setValue('homepage_variant', $validated['homepage_variant']);

        return redirect()->route('homepage.edit')
            ->with('success', 'Homepage display setting saved.');
    }
}
