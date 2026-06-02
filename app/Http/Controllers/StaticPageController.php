<?php

namespace App\Http\Controllers;

/**
 * Controller StaticPageController
 * Menyajikan halaman statis: Syarat & Ketentuan dan Kebijakan Privasi.
 */
class StaticPageController extends Controller
{
    public function terms()
    {
        return view('static.terms');
    }

    public function privacy()
    {
        return view('static.privacy');
    }
}
