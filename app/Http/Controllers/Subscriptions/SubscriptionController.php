<?php

namespace App\Http\Controllers\Subscriptions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function __construct() {
        $this->middleware(['auth']);
    }

    public function index()
    {
        if(auth()->user()->subscribed('default')){
            return redirect()->route('subscriptions.premium');
        }
        return view('subscriptions.index',[
            'intent' => auth()->user()->createSetupIntent()
        ]);
    }

    public function store(Request $request)
    {
        $request->user()
            ->newSubscription('default', 'price_1KUWGND1a7olz7DGBh7DvZ60')
            ->create($request->token);

            return redirect()->route('subscriptions.premium');
    }

    public function premium()
    {
        if(!auth()->user()->subscribed('default')){
            return redirect()->route('subscriptions.index');
        }
            return view('subscriptions.premium');
    }
}
