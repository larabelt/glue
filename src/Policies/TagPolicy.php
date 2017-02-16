<?php

namespace Belt\Glue\Policies;

use Belt\Core\User;
use Belt\Core\Policies\BaseAdminPolicy;
use Belt\Glue\Tag;

class TagPolicy extends BaseAdminPolicy
{
    /**
     * Determine whether the user can view the object.
     *
     * @param  User $auth
     * @param  Tag $object
     * @return mixed
     */
    public function view(User $auth, $object)
    {
        return true;
    }
}