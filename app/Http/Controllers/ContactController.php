<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Http\Requests\ImportContactsRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use SimpleXMLElement;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Contact::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Sorting
        $sortField = $request->get('sort', 'name');
        $sortDirection = $request->get('direction', 'asc');
        
        if (in_array($sortField, ['name', 'phone', 'email', 'company', 'created_at'])) {
            $query->orderBy($sortField, $sortDirection);
        }

        $contacts = $query->paginate(15)->withQueryString();

        return view('contacts.index', compact('contacts', 'sortField', 'sortDirection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request): RedirectResponse
    {
        try {
            $contact = Contact::create($request->validated());

            return redirect()->route('contacts.index')
                ->with('success', 'Contact created successfully.');
        } catch (Exception $e) {
            Log::error('Error creating contact: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the contact.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact): View
    {
        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact): View
    {
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, Contact $contact): RedirectResponse
    {
        try {
            $contact->update($request->validated());

            return redirect()->route('contacts.index')
                ->with('success', 'Contact updated successfully.');
        } catch (Exception $e) {
            Log::error('Error updating contact: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while updating the contact.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        try {
            $contact->delete();

            return redirect()->route('contacts.index')
                ->with('success', 'Contact deleted successfully.');
        } catch (Exception $e) {
            Log::error('Error deleting contact: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'An error occurred while deleting the contact.');
        }
    }

    /**
     * Show the XML import form.
     */
    public function import(): View
    {
        return view('contacts.import');
    }

    /**
     * Process XML import.
     */
    public function processImport(ImportContactsRequest $request): RedirectResponse
    {
        try {
            $file = $request->file('xml_file');
            $xmlContent = file_get_contents($file->getPathname());
            
            // Validate XML structure
            $xml = new SimpleXMLElement($xmlContent);
            
            if (!isset($xml->contact)) {
                return redirect()->back()
                    ->with('error', 'Invalid XML format. The file must contain contact elements.');
            }

            $imported = 0;
            $skipped = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($xml->contact as $contactXml) {
                try {
                    // Extract data from XML
                    $name = trim((string) $contactXml->name);
                    $phone = trim((string) $contactXml->phone);
                    $email = trim((string) $contactXml->email ?? '');
                    $company = trim((string) $contactXml->company ?? '');
                    $address = trim((string) $contactXml->address ?? '');
                    $notes = trim((string) $contactXml->notes ?? '');

                    // Basic validation
                    if (empty($name) || empty($phone)) {
                        $skipped++;
                        $errors[] = "Skipped contact with missing name or phone: {$name}";
                        continue;
                    }

                    // Check for duplicates based on phone number
                    if (Contact::where('phone', $phone)->exists()) {
                        $skipped++;
                        $errors[] = "Skipped duplicate contact: {$name} ({$phone})";
                        continue;
                    }

                    // Create contact
                    Contact::create([
                        'name' => $name,
                        'phone' => $phone,
                        'email' => $email ?: null,
                        'company' => $company ?: null,
                        'address' => $address ?: null,
                        'notes' => $notes ?: null,
                    ]);

                    $imported++;
                } catch (Exception $e) {
                    $skipped++;
                    $errors[] = "Error importing contact {$name}: " . $e->getMessage();
                    Log::error('XML import error for contact: ' . $e->getMessage());
                }
            }

            DB::commit();

            $message = "Import completed: {$imported} contacts imported";
            if ($skipped > 0) {
                $message .= ", {$skipped} skipped";
            }

            $response = redirect()->route('contacts.index')->with('success', $message);

            if (!empty($errors)) {
                $response->with('import_errors', $errors);
            }

            return $response;

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('XML import failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to import XML file: ' . $e->getMessage());
        }
    }

    /**
     * Export contacts as XML.
     */
    public function export(): \Illuminate\Http\Response
    {
        try {
            $contacts = Contact::orderBy('name')->get();

            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><contacts></contacts>');

            foreach ($contacts as $contact) {
                $contactElement = $xml->addChild('contact');
                $contactElement->addChild('name', htmlspecialchars($contact->name));
                $contactElement->addChild('phone', htmlspecialchars($contact->phone));
                
                if ($contact->email) {
                    $contactElement->addChild('email', htmlspecialchars($contact->email));
                }
                
                if ($contact->company) {
                    $contactElement->addChild('company', htmlspecialchars($contact->company));
                }
                
                if ($contact->address) {
                    $contactElement->addChild('address', htmlspecialchars($contact->address));
                }
                
                if ($contact->notes) {
                    $contactElement->addChild('notes', htmlspecialchars($contact->notes));
                }
            }

            $filename = 'contacts_' . date('Y-m-d_H-i-s') . '.xml';

            return response($xml->asXML(), 200, [
                'Content-Type' => 'application/xml',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);

        } catch (Exception $e) {
            Log::error('XML export failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to export contacts: ' . $e->getMessage());
        }
    }
}
