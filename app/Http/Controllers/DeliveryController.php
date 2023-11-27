<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function store(Request $request)
    {
        // Отримання даних з POST-запиту
        $customerName = $request->input('customer_name');
        $phoneNumber = $request->input('phone_number');
        $email = $request->input('email');
        $senderAddress = config('app.sender_address');
        $deliveryAddress = $request->input('delivery_address');

        // Логіка взаємодії з API Нової Пошти

        // Повернення відповіді
        return response()->json(['message' => 'Delivery request received successfully']);
    }
}
