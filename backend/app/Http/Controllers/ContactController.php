<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contact::query();

        if ($request->has('search')) {
            $searchTerm = '%'.$request->input('search').'%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhereHas('phoneNumber', function ($subQuery) use ($searchTerm) {
                        $subQuery->where('number', 'like', $searchTerm);
                    });
            });
        }

        $data = $query->with('phoneNumber')->paginate(10);

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|min:10|regex:/^(\+977)?[9][6-9]\d{8}$/',
        ]);

        $contact = Contact::create([
            'name' => $data['name'],
        ]);

        $contact->phoneNumber()->create([
            'number' => $data['number'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Contact created successfully',
            'data' => $contact->load('phoneNumber'),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        $data = $contact->load('phoneNumber');

        return response()->json([
            'status' => true,
            'message' => 'Contact fetched successfully',
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'old_number' => 'required|string|min:10|regex:/^(\+977)?[9][6-9]\d{8}$/',
            'new_number' => 'required|string|min:10|regex:/^(\+977)?[9][6-9]\d{8}$/',
        ]);

        if (isset($data['name'])) {
            $contact->update([
                'name' => $data['name'],
            ]);
        }

        $contact->phoneNumber()->where('number', $data['old_number'])->update([
            'number' => $data['new_number'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Contact updated successfully',
            'data' => $contact->load('phoneNumber'),
        ]);
    }

    /**
     * Add a phone number to already existing contact
     */
    public function add(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'number' => 'required|string|min:10|regex:/^(\+977)?[9][6-9]\d{8}$/',
        ]);

        $contact->phoneNumber()->create([
            'number' => $data['number'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'New number added to the contact',
            'data' => $contact->load('phoneNumber'),
        ]);
    }

    /**
     * Remove a phone number from an already existing contact
     */
    public function remove(Request $request, Contact $contact)
    {
        $data = $request->validate([
            'number' => 'required|string|min:10|regex:/^(\+977)?[9][6-9]\d{8}$/',
        ]);

        $contact->phoneNumber()->where('number', $data['number'])->delete();

        return response()->json([
            'status' => true,
            'message' => 'Number deleted from the contact',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json([
            'status' => true,
            'message' => 'Contact deleted successfully',
        ], 200);
    }
}
