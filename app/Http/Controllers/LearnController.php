<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class LearnController extends Controller
{
    public function learn(Request $request)
{
    $selectedCategory = $request->category ?? 'All';
    $search = $request->search;

    $query = Article::with('author')->where('is_published', true);

    // Filter kategori
    if ($selectedCategory !== 'All') {
        $query->where('category', $selectedCategory);
    }

    // Search
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%$search%")
              ->orWhere('excerpt', 'like', "%$search%");
        });
    }

    $articles = $query->latest()->get();
    // $articles = $query->latest()->paginate(6);

    // Ambil kategori unik dari database
    $articleCategories = ['All'];
    $dbCategories = Article::select('category')
        ->whereNotNull('category')
        ->distinct()
        ->pluck('category')
        ->toArray();

    $articleCategories = array_merge($articleCategories, $dbCategories);

    // Featured
    $featured = $articles->first();
    $rest = $articles->skip(1);

    return view('learn', compact(
        'articles',
        'featured',
        'rest',
        'articleCategories',
        'selectedCategory',
        'search'
    ));
}

public function show($slug)
{
    $article = Article::with('author')
        ->where('slug', $slug)
        ->where('is_published', true)
        ->firstOrFail();

    return view('learn-detail', compact('article'));
}
}
