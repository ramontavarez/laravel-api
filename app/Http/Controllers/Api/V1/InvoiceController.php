<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\InvoiceResource;
use App\Models\Invoice;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    use HttpResponses;
    private $types = ['B', 'C', 'P'];
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return InvoiceResource::collection(Invoice::with('user')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|max:1|in:' . implode(',', $this->types),
            'paid' => 'boolean',
            'payment_date' => 'nullable|date',
            'value' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', $validator->errors(), 422);
        }

        $created = Invoice::create($validator->validated());

        return $this->success('Invoice created successfully', [new InvoiceResource($created)]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $payment_date_rule = $request->get('paid') ? 'required' : 'nullable';

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|max:1|in:' . implode(',', $this->types),
            'paid' => 'required|boolean',
            'payment_date' => $payment_date_rule . '|date_format:Y-m-d H:i:s',
            'value' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', $validator->errors(), 422);
        }

        $validated = $validator->validated();

        $updated = $invoice->update([
            'user_id' => $validated['user_id'],
            'type' => $validated['type'],
            'paid' => $validated['paid'],
            'payment_date' => $validated['paid'] ? $validated['payment_date'] : null,
            'value' => $validated['value'],
        ]);

        if ($updated) {
            return $this->success('Invoice updated successfully', [new InvoiceResource($invoice)]);
        }

        return $this->error('Invoice not updated', [], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        $deleted = $invoice->delete();

        if ($deleted) {
            return $this->success('Invoice deleted successfully', []);
        }

        return $this->error('Invoice not deleted', [], 400);
    }
}
