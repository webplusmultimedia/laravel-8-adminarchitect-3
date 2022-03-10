<?php

    namespace App\Policies;

    use App\Models\Admin;
    use Illuminate\Auth\Access\HandlesAuthorization;
    use Illuminate\Auth\Access\Response;

    class AdminPolicy
    {
        use HandlesAuthorization;

        public function index(Admin $user)
        {
            if ($user->is_admin_roles)
                return Response::allow();

            return Response::deny('Vous');
        }

        /**
         * Determine whether the user can view any models.
         *
         * @param Admin $user
         * @return mixed
         */
        public function viewAny(Admin $user)
        {
            if ($user->is_admin_roles)
                return Response::allow();

            return Response::deny('Vous');
        }

        /**
         * Determine whether the user can view the model.
         *
         * @param Admin $user
         * @param Admin $model
         * @return mixed
         */
        public function view(Admin $user, Admin $model)
        {
            if ($user->hasRole(Admin::ROLE_SUPER_ADMIN))
                return Response::allow();

            return Response::deny('Vous');
        }

        /**
         * Determine whether the user can create models.
         *
         * @param Admin $user
         * @return mixed
         */
        public function create(Admin $user)
        {
            if ($user->is_admin_roles)
                return Response::allow();

            return Response::deny('Vous');
        }

        /**
         * Determine whether the user can update the model.
         *
         * @param Admin $user
         * @param Admin $model
         * @return mixed
         */
        public function update(Admin $user, Admin $model)
        {

            if (($user->is_admin_roles AND !$model->isSuperAdmin) OR ($user->isSuperAdmin and $model->isSuperAdmin)) {
                return Response::allow();
            }

            return Response::deny('Vous');
        }

        /**
         * Determine whether the user can delete the model.
         *
         * @param Admin $user
         * @param Admin $model
         * @return mixed
         */
        public function delete(Admin $user, Admin $model)
        {
            if ($user->is_admin_roles and !$model->isSuperAdmin)
                return Response::allow();

            return Response::deny('Vous');
        }

        /**
         * Determine whether the user can restore the model.
         *
         * @param Admin $user
         * @param Admin $model
         * @return mixed
         */
        public function restore(Admin $user, Admin $model)
        {
            //
        }

        /**
         * Determine whether the user can permanently delete the model.
         *
         * @param Admin $user
         * @param Admin $model
         * @return mixed
         */
        public function forceDelete(Admin $user, Admin $model)
        {
            //
        }
    }
