<?php

namespace MiniRest\Framework\Storage\Acl;

class PrivateAcl implements AclInterface
{

    function putObject()
    {
        return 'private';
    }
}