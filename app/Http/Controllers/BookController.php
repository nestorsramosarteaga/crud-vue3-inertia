<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Author;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{

    const EXAMPLE_IMAGE = '/img/example.png';

    public function index()
    {
        $books = Book::paginate(10);

        return Inertia::render('Books/Index', [
            'books' => $books
        ]);

    }

    public function create()
    {
        $authors = Author::orderBy('id')->get();

        return Inertia::render('Books/Create', [
            'authors' => $authors
        ]);
    }

    public function store(Request $request)
    {

        $request->validate([
            'isbn' => 'required|max:13',
            'description' => 'required|max:200',
            'title' => 'required|max:100',
            'publisher' => 'required|max:100',
            'release_date' => 'required|date',
            'pages' => 'required|numeric',
            'image' => 'required|file|mimes:png,jpg,jpeg,webp,gif',
            'authors' => 'required|array',
        ]);

        $book = Book::create($request->all());

        if ( $request->hasFile('image') ) {
            $imgName = microtime(true).'.'.$request->file('image')
                ->getClientOriginalExtension();
            $request->file('image')->storeAs('public/img', $imgName);
            $book->image = '/img/' . $imgName;
            $book->save();
        }
        $book->authors()->sync($request->authors);

        return redirect('books/create')->with('success','Book created!');
    }


    public function updateBook(Request $request)
    {
        $request->validate([
            'isbn' => 'required|max:13',
            'description' => 'required|max:200',
            'title' => 'required|max:100',
            'publisher' => 'required|max:100',
            'release_date' => 'required|date',
            'pages' => 'required|numeric',
            'id' => 'required|numeric',
            'authors' => 'required|array',
        ]);

        $book = Book::find($request->id);
        $book->update($request->input());

        if ( $request->hasFile('image') ) {
            // dd($book->image,$request->file('image'));
            if ( $book->image !== self::EXAMPLE_IMAGE )
                Storage::disk('public')->delete($book->image);

            $imgName = microtime(true).'.'.$request->file('image')
                ->getClientOriginalExtension();
            $request->file('image')->storeAs('public/img', $imgName);
            $book->image = '/img/' . $imgName;
            $book->save();
        }
        $book->authors()->sync($request->authors);

        return redirect('books')->with('success','Book updated!');

    }

    public function show(Book $book){
        return Inertia::render('Books/Show', [
            'book' => $book,
            'authors' => $book->authors
        ]);
    }

    public function edit(Book $book)
    {
        $authors = Author::orderBy('id')->get();

        return Inertia::render('Books/Edit', [
            'book' => $book,
            'authors' => $authors,
            'authorsOfBook' => $book->authors
        ]);
    }

    public function update(Request $request, Book $book)
    {
        //
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect('books')
            ->with('success', 'Book deleted!!!');
    }

}