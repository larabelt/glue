<?php

namespace Belt\Glue\Policies;

use Belt\Core\User;
use Belt\Core\Policies\BaseAdminPolicy;
use Belt\Glue\Category;

/**
 * Class CategoryPolicy
 * @package Belt\Glue\Policies
 */
class CategoryPolicy extends BaseAdminPolicy
{
    /**
     * Determine whether the user can view the object.
     *
     * @param  User $auth
     * @return mixed
     */
    public function index(User $auth)
    {
        return true;
    }

    /**
     * Determine whether the user can view the object.
     *
     * @param  User $auth
     * @param  Category $object
     * @return mixed
     */
    public function view(User $auth, $object)
    {
        return true;
    }
}