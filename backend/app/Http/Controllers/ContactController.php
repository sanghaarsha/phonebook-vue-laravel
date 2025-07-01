<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Contact::with('phoneNumbers')->get();

        return response()->json([
            'status' => true,
            'data' => $data,
        ]);
    }

    public function store(StoreContactRequest $request)
    {
        $data = $request->validated();

        $contact = Contact::create([
            'name' => $data['name'],
        ]);

        $contact->phoneNumbers()->create([
            'number' => $data['phone_number'],
        ]);

        return response()->json([
            'status' => true,
            'data' => $contact,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        $contact->load('phoneNumbers');

        return response()->json([
            'status' => true,
            'data' => $contact,
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'prev_phone_number' => 'required|string|min:10|regex:/^(\+977)?[9][6-9]\d{8}$/',
            'new_phone_number' => 'required|string|min:10|regex:/^(\+977)?[9][6-9]\d{8}$/',
        ]);

        $phoneNumber = $contact->phoneNumbers()->where('number', $data['prev_phone_number'])->first();

        if ($phoneNumber) {
            $phoneNumber->update([
                'number' => $data['new_phone_number'],
            ]);
        }

        $contact->update([
            'name' => $data['name'],
        ]);

        return response()->json([
            'status' => true,
            'data' => $contact->fresh()->load('phoneNumbers'),
        ]);
    }

    public function add()
    {
        $request = request();
        $data = $request->validate([
            'id' => 'required',
            'phone_number' => 'required|string|min:10|regex:/^(\+977)?[9][6-9]\d{8}$/',
        ]);

        $contact = Contact::findOrFail($data['id']);

        $phoneNumber = $contact->phoneNumbers()->create([
            'number' => $data['phone_number'],
        ]);

        return response()->json([
            'status' => true,
            'data' => $contact->fresh()->load('phoneNumbers'),
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();
    }
}
