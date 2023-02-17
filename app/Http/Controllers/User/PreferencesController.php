<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserPreferencesRequest;
use App\Models\Preference;
use App\Models\UserPreference;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PreferencesController extends Controller
{
    /**
     * @return Renderable
     */
    public function index(): Renderable
    {
        $userPreferences = auth()->user()->getPreferences();

        return view('user.preferences', compact('userPreferences'));
    }

    /**
     * @param UpdateUserPreferencesRequest $request
     * @return RedirectResponse
     */
    public function update(UpdateUserPreferencesRequest $request): RedirectResponse
    {
        foreach ($request->all() as $slug => $value) {
            $preference = Preference::query()->where('slug', $slug)->first();

            if ($preference) {
                UserPreference::updateOrCreate([
                    'user_id' => auth()->id(),
                    'preference_id' => $preference->id,
                ], [
                    'preference_slug' => $preference->slug,
                    'value' => $value
                ]);
            }
        }

        return redirect()->route('user.preferences');
    }
}
