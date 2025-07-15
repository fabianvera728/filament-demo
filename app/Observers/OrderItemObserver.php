<?php

namespace App\Observers;

use App\Models\OrderItem;

class OrderItemObserver
{
    /**
     * Handle the OrderItem "creating" event.
     */
    public function creating(OrderItem $orderItem): void
    {
        $this->calculateItemTotal($orderItem);
    }

    /**
     * Handle the OrderItem "created" event.
     */
    public function created(OrderItem $orderItem): void
    {
        $this->updateOrderTotals($orderItem);
    }

    /**
     * Handle the OrderItem "updating" event.
     */
    public function updating(OrderItem $orderItem): void
    {
        $this->calculateItemTotal($orderItem);
    }

    /**
     * Handle the OrderItem "updated" event.
     */
    public function updated(OrderItem $orderItem): void
    {
        $this->updateOrderTotals($orderItem);
    }

    /**
     * Handle the OrderItem "deleted" event.
     */
    public function deleted(OrderItem $orderItem): void
    {
        $this->updateOrderTotals($orderItem);
    }

    /**
     * Calculate the total price for this item
     */
    private function calculateItemTotal(OrderItem $orderItem): void
    {
        $orderItem->total_price = $orderItem->quantity * $orderItem->unit_price;
    }

    /**
     * Update the order totals based on all items
     */
    private function updateOrderTotals(OrderItem $orderItem): void
    {
        $order = $orderItem->order;
        
        if (!$order) {
            return;
        }

        // Calculate subtotal from all order items
        $subtotal = $order->items()->sum('total_price');
        
        // Calculate total amount (subtotal + tax + shipping - discount + tip)
        $totalAmount = $subtotal + 
                      ($order->tax_amount ?? 0) + 
                      ($order->shipping_amount ?? 0) + 
                      ($order->tip_amount ?? 0) - 
                      ($order->discount_amount ?? 0);

        // Update order totals
        $order->update([
            'subtotal' => $subtotal,
            'total_amount' => $totalAmount,
        ]);
    }
}
