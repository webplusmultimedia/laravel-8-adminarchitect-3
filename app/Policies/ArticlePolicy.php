<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param Admin $admin
     * @return mixed
     */
    public function viewAny(Admin $admin)
    {
        return  $admin->hasRole(3);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param Admin $admin
     * @param Article $article
     * @return mixed
     */
    public function view(Admin $admin, Article $article)
    {
        return  Response::allow();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param Admin $admin
     * @return mixed
     */
    public function create(Admin $admin)
    {
        return $admin->isSuperAdmin()?Response::allow():Response::deny('No way');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param Admin $admin
     * @param Article $article
     * @return mixed
     */
    public function update(Admin $admin, Article $article)
    {
        return $admin->isSuperAdmin()?Response::allow():Response::deny('No way');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param Admin $admin
     * @param Article $article
     * @return mixed
     */
    public function delete(Admin $admin, Article $article)
    {
        if (empty($article->nom))
            return Response::allow();

        return Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param Admin $admin
     * @param Article $article
     * @return mixed
     */
    public function restore(Admin $admin, Article $article)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param Admin $admin
     * @param Article $article
     * @return mixed
     */
    public function forceDelete(Admin $admin, Article $article)
    {
        //
    }
}
