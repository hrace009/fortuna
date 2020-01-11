<?php

namespace App\Http\Controllers\User\Donate;

use App\Models\GoldPackage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Payments as Donate;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;

class DonateController extends Controller
{
    public function __construct()
    {
        $this->middleware('user.forbidden')->only('store');
    }

    public function index()
    {
        $payments = auth()->user()->orders()->whereNotIn('transaction_status', [9])->latest()->paginate(10);

        return OrderResource::collection($payments);
    }

    public function create()
    {
        $order = request()->user()->orders()->created()->first();

        if (! $order) {
            return response()->json([], Response::HTTP_NO_CONTENT);
        }

        return new OrderResource($order);
    }

    public function store(Request $request)
    {
        $request->validate([
            'gold_package' => 'required|exists:gold_packages,package_id',
            'game_account' => 'required|account_owner',
        ]);

        $package = GoldPackage::findByUuid($request->gold_package)->first();

        $order = $request->user()->orders()->create([
            'order_id'           => Str::orderedUuid(),
            'product'            => $package->package_name,
            'game_id'            => $request->game_account['id'],
            'game_login'         => $request->game_account['name'],
            'amount'             => $package->package_price,
            'cash_amount'        => $package->package_gold_amount,
            'transaction_status' => 9,
        ]);

        activity()->performedOn($order)->withProperties(['status' => 'warning'])->log('Pedido realizado pelo usuário.');

        return new OrderResource($order);
    }

    public function show(Donate $donate)
    {
        $this->authorize('view', $donate);

        return new OrderResource($donate);
    }

    public function destroy(Donate $donate)
    {
        $this->authorize('delete', $donate);

        if ((int) $donate->transaction_status !== 9) {
            return response()->json(['error' => 'Nao foi possivel cancelar o pedido.'], Response::HTTP_BAD_REQUEST);
        }

        $donate->delete();
    }
}
