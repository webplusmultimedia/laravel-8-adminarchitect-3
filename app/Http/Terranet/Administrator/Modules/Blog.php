<?php

    namespace App\Http\Terranet\Administrator\Modules;

    use App\Models\Article;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Support\Str;
    use Terranet\Administrator\Collection\Mutable as MutableCollection;
    use Terranet\Administrator\Contracts\Module\Editable;
    use Terranet\Administrator\Contracts\Module\Exportable;
    use Terranet\Administrator\Contracts\Module\Filtrable;
    use Terranet\Administrator\Contracts\Module\Navigable;
    use Terranet\Administrator\Contracts\Module\Sortable;
    use Terranet\Administrator\Contracts\Module\Validable;
    use Terranet\Administrator\Field\BelongsTo;
    use Terranet\Administrator\Field\Date;
    use Terranet\Administrator\Field\Enum;
    use Terranet\Administrator\Field\Hidden;
    use Terranet\Administrator\Field\LinkText;
    use Terranet\Administrator\Field\Media;
    use Terranet\Administrator\Field\Slug;
    use Terranet\Administrator\Field\Text;
    use Terranet\Administrator\Field\Textarea;

    use Terranet\Administrator\Scaffolding;
    use Terranet\Administrator\Traits\Module\AllowFormats;
    use Terranet\Administrator\Traits\Module\AllowsNavigation;
    use Terranet\Administrator\Traits\Module\HasFilters;
    use Terranet\Administrator\Traits\Module\HasForm;
    use Terranet\Administrator\Traits\Module\HasPartQuerying;
    use Terranet\Administrator\Traits\Module\HasSortable;
    use Terranet\Administrator\Traits\Module\ValidatesForm;

    /**
     * Administrator Resource Page
     *
     * @package Terranet\Administrator
     */
    class Blog extends Scaffolding implements Navigable, Filtrable, Editable, Validable, Sortable
    {
        use HasFilters, HasForm, HasSortable, ValidatesForm, AllowFormats, AllowsNavigation;

        /**
         * The module Eloquent model
         *
         * @var string
         */
        protected $model = \App\Models\Article::class;
        protected $with = ['media'];
        protected $showActions = false;


        public function group()
        {
            return "Articles";
        }

        public function title(): string
        {
            return 'Blog';
        }
        public function singular()
        {
            return 'Blog';
        }



        public function columns(): MutableCollection
        {

            return $this->scaffoldColumns()
                ->except(['id', 'extrait', 'texte', 'type', 'date', 'slug', 'categorie_id', 'nom', 'seo_description', 'seo_title'])
                ->add(Date::make('Créer le', 'created_at')->setDateFormat('d/m/Y'))
                ->updateMany([
                    'slug' => function ($field) {
                        return LinkText::make('Lien', 'slug')->setRoute("page.index", ['slug']);
                    },
                    'date' => function ($field) {
                        return Date::make('Date', 'date')->setDateFormat('d/m/Y')->hideOnIndex();
                    },

                    'etat'  => function ($field) {
                        return Enum::make('Etat')->setOptions($this->model::ETATS)->palette(['publie' => '#04a5bf', 'brouillon' => "rgb(245, 116, 2)"]);
                    },
                    'titre' => function ($field) {
                        return $field->onlyOnView();
                    }
                ])
                ->add('Nom', function (Text $field) {
                    return $field->setId('nomTitle');
                })
                ->move('created_at', 1)
                ->move('titre', 3)
                ->move('etat', 4)
                ->push(Media::make('images')->setParentModuleName($this->url())->setWidthAndHeightForThumb(80, 50));
        }


        public function viewColumns(): MutableCollection
        {
            return $this->columns();
        }

        public function form()
        {
            $form = $this->scaffoldForm()
                ->except(['id', 'categorie_id','nom'])
                ->update('extrait', function ($field) {
                    return Textarea::make('Extrait', 'extrait')->tinymcemini();
                })
                ->update('texte', function ($field) {
                    return Textarea::make('Texte', 'texte')->tinymce();
                })
                ->update('etat', function ($field) {
                    return Enum::make('Etat')->setOptions($this->model::ETATS)->required();
                })
                ->update('type', function ($field) {
                    return Hidden::make('Type', 'type')->setValue('blog');
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
                //->add(BelongsTo::make('Catégorie','categorie')->useForTitle('nom'))
                ->stack(['etat', 'date'], 'Publication')
                ->stack(['seo_title', 'seo_description', 'slug'], 'SEO - Référencement')
                ->push(Media::make('Médias', 'medias')->hideOnCreating())
                ->move('medias', 'before:publication')
                ->insert(Media::make('Images Appartement', 'appartement')
                    ->hideOnCreating()
                    ->hideOnFormIf(function (){
                        if(self::getTheVerb()=='edit' && $this->getFormValues()->nom=='Accueil')
                            return false;
                        return true;
                    })
                    ->fromCollection('appartement')
                    ->setTypeForm(1), 'after:medias');


            return $form;
        }



        public function order()
        {
            return 2;
        }

        public function rules()
        {
            $discovered = $this->scaffoldRules();
            $discovered = array_merge($discovered, [
                'slug' => Str::replaceFirst('required', 'nullable', $discovered['slug'])
            ]);

            return $discovered;
        }

        public function sortable()
        {
            return [

                'created_at',
                'titre',
                /*'categorie' => function ($query, $element, $direction) {
                    return $query->join('post_categories', function ($join) {
                        $join->on('post_categories.id', '=', 'posts.post_category_id');
                    })->orderBy("post_categories.slug", $direction);
                },*/
            ];
        }

        public function linkAttributes()
        {
            return ['icon' => 'fas fa-blog', 'id' => $this->url()];
        }

        public function filters()
        {
            return $this->scaffoldFilters()
                ->except(['nom', 'slug', 'type'])
                ->push(\Terranet\Administrator\Filter\Text::make('Titre', 'titre')/*->enableModes()*/)
                ->push(\Terranet\Administrator\Filter\Enum::make('Etat', 'etat')->setOptions($this->model::ETATS))
                /*->push(\Terranet\Administrator\Filter\Enum::make('Tags')
                    ->setOptions(\App\Models\Tag::all()->pluck('name', 'id')->toArray())
                    ->setQuery(function (Builder $builder, $value) {
                        return $builder
                            ->whereHas('tags',function (Builder $q) use ($value) {
                                $q->where('tag_id',$value);
                            });
                    })
                )*/;
        }

        public function setPartQuery(Builder $builder): void
        {
            $builder->where('type', Article::TYPE_BLOG);
        }

    }
