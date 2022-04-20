<?php

namespace App\Http\Controllers;

use App\Enums\ColorTheme;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileEditColorThemeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $theme
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, $theme)
    {
        $data = Validator::make(['theme' => $theme], [
            'theme' => ['required', new EnumValue(ColorTheme::class)],
        ])->validate();

        Auth::user()->update([
            'theme' => Arr::get($data, 'theme'),
        ]);

        return redirect()->back();
    }
}
