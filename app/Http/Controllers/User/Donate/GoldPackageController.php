<?php

namespace App\Http\Controllers\User\Donate;

use App\Models\GoldPackage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GoldPackageResource;

class GoldPackageController extends Controller
{
    /**
     * @group package
     *
     * @return void
     */
    public function __invoke()
    {
        $packages = GoldPackage::all();

        return GoldPackageResource::collection($packages);
    }
}
