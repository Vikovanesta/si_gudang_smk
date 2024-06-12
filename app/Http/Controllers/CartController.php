<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * @group Cart
 *
 * APIs for managing cart
 *
 * @subgroup Academic
 *
 * @authenticated
 */
class CartController extends Controller
{
    use HttpResponses;

    public function index(Request $request)
    {
        Gate::authorize('academic');

        $query = $request->query();

        $carts = Cart::filterByQuery($query)->where('user_id', auth()->id())->get();

        return CartResource::collection($carts);
    }

    public function store(Request $request)
    {
        Gate::authorize('academic');

        $cart = Cart::where('user_id', auth()->id())->where('item_id', $request->item_id)->first();

        if ($cart) {
            return $this->error(null, 'Product already in cart', 400);
        }

        $cartCount = Cart::where('user_id', auth()->id())->count();

        if ($cartCount >= 20) {
            return $this->error(null, 'Cart limit reached', 400);
        }

        $cart = Cart::create([
            'user_id' => auth()->id(),
            'item_id' => $request->item_id
        ]);

        return $this->success(new CartResource($cart), 'Product added to cart successfully');
    }

    public function destroy(Request $request, $id)
    {
        Gate::authorize('academic');

        $item = Cart::where('user_id', auth()->id())->where('item_id', $id)->first();

        if (!$item) {
            return $this->error(null, 'Item not found in cart', 404);
        }

        $item->delete();

        return $this->success(null, 'Item removed from cart successfully');
    }
}
