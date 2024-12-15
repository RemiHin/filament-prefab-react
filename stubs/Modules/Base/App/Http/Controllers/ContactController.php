<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Mail\Contact\ContactInformAdminMail;
use App\Mail\Contact\ContactInformRespondentMail;
use App\Settings\ContactSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class ContactController extends Controller
{
    public function submit(ContactRequest $request): RedirectResponse
    {
        $adminName = app(ContactSettings::class)->admin_name;
        $adminEmail = app(ContactSettings::class)->admin_email;

        if ($adminEmail && $adminName) {
            Mail::to($adminEmail, $adminName)
                ->send(new ContactInformAdminMail($request->validated()));
        }

        Mail::to($request->get('email'), $request->get('name'))->send(new ContactInformRespondentMail($request->validated()));

        Session::flash('success');

        return redirect()->back();
    }
}
