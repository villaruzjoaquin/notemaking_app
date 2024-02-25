<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $userId = Auth::user()->id; //This grabs the currently logged user
        // $notes = Note::where('user_id', $userId)->get(); // This shows the index containing notes created by the currently logged user

        // $notes = Note::where('user_id', Auth::id())->latest('updated_at')->paginate(5); // Order by updated at via 'latest'
        
        // $notes = Auth::user()->notes()->latest('updated_at')->paginate(5);
        $notes = Note::whereBelongsTo(Auth::user())->latest('updated_at')->paginate(5);  // Display the user's notes from oldest to newest.
        return view('notes.index')->with('notes', $notes); // notes.index just means we grab index.php from the notes folder
        
        // $notes->each(function($note){
        //     dump($note->title);
        // }); // Creates a function that dumps the note titles
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([

            'title' => 'required|max:120',
            'text' => 'required'
        ]);

        // This is one way of saving information

        Auth::user()->notes()->create([
            'uuid' => Str::uuid(),
            'title' => $request->title,
            'text' => $request->text    
        ]);

        // This is another way of saving information

        // $note = new Note([
        //     'uuid' => Str::uuid(),
        //     'user_id' => Auth::id(),
        //     'title' => $request->title,
        //     'text' => $request->text
        // ]);

        // $note->save();

        return to_route('notes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        // $note = Note::where('uuid', $uuid)->where('user_id', Auth::id())->firstOrFail();  // Select * FROM Note where id = note id ; and user_id = currently logged user id

        if(!$note->user->is(Auth::user())){
            return abort(403); // If another user that does not own the note somehow gets a hold of the uuid, it will send them to the error page. 
        }
        return view('notes.show')->with('note', $note);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        if($note->user_id != Auth::id()){
            return abort(403); // If another user that does not own the note somehow gets a hold of the uuid, it will send them to the error page. 
        }
        return view('notes.edit')->with('note', $note);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {

        if(!$note->user->is(Auth::user())){
            return abort(403); // If another user that does not own the note somehow gets a hold of the uuid, it will send them to the error page. 
        }


        $request->validate([

            'title' => 'required|max:120',
            'text' => 'required'
        ]);

        $note->update([
            'title' => $request -> title,
            'text' => $request -> text
        ]);

        return to_route('notes.show', $note)->with('success', 'Note updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        if($note->user_id != Auth::id()){
            return abort(403); // If another user that does not own the note somehow gets a hold of the uuid, it will send them to the error page. 
        }

        $note->delete();

        return to_route('notes.index')->with('success', 'Note Deleted Successfully');
    }
}
