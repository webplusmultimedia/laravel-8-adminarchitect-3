@extends('layouts.website')
@section('title', e($article->tagTitle))
@section('description', e($article->tagMetaDescription))

@section('content')

    <section class="inner-banner"
             style="background: url({{ Croppa::url($article->getFirstMediaUrl(),1200,800)}}) rgb(12,71,91) center center; background-size: cover;">
        <div class="thm-container text-center">
            <h2 style="font-size: 45px">Contactez-nous</h2>

        </div><!-- /.thm-container -->
    </section><!-- /.inner-banner -->

    <section class="contact-form-area">
        <div class="thm-container">
            <div class="title text-center">
                <h3>Laisser nous un message</h3>
                <p>Praising pain was born and I will give you a complete account of the system,</p>
                <div class="line"></div><!-- /.line -->
            </div><!-- /.title -->
            @include('partials.alert')
            {{ Form::open(['route'=>'contact.send','class'=>"contact-form"]) }}

            <div class="row">
                <div class="col-md-4">
                    {{ Form::text('nom',null,['placeholder' => 'Nom *']) }}
                </div><!-- /.col-md-4 -->
                <div class="col-md-4">
                    {{ Form::email('email',null,['placeholder' => 'Email *']) }}
                </div><!-- /.col-md-4 -->
                <div class="col-md-4">
                    {{ Form::text('telephone',null,['placeholder' => 'Téléphone *']) }}
                </div><!-- /.col-md-4 -->
                <div class="col-md-12">
                    {{ Form::select('subject',$services->pluck('titre','titre')->toArray()+['Autre'=>'Autre'],null,['class'=>'form-control mb10']) }}
                </div><!-- /.col-md-4 -->
                <div class="col-md-12">
                    {{ Form::textarea('msg',null,['placeholder' => 'Message *']) }}
                    <button type="submit">Envoyer</button>
                </div><!-- /.col-md-12 -->
                <div class="form-result"></div><!-- /.form-result -->
            </div><!-- /.row -->
        {{ Form::close() }}<!-- /.contact-form -->
        </div><!-- /.thm-container -->
    </section><!-- /.contact-form-area -->

    <section class="contact-page-info ">
        <div class="thm-container">
            <div class="row">
                <div class="col-md-4 col-sm-6 col-xs-12">
                    <div class="single-contact-info">
                        <div class="icon-box">
                            <img src="{{ asset('img/address-icon.png')}}" alt="Awesome Image"/>
                        </div><!-- /.icon-box -->
                        <h3>Adresse</h3>
                        <p>{{ options_find('adresse_1') }} <br/>{{ options_find('adresse') }}</p>
                    </div><!-- /.single-contact-info -->
                </div><!-- /.col-md-3 -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="single-contact-info">
                        <div class="icon-box">
                            <img src="{{ asset('img/phone-icon.png')}}" alt="Awesome Image"/>
                        </div><!-- /.icon-box -->
                        <h3>Téléphones</h3>
                        <p>{{ options_find('telephone') }}<br/>{{ options_find('telephone_secondaire') }}</p>
                    </div><!-- /.single-contact-info -->
                </div><!-- /.col-md-3 -->

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="single-contact-info">
                        <div class="icon-box">
                            <img src="{{ asset('img/share-icon.png')}}" alt="Awesome Image"/>
                        </div><!-- /.icon-box -->
                        <h3>Nous suivre</h3>
                        <div class="social">
                            <a href="{{ options_find('facefook') }}" class="fa fa-facebook"></a>
                            <a href="{{ options_find('instagram') }}" class="fa fa-instagram"></a>
                        </div><!-- /.social -->
                    </div><!-- /.single-contact-info -->
                </div><!-- /.col-md-3 -->
            </div><!-- /.row -->
        </div><!-- /.thm-container -->
    </section><!-- /.contact-info -->

<section class="carte-info">
    <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15440.329614317241!2d-60.9300785!3d14.6512636!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xeb437078e22d2cd4!2sMAC-G%20SERVICES!5e0!3m2!1sfr!2sfr!4v1636548740073!5m2!1sfr!2sfr" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
</section>



@stop
