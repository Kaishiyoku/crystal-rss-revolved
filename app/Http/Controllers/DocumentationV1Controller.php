<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;

class DocumentationV1Controller extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    public function home()
    {
        $apiEndpointsFile = Jetstream::localizedMarkdownPath('documentation/api_endpoints.md');

        return view('documentation.v1.home', [
            'apiEndpoints' => Str::markdown(file_get_contents($apiEndpointsFile)),
        ]);
    }
}
