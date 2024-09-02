<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Book;
use App\Models\Author;
use App\Models\Country;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $authors = Author::with('books:title')->get();
        $books = Book::count();
        $countries = Country::with('authors')->get();

        return Inertia::render('Dashboard',[
            'authors' => $authors,
            'books' => $books,
            'countries' => $countries
        ]);
    }
}