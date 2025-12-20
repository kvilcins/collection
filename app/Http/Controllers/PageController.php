<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;
use Inertia\Inertia;

class PageController extends Controller
{
    public function privacy()
    {
        return Inertia::render('Pages/Privacy');
    }

    public function terms()
    {
        return Inertia::render('Pages/Terms');
    }

    public function contact()
    {
        return Inertia::render('Pages/Contact');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'message' => 'required|string|min:10|max:2000',
        ]);

        Mail::to('kategarina94@gmail.com')->send(new ContactFormMail($validated));

        return back()->with('success', 'Message sent successfully! We will get back to you soon.');
    }
}
