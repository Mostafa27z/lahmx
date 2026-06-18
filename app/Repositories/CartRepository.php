<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartRepository
{
    public function getOrCreateCart(): Cart
    {
        if (Auth::check()) {
            // Logged in user cart
            return Cart::firstOrCreate(['user_id' => Auth::id()]);
        }

        // Session-based cart for guest checkout
        $cartId = Session::get('guest_cart_id');
        
        if ($cartId) {
            $cart = Cart::find($cartId);
            if ($cart) {
                return $cart;
            }
        }

        $cart = Cart::create(['user_id' => null]);
        Session::put('guest_cart_id', $cart->id);
        
        return $cart;
    }

    public function associateCartToUser(int $userId): void
    {
        $guestCartId = Session::get('guest_cart_id');
        if ($guestCartId) {
            $guestCart = Cart::find($guestCartId);
            if ($guestCart) {
                $userCart = Cart::firstOrCreate(['user_id' => $userId]);
                
                // Merge guest items into user cart
                foreach ($guestCart->items as $guestItem) {
                    $existingItem = $userCart->items()
                        ->where('product_id', $guestItem->product_id)
                        ->where('options', $guestItem->options)
                        ->first();
                    if ($existingItem) {
                        $existingItem->quantity += $guestItem->quantity;
                        $existingItem->save();
                    } else {
                        $guestItem->cart_id = $userCart->id;
                        $guestItem->save();
                    }
                }
                
                $guestCart->delete();
                Session::forget('guest_cart_id');
            }
        }
    }

    public function addItem(Product $product, int $quantity = 1, ?string $options = null): CartItem
    {
        $cart = $this->getOrCreateCart();
        
        $item = $cart->items()->where('product_id', $product->id)
            ->where('options', $options)
            ->first();
        
        if ($item) {
            $item->quantity += $quantity;
            $item->price = $product->active_price;
            $item->save();
        } else {
            $item = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->active_price,
                'options' => $options,
            ]);
        }

        return $item;
    }

    public function updateItemQuantity(int $itemId, int $quantity): ?CartItem
    {
        $item = CartItem::find($itemId);
        if ($item && $quantity > 0) {
            $item->quantity = $quantity;
            $item->save();
            return $item;
        }
        
        if ($item && $quantity <= 0) {
            $item->delete();
            return null;
        }

        return null;
    }

    public function removeItem(int $itemId): bool
    {
        $item = CartItem::find($itemId);
        if ($item) {
            return $item->delete();
        }
        return false;
    }

    public function clearCart(): void
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();
    }
}
