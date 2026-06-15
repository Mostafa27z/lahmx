<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $method = $request->input('method');
        $query = Payment::with('order');

        if ($method) {
            $query->where('method', $method);
        }

        $payments = $query->latest()->paginate(15);
        return view('admin.payments.index', compact('payments', 'method'));
    }
}
