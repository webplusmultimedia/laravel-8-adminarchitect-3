<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategorie;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index($slug)
    {
        $article = Article::with('media')->pages()->publie()->where('slug', $slug)->firstOrFail();

        return view('article.show', compact('article'));
    }


    public function blogIndex()
    {
        $page = Article::with('media')->where('nom', 'Accueil')->firstOrFail();
        $blogs = Article::blog()->with('media')->publie()->latest()->paginate(10);

        return view('article.blog.liste', compact('page', 'blogs'));
    }

    public function blogDetail($slug)
    {
        $article = Article::with('media')->blog()->publie()->where('slug', $slug)->firstOrFail();

        return view('article.blog.show', compact('article'));
    }


    public function home()
    {
        $article = Article::where('nom','accueil')->with('media')->firstOrFail();
        return view('home',compact('article'));
    }


}
