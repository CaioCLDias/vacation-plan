<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first(['id', 'secret']);

        return view('welcome', [
            'clientId' => $client->id ?? 'N/A',
            'clientSecret' => $client->secret ?? 'N/A',
            'email' => env('TEST_USER_EMAIL', 'admin@example.com'),
            'password' => env('TEST_USER_PASSWORD', 'password123')
        ]);
    }
}
