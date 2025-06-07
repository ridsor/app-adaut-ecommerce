<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'name' => 'required|string|max:100',
            'subject' => 'required|string|max:200',
            'description' => 'required|string|max:500'
        ]);

        Contact::create($validated);

        return back()->with('status', 'Terima kasih sudah menghubungi kami');
    }
}
