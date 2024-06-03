<?php

namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
     // Criar um pagamento
     public function createPayment(Request $request)
     {
         $request->validate([
             'amount' => 'required|numeric|min:0.50',
             'currency' => 'required|string|size:3',
             'payment_method' => 'required|string|in:card,transfer',
             'customer_email' => 'required|email',
             'description' => 'nullable|string',
         ]);
 
         // Criar um pagamento
         $payment = Payment::create([
             'transaction_id' => Str::uuid(),
             'amount' => $request->amount,
             'currency' => $request->currency,
             'status' => 'pending',
             'payment_method' => $request->payment_method,
             'customer_email' => $request->customer_email,
             'description' => $request->description,
         ]);
 
         try {
             if ($request->payment_method == 'card') {
                 // Simulando uma resposta do gateway de pagamento
                 $response = $this->simulateCardPayment($payment);
             } else if ($request->payment_method == 'transfer') {
                 // Simular a verificação de pagamento por transferência
                 $response = $this->simulateBankTransfer($payment);
             }
 
             if ($response['status'] == 'success') {
                 $payment->status = 'completed';
             } else {
                 $payment->status = 'failed';
             }
 
             $payment->save();
             return response()->json($payment, 201);
         } catch (\Exception $e) {
             // Handle exception
             $payment->status = 'failed';
             $payment->save();
             return response()->json(['error' => 'Payment failed', 'message' => $e->getMessage()], 500);
         }
     }
 
     // Simular pagamento com cartão
     private function simulateCardPayment($payment)
     {
         // Lógica fictícia para simular o pagamento com cartão
         // Sucesso se o valor for menor que 1000
         if ($payment->amount < 1000) {
             return ['status' => 'success', 'transaction_id' => $payment->transaction_id];
         } else {
             return ['status' => 'failed', 'transaction_id' => $payment->transaction_id];
         }
     }
 
     // Simular verificação de pagamento por transferência bancária
     private function simulateBankTransfer($payment)
     {
         // Lógica fictícia para simular a verificação de transferência bancária
         // Sucesso se o valor for menor que 1000
         if ($payment->amount < 1000) {
             return ['status' => 'success', 'transaction_id' => $payment->transaction_id];
         } else {
             return ['status' => 'failed', 'transaction_id' => $payment->transaction_id];
         }
     }
 
     // Listar todos os pagamentos
     public function listPayments()
     {
         $payments = Payment::all();
         return response()->json($payments);
     }
 
     // Atualizar o status do pagamento
     public function updatePaymentStatus(Request $request, $transaction_id)
     {
         $request->validate([
             'status' => 'required|string|in:pending,completed,failed',
         ]);
 
         $payment = Payment::where('transaction_id', $transaction_id)->firstOrFail();
         $payment->status = $request->status;
         $payment->save();
 
         return response()->json($payment);
     }
 
     // Receber notificações do gateway de pagamento
     public function paymentNotification(Request $request)
     {
         $request->validate([
             'transaction_id' => 'required|string',
             'status' => 'required|string|in:pending,completed,failed',
         ]);
 
         // Obter o pagamento com base no transaction_id
         $payment = Payment::where('transaction_id', $request->transaction_id)->first();
 
         if ($payment) {
             // Atualizar o status do pagamento com base na notificação
             $payment->status = $request->status;
             $payment->save();
 
             // Retornar uma resposta de sucesso
             return response()->json(['message' => 'Payment status updated successfully'], 200);
         } else {
             // Retornar uma resposta de erro se o pagamento não for encontrado
             return response()->json(['error' => 'Payment not found'], 404);
         }
     }

  
}
