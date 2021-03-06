<?php

    namespace App\Http\Terranet\Administrator\Modules\Articles;

    use App\Models\Article;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Support\Str;
    use Terranet\Administrator\Collection\Mutable as MutableCollection;
    use Terranet\Administrator\Exception;
    use Terranet\Administrator\Field\Date;
    use Terranet\Administrator\Field\Enum;
    use Terranet\Administrator\Field\Hidden;
    use Terranet\Administrator\Field\Media;
    use Terranet\Administrator\Field\Slug;
    use Terranet\Administrator\Field\Text;
    use Terranet\Administrator\Field\Textarea;

    /**
     * Administrator Resource Page
     *
     * @package Terranet\Administrator
     */
    class Page extends Blog
    {


        /**
         * The module Eloquent model
         *
         * @var string
         */
        protected $model = \App\Models\Article::class;
        protected $with = ['media'];


        public function group(): string
        {
            return "Articles";
        }

        public function title(): string
        {
            return 'Liste Pages';
        }


        /**
         * @throws Exception
         */
        public function form()
        {
            $form = $this->scaffoldForm()
                ->except(['id', 'categorie_id'])
                ->update('extrait', function ($field) {
                    return Textarea::make('Extrait', 'extrait')->tinymcemini();
                })
                ->update('texte', function ($field) {
                    return Textarea::make('Texte', 'texte')->tinymce();
                })
                ->update('nom', function ($field) {
                    return $field->hideOnFormIf(function () {
                        return !auth()->user()->isSuperAdmin;
                    });
                })
                ->update('etat', function ($field) {
                    return Enum::make('Etat')->setOptions($this->model::ETATS)->required();
                })
                ->update('type', function ($field) {
                    return Hidden::make('Type', 'type')->setValue('page');
                })
                ->update('date', function ($field) {
                    return Date::make('Date', 'date')->required();
                })
                ->update('slug', function ($field) {
                    return Slug::make('Slug', 'slug');
                })
                ->update('seo_description', function ($field) {
                    return Text::make('Description', 'seo_description');
                })
                ->update('seo_title', function ($field) {
                    return Text::make('Title', 'seo_title');
                })
                //->add(BelongsTo::make('Cat??gorie','categorie')->useForTitle('nom'))
                ->stack(['etat', 'date'], 'Publication')
                ->stack(['seo_title', 'seo_description', 'slug'], 'SEO - R??f??rencement')
                ->push(Media::make('M??dias', 'medias')->hideOnCreating())
                ->move('medias', 'before:publication')
                ->insert(Media::make('Images Slides', 'slides')
                    ->hideOnCreating()
                    ->hideOnFormIf(function (){
                        if (self::getTheVerb() === 'edit' && $this->getFormValues()->nom === 'Accueil')
                            return false;
                        return true;
                    })
                    ->fromCollection('appartement')
                    ->setTypeForm(1), 'after:medias');


            return $form;
        }

        public function singular(): string
        {
            return 'Vue de la page';
        }

        public function order():int
        {
            return 1;
        }

        public function sortable():array
        {
            return [
                'nom','created_at',
            ];
        }

        public function linkAttributes():array
        {
            return ['icon' => 'fas fa-file-alt', 'id' => $this->url()];
        }

        public function filters(): MutableCollection
        {
            return $this->scaffoldFilters()
                ->except(['nom', 'slug', 'type'])
                ->push(\Terranet\Administrator\Filter\Text::make('Titre', 'titre')/*->enableModes()*/)
                ->push(\Terranet\Administrator\Filter\Enum::make('Etat', 'etat')->setOptions($this->model::ETATS));
        }

        public function setPartQuery(Builder $builder): void
        {
            $builder->where('type', Article::TYPE_PAGE);
        }

    }
