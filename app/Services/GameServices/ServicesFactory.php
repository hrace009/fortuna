<?php

namespace App\Services\GameServices;

use InvalidArgumentException;

class ServicesFactory
{
    public function make($type)
    {
        switch ($type) {
            case 'teleport':
                return new Teleport;
            case 'storehousepasswd':
                return new Storehousepasswd;
            default:
                abort(403, 'Unrecognized service.');
            //throw new InvalidArgumentException('Unknow game service.', 401);
        }
    }
}
