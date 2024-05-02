<?php

namespace MiniRestFramework\Storage\Acl;

class PrivateAcl implements AclInterface
{

    function putObject()
    {
        return 'private';
    }
}