<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;


class CardController extends Controller
{

    public function store(Request $request)
    {
 
        $request->validate([
            'card_number' => 'required|string',
            'card_expiry' => 'required|string',
            'card_cvc' => 'required|string',
        ]);

        // Simulando a tokenização do cartão
        $token = 'fake-token-' . bin2hex(random_bytes(16));

        // Salvar o token do cartão no banco de dados
        $card = Card::create([
            'token' => $token,
            'card_number' => $request->card_number, // Nunca armazene dados reais de cartões
            'card_expiry' => $request->card_expiry,
        ]);

        return response()->json($card, 201);
    }

    // Verificar saldo do cartão
    public function checkBalance(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        // Simulando a verificação do saldo
        $balance = 1000; // Saldo fictício

        return response()->json(['balance' => $balance], 200);
    }
}
