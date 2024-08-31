<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Country;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::orderBy('country')->get();
        $authors = Author::with('country:id,country', 'books:title')->get();

        return Inertia::render('Authors/Index',[
            'authors' => $authors,
            'countries' => $countries
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:80',
            'last_name' => 'required|max:80',
            'country_id' => 'required|exists:countries,id'
        ]);

        $author = new Author($request->input());
        $author->save();
        return redirect('authors');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $request->validate([
            'name' => 'required|max:80',
            'last_name' => 'required|max:80',
            'country_id' => 'required|exists:countries,id'
        ]);

        $author->update($request->input());
        return redirect('authors');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $author->delete();
        return redirect('authors');
    }
}