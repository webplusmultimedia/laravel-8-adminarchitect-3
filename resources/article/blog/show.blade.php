@extends('layouts.app')
@section('title', e($article->tagTitle))
@section('description', e($article->tagMetaDescription))

@section('content')

    <!--Breadcrumb START-->
    <div class="breadcrumb-section jarallax pixels-bg" data-jarallax data-speed="0.6"
         style="background: url('{{ url($article->getFirstMediaUrl('default','large')) }}')">
        <div class="container text-center">
            <h1>{{ $article->titre }}</h1>
            {{--<ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="#">Pages</a></li>
                <li><a href="page-services-1.html">Our Services I</a></li>
            </ul>--}}
        </div>
    </div>
    <!--Breadcrumb END-->
    <div class="section-block">
        <div class="container">
            <div class="section-heading text-center">
                <h3 class="semi-bold font-size-35">{{{ $article->titre }}}</h3>
                <div class="section-heading-line line-thin"></div>
                <p>Webplus Multimédia met aux services des sociétés tout un panel d'expertise web dans différent domaine.</p>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="  content">
                        @if ($article->extrait)
                            <p class="headline">{!! $article->extrait !!}</p>
                        @endif
                        {!! $article->texte !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
