<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Requests\StoreTicketRequest;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $tickets = Ticket::all();
        return view('ticket.index', ['tickets' => $tickets]); //or
        // return view('ticket.index')->with('tickets', $tickets);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('ticket.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request) {
        $ticket = Ticket::create([
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->id(),
        ]);

        if($request->file('attachment')){
            $this->addAttachment($request, $ticket); //Fucntion declared down.
        }

        return response()->redirectToRoute('ticket.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket) {
        return view('ticket.show')->with('ticket', $ticket);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket) {
        return view('ticket.edit')->with('ticket', $ticket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket) {
        $ticket->update($request->except('attachment'));

        if($request->file('attachment')){
            Storage::disk('public')->delete($ticket->attachment); //Deleting the old file before update.
            $this->addAttachment($request, $ticket); //Function declared down.
        }

        return response()->redirectToRoute('ticket.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket) {
        $ticket->delete();
        return response()->redirectToRoute('ticket.index');
    }

    // Add attachment function
    protected function addAttachment ($request, $ticket) {
        $ext = $request->file('attachment')->extension();
        $contents = file_get_contents($request->file('attachment'));
        $filename = Str::random(25);
        $path = "attachments/$filename.$ext";
        Storage::disk('public')->put($path, $contents);
        // if there file then:
        $ticket->update(['attachment' => $path]);
    }
}
