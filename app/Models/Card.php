<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;


    protected $fillable = [
        'token', 'card_number', 'card_expiry','cvv', 'amount'
    ];

    public function getCard(
        string $card_number,
        string $cvv,
        string $card_expiry
    ) {
         try {
            return  Card::where('card_number', $card_number )
                ->where('cvv', $cvv )
                ->where('card_expiry', $card_expiry)
                ->first();
        } catch (Exception $e) {
            return ($e->getMessage());
        }
    }
}
