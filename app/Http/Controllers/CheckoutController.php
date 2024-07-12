<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    /**
     * Displays the checkout form for purchasing a specific product.
     *
     * @param Request $request
     * @param int $id
     * @return Application|Factory|View|\Illuminate\Foundation\Application|RedirectResponse
     */
    public function checkoutForm(Request $request, int $id): Factory|View|\Illuminate\Foundation\Application|Application|RedirectResponse
    {
        // Find the product based on the provided $id
        $product = Product::find($id);

        // If product is not found, redirect to the home page
        if (!$product instanceof Product) {
            return redirect()->route('home');
        }

        // Return the checkout form view, passing the $product data to it
        return view('checkout.checkout', compact('product'));
    }

    /**
     * Processes the checkout form submission and creates an order for the product.
     *
     * @param Request $request
     * @param int $id
     * @return RedirectResponse
     */
    public function checkout(Request $request, int $id): RedirectResponse
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        // Find the product based on the provided $id
        $product = Product::find($id);

        // If product is not found, redirect to the home page
        if (!$product instanceof Product) {
            return redirect()->route('home');
        }

        // Create a new order instance and populate its fields
        $order = new Order();
        $order->product_id = $product->id;
        $order->amount = $product->price;
        $order->name = $request->input('name');
        $order->email = $request->input('email');
        $order->phone = $request->input('phone');
        $order->address = $request->input('address');

        // Save the order to the database
        if (!$order->save()) {
            // If order saving fails, redirect back with an error message
            return redirect()->back()->with('error', 'Order cannot be saved');
        }

        // Redirect to the payment page with product_id and order_id parameters
        return redirect()->route('payment', ['product_id' => $product->id, 'order_id' => $order->id]);
    }

}
