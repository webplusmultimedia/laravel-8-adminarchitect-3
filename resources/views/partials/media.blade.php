@if($article->hasMedia())
    <div class="img-box">
        <img src="{{ Croppa::url($article->getFirstMediaUrl(),980,400) }}"
             alt="{{ $article->getFirstMedia()->getCustomProperty('titre') }}"/>
    </div>
@endif
