<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    /**
     * Display the payment gateway form.
     *
     * @param Request $request
     * @return Factory|View|Application
     */
    public function paymentGatewayForm(Request $request): Factory|View|Application
    {
        // Retrieve the first payment setting from the database
        $paymentSetting = PaymentSetting::first();

        // Render the 'paymentGatewayForm' view, passing the paymentSetting data to it
        return view('admin.paymentGateway.paymentGatewayForm', compact('paymentSetting'));
    }

    /**
     * Save payment gateway settings.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function paymentGatewaySave(Request $request): RedirectResponse
    {
        // Validate incoming request data
        $request->validate([
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'mode' => 'required|string',
        ]);

        // Retrieve the first payment setting from the database
        $paymentSetting = PaymentSetting::first();

        // If no payment setting exists, create a new instance
        if (!$paymentSetting instanceof PaymentSetting) {
            $paymentSetting = new PaymentSetting();
        }

        // Assign input values to payment setting attributes
        $paymentSetting->client_id = $request->input('client_id');
        $paymentSetting->client_secret = $request->input('client_secret');
        $paymentSetting->mode = $request->input('mode');

        // Attempt to save the payment setting
        if (!$paymentSetting->save()) {
            // If saving fails, redirect back with an error message
            return redirect()->back()->with('error', 'Server Error!');
        }

        // If saving succeeds, redirect to the payment gateway settings page with a success message
        return redirect()->route('admin.payment.gateway')->with('success', 'Successfully saved!');
    }

}
