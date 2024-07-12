<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentSetting;
use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Srmklive\PayPal\Services\PayPal;
use Exception;

class PaymentController extends Controller
{
    protected $paypal;

    /**
     * @throws Exception
     * @throws \Throwable
     */
    public function __construct(Request $request)
    {
        $this->initializePayPal($request);
    }

    /**
     * Initializes PayPal payment gateway with provided credentials.
     *
     * @param Request $request
     *
     * @throws \Throwable
     */
    protected function initializePayPal(Request $request): void
    {
        // Fetch PayPal credentials from database
        $paypalCredential = PaymentSetting::first();

        if ($paypalCredential instanceof PaymentSetting) {
            // Configuration array for PayPal SDK
            $config = [
                'mode'            => $paypalCredential->mode,
                'sandbox'         => [
                    'client_id'     => $paypalCredential->client_id,
                    'client_secret' => $paypalCredential->client_secret,
                    'app_id'        => '',
                ],
                'live'            => [
                    'client_id'     => $paypalCredential->client_id,
                    'client_secret' => $paypalCredential->client_secret,
                    'app_id'        => '',
                ],
                'payment_action'  => 'Sale',
                'currency'        => 'USD',
                'notify_url'      => '', // Provide your notify URL
                'locale'          => '', // Specify your locale if needed
                'validate_ssl'    => true,
            ];

            // Initialize PayPal SDK with the configuration
            $this->paypal = new PayPal($config);

            // Set API credentials and obtain access token
            $this->paypal->setApiCredentials($config);
            $token = $this->paypal->getAccessToken();
            $this->paypal->setAccessToken($token);
        } else {
            // Throw exception if PayPal credentials are not found
            throw new Exception("PayPal credentials not found for the provided client_id.");
        }
    }

    /**
     * Initiates payment process for a product order using PayPal.
     *
     * @param Request $request
     * @param int $productId
     * @param int $orderId
     * @return Application|\Illuminate\Foundation\Application|RedirectResponse|Redirector
     */
    public function payment(Request $request, int $productId, int $orderId): \Illuminate\Foundation\Application|Redirector|Application|RedirectResponse
    {
        // Fetch the product based on $productId
        $product = Product::find($productId);

        // If product is not found, delete the order and redirect back
        if (!$product instanceof Product) {
            Order::where('id', $orderId)->delete();
            return redirect()->back();
        }

        // Fetch the order based on $orderId
        $order = Order::find($orderId);

        // If order is not found, redirect back
        if (!$order instanceof Order) {
            return redirect()->back();
        }

        // Create an order with PayPal
        $paymentResponse = $this->paypal->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $order->amount
                    ]
                ]
            ],
            "application_context" => [
                "return_url" => route('payment.success'),
                "cancel_url" => route('payment.cancel'),
            ]
        ]);

        // Redirect to PayPal approval URL if order creation is successful
        if ($paymentResponse['status'] == 'CREATED') {
            return redirect($paymentResponse['links'][1]['href']);
        }

        // If something goes wrong, redirect to checkout page with error message
        return redirect()->route('checkout')->with('error', 'Something went wrong.');
    }

    /**
     * Processes successful payment completion callback from PayPal.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function success(Request $request): RedirectResponse
    {
        // Capture the payment order using the PayPal token from the query parameters
        $response = $this->paypal->capturePaymentOrder($request->query('token'));

        // If payment is successfully completed, redirect to home page
        if ($response['status'] == 'COMPLETED') {
            return redirect()->route('home')->with('success', 'Your order has been successfully completed.');
        }

        // If payment fails, redirect to checkout page with an error message
        return redirect()->route('checkout')->with('error', 'Payment failed.');
    }


    /**
     * Displays the cancellation view when the payment process is canceled by the user.
     *
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function cancel(Request $request): Factory|View|\Illuminate\Foundation\Application|Application
    {
        // Return the view 'payment.cancel' to display cancellation message
        return view('payment.cancel');
    }

}
