<?php

namespace App\Models;

use App\Presenters\PaymentPresenter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laracasts\Presenter\PresentableTrait;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Class Payments.
 * @property \Illuminate\Support\Carbon delivered_at
 * @property int cash_amount
 * @property array game_account
 */
class Payments extends Model
{
    use CausesActivity, LogsActivity, PresentableTrait, SoftDeletes;

    protected $presenter = PaymentPresenter::class;

    protected static $recordEvents = [];

    protected static $logName = 'orders';

    public const STATUS_RESERVED = 'reserved';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    public $guarded = ['id'];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['activities', 'owner'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'data',
        'game_account' => 'array',
    ];

    /**
     * Find an order by the given order identifier.
     *
     * @param [type] $query
     * @param [type] $orderId
     * @return void
     */
    public function scopebyOrderId($query, $orderId)
    {
        return $query->whereOrderId('order_id', $orderId);
    }

    public function scopeCreated($query)
    {
        return $query->where('transaction_status', 9);
    }

    /**
     * Get only order with status of approved.
     *
     * @return void
     */
    public function scopePaid($query)
    {
        return $query->whereTransactionStatus('approved');
    }

    /**
     * Get only order that has been created at 2018.
     *
     * @return void
     */
    public function scopeEighteen($query)
    {
        return $query->whereYear('created_at', 2018);
    }

    /**
     * The order belongs to an user.
     *
     * @return void
     */
    public function owner()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    /**
     * The order is associated to a game account.
     */
    public function game()
    {
        return $this->belongsTo(\App\Game\User::class, 'game_id', 'ID');
    }

    /**
     * Get the package data which order belongs to.
     *
     * @return void
     */
    public function package()
    {
        return $this->belongsTo('App\CashPackage', 'package_id', 'id');
    }

    /**
     * Get the extra properties with the given name.
     *
     * @param string $propertyName
     *
     * @return mixed
     */
    public function getExtraProperty(string $propertyName)
    {
        return array_get(json_decode($this->data, true), $propertyName);
    }

    /**
     * Display bank billet only if billet is not null and
     * if transaction status is waiting for payment (pending).
     */
    public function hasBillet()
    {
        return ! is_null($this->getExtraProperty('billet')) && $this->transaction_status === 'pending';
    }

    public function totalContribution()
    {
        return $this->sum('amount');
    }

    /**
     * Format the given amount.
     *
     * @param int $amount
     * @return string
     */
    public function formatAmount($amount) : string
    {
        $amount = number_format(($amount * 100) / 100, 0, '.', '.');

        return $amount;
    }

    public function formatMoney($amount) : string
    {
        $formatter = new \NumberFormatter('pt-BR', \NumberFormatter::CURRENCY);

        return $formatter->format($amount);
    }

    public function formatGateway() : string
    {
        return __("app.order.gateways.{$this->gateway}");
    }

    /**
     * Check if the cash has been delivered to the user.
     *
     * @return bool
     */
    public function hasBeenDelivered(): bool
    {
        return ! is_null($this->delivered_at);
    }

    public function markAsDelivered() : bool
    {
        $addcash = \App\Game\User::addcash(decodeHashIdentifier($this->game_id), $this->cash_amount);

        if (! $addcash) {
            logger()->warning("[add_cash] CASH for Order [#$this->order_id] wasn't transferred to the user.");

            return false;
        }

        $this->forceFill([
            'delivered_at' => $this->freshTimestamp(),
        ])->save();

        return true;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'order_id';
    }
}
