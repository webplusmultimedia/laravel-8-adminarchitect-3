@extends('layouts.app')
@section('title', e($page->seo_title))
@section('description', e($page->seo_description))

@section('content')
    <div class="breadcrumb-section jarallax pixels-bg" data-jarallax data-speed="0.6"
         style="background: url('{{ url($page->getFirstMediaUrl('default','large')) }}')">
        <div class="container text-center">
            <h1>Articles</h1>
            {{--<ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="#">Pages</a></li>
                <li><a href="page-services-1.html">Our Services I</a></li>
            </ul>--}}
        </div>
    </div>
    <div class="section-block grey-bg">
        <div class="background-shape bs-right"></div>
        <div class="container">
            <div class="section-heading text-center">
                <h3 class="semi-bold">Plus de 25 ans d'expériences dans le développement WEB</h3>
                <div class="section-heading-line line-thin"></div>
                <p>L'innovation est au coeur de notre métier</p>
            </div>
            <div class="row mt-40">
                @include('partials.blogitem')

            </div>

            <div class="mt-25">
                {{ $blogs->links() }}
            </div>

        </div>
    </div>
@stop
