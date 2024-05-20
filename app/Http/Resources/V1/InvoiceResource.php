<?php

namespace App\Http\Resources\V1;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{

    private array $types = ['C' => 'Cartão', 'B' => 'Boleto', 'P' => 'Pix'];
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => [
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'type' => $this->types[$this->type],
            'value' => 'R$ ' . number_format($this->value, 2, ',', '.'),
            'paid' => $this->paid ? 'Pago' : 'Pendente',
            'payment_date' => $this->paid ? Carbon::parse($this->payment_date)->format('d/m/Y H:i:s') : null
        ];
    }
}
