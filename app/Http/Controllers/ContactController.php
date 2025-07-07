<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('name')->paginate(10);
        return Inertia::render('Contacts/Index', [
            'contacts' => $contacts
        ]);
    }

    public function create()
    {
        return Inertia::render('Contacts/Create');
    }

    public function store(StoreContactRequest $request)
{
    // dd($request->all(), $request->validated());

    $validated = $request->validated();
    
    $validated['phone'] = preg_replace('/\D/', '', $validated['phone']);

    Contact::create($validated);

    return redirect()->route('contacts.index')->with('message', 'Contact created successfully!');
}

    public function edit(Contact $contact)
    {
        return Inertia::render('Contacts/Edit', [
            'contact' => $contact
        ]);
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['phone'] = preg_replace('/\D/', '', $validated['phone']);

        $contact->update($validated);

        return redirect()->route('contacts.index')->with('message', 'Contact updated successfully!');
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('contacts.index')->with('message', 'Contact deleted successfully!');
    }
}
