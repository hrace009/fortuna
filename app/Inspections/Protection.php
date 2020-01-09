<?php

namespace App\Inspections;

class Protection
{
    /**
     * All registered inspection classes.
     *
     * @var array
     */
    protected $inspections = [
        InvalidKeywords::class,
    ];

    /**
     * Detect spam.
     *
     * @param [type] $body
     *
     * @return void
     */
    public function detect($body)
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($body);
        }

        return false;
    }
}
