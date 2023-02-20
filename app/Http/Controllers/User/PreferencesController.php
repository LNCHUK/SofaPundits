<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserPreferencesRequest;
use App\Models\Preference;
use App\Models\UserPreference;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();

        try {
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

            // Flush the cache to ensure changes are recognised
            cache()->forget('user_preferences-' . auth()->id());
        } catch (\Exception $ex) {
            report($ex);

            DB::rollBack();

            return redirect()->back()
                ->with('error', 'There was an error updating your preferences. Please try again, and if the 
                    issue continues notify an administrator');
        }

        DB::commit();

        return redirect()->route('user.preferences')
            ->with('success', 'Your preferences have been updated successfully!');
    }
}
