<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Facades\App\Services\Payment\PaymentsFactory;

class PaymentGateway extends Model
{
    public $timestamps = false;

    /**
     * Get gateway by the given slug.
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->whereSlug(str_slug($slug));
    }

    /**
     * Retrieves only enabled gateways.
     */
    public function scopeEnabled($query)
    {
        return $query->whereEnabled(true);
    }

    /**
     * Retrieves only enabled gateways.
     */
    public static function scopeIsEnabled($query)
    {
        return $query->whereEnabled(true);
    }

    /**
     * Retrieves only enabled gateways.
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     *  Handle Payment Gateway's transaction.
     */
    public function payment()
    {
        return PaymentsFactory::make($this->slug);
    }
}
