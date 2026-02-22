<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ContactWithUsMessage;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = ContactWithUsMessage::query()
            ->latest('id')
            ->paginate(20);

        return view('pages.admin.contact_messages.index', compact('messages'));
    }

    public function destroy(ContactWithUsMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()
            ->route('admin.contact-messages.index')
            ->with('success', 'Message deleted successfully.');
    }
}
