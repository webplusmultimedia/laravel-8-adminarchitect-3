<?php
namespace App\Http\Controllers;



use App\Http\Requests\SendContactRequest;


use App\Mail\ContactMail;
use App\Models\Article;

use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{

    public function index()
    {
        $article = Article::where('nom', 'Contact')->firstOrFail();


        return view('contact.index', compact('article'));
    }

    public function send(SendContactRequest $request)
    {

        Mail::send(new ContactMail($request));

        return back()->with(['success'=>"Nous vous remercions pour votre demande <br>
                            Votre demande sera traité par un de nos collaborateurs très rapidement. <br>
                            A très bientôt."]);
    }



}
