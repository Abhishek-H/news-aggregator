<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPreference;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class UserPreferenceController extends Controller
{
    // Get User Preferences
    public function getPreferences()
    {
        try {
            $preferences = UserPreference::where('user_id', Auth::id())->first();
            return response()->json($preferences ?? [], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve preferences', 'message' => $e->getMessage()], 500);
        }
    }

    // Update User Preferences
    public function updatePreferences(Request $request)
    {
        try {
            $request->validate([
                'sources' => 'nullable|array',
                'categories' => 'nullable|array'
            ]);

            $preferences = UserPreference::updateOrCreate(
                ['user_id' => Auth::id()],
                [
                    'sources' => $request->sources,
                    'categories' => $request->categories
                ]
            );

            return response()->json(['message' => 'Preferences saved successfully!', 'preferences' => $preferences], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update preferences', 'message' => $e->getMessage()], 500);
        }
    }

    // Get Personalized News Based on User Preferences
    public function getPersonalizedNews()
    {
        try {
            $preferences = UserPreference::where('user_id', Auth::id())->first();

            if (!$preferences) {
                return response()->json(['error' => 'No preferences set'], 400);
            }

            $query = Article::query();

            if (!empty($preferences->sources)) {
                $query->whereIn('source', $preferences->sources);
            }

            if (!empty($preferences->categories)) {
                $query->whereIn('category', $preferences->categories);
            }

            return response()->json($query->paginate(10), 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to fetch personalized news', 'message' => $e->getMessage()], 500);
        }
    }
}
