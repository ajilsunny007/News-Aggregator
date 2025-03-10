<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\UserPreference;
use Illuminate\Http\Request;

class UserPreferenceController extends Controller
{
    public function show(Request $request)
    {
        $preferences = $request->user()->preferences;
        return response()->json($preferences);
    }

    public function update(Request $request)
    {
        $request->validate([
            'preferred_sources' => 'nullable|array',
            'preferred_categories' => 'nullable|array',
            'preferred_authors' => 'nullable|array',
        ]);

        $preferences = UserPreference::updateOrCreate(
            ['user_id' => $request->user()->id],
            $request->only([
                'preferred_sources',
                'preferred_categories',
                'preferred_authors'
            ])
        );

        return response()->json($preferences);
    }
}