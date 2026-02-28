<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request, string $lang)
    {
        $supported = ['en', 'id'];
        if (in_array($lang, $supported)) {
            session(['locale' => $lang]);
        }

        return redirect()->back();
    }
}
