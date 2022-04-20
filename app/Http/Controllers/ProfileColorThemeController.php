<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Color\Hex;

class ProfileColorThemeController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $data = $request->validate(availableThemeColorFields()->mapWithKeys(fn(string $colorField) => [$colorField => ['required', 'color_hex']])->toArray());

        collect($data)->each(function ($color, $colorField) use ($request) {
            $request->session()->put('theme.' . Str::replace('_', '-', $colorField), rgbToString(Hex::fromString($color)->toRgb()));
        });

        $request->session()->put('theme.custom', true);

        return redirect()->back();
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $request->session()->forget(availableThemeColorFields()->map(fn(string $colorField) => 'theme.' . Str::replace('_', '-', $colorField))->toArray());
        $request->session()->forget('theme.custom');

        return redirect()->back();
    }
}
