<?php

namespace App\Observers;

use App\Models\BankReceiptVoucher;
use App\Models\BookingApplication;
use App\Models\BookingReturn;
use App\Models\ClientInvoice;
use App\Models\ContractorBill;
use App\Models\GoodsReceivedNoteMaster;
use App\Models\Product;
use App\Models\PurchaseMaster;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturnMaster;
use App\Models\PlotPurchaseMaster;
use App\Models\SaleInvoice;
use App\Models\User;
use App\Notifications\ApprovalPendingNotification;
use Illuminate\Database\Eloquent\Model;

class ApprovalEntryObserver
{
    private const APPROVALS = [
        BankReceiptVoucher::class => [
            'type' => 'bank_receipt_voucher',
            'permission' => 'brv-approval.list',
            'pending_statuses' => ['Unverified'],
            'title_en' => 'Bank receipt voucher awaiting approval',
            'title_ur' => 'بینک رسید واؤچر منظوری کا منتظر ہے',
            'route' => 'bank-receipt-voucher.show',
            'reference' => 'BRV',
        ],
        Product::class => [
            'type' => 'product',
            'permission' => 'products.list',
            'pending_statuses' => ['Unverified'],
            'title_en' => 'Product awaiting approval',
            'title_ur' => 'پروڈکٹ منظوری کا منتظر ہے',
            'route' => 'products.show',
            'reference' => 'Product',
        ],
        BookingApplication::class => [
            'type' => 'booking',
            'permission' => 'booking-module.view',
            'pending_statuses' => ['Unverified'],
            'title_en' => 'Booking awaiting approval',
            'title_ur' => 'بکنگ منظوری کی منتظر ہے',
            'route' => 'bookings.show',
            'reference' => 'Booking',
        ],
        BookingReturn::class => [
            'type' => 'booking_return',
            'permission' => 'booking-module.view',
            'pending_statuses' => ['Unverified'],
            'title_en' => 'Booking return awaiting approval',
            'title_ur' => 'بکنگ کی واپسی منظوری کی منتظر ہے',
            'route' => 'bookingReturns.show',
            'reference' => 'Booking Return',
        ],
        PurchaseOrder::class => [
            'type' => 'purchase_order',
            'permission' => 'purchase-module.view',
            'pending_statuses' => ['Unverified'],
            'title_en' => 'Purchase order awaiting approval',
            'title_ur' => 'پرچیز آرڈر منظوری کا منتظر ہے',
            'route' => 'purchase-order.show',
            'reference' => 'Purchase Order',
        ],
        GoodsReceivedNoteMaster::class => [
            'type' => 'goods_received_note',
            'permission' => 'purchase-module.view',
            'pending_statuses' => ['Unverified'],
            'title_en' => 'Goods received note awaiting approval',
            'title_ur' => 'گڈز ریسیوڈ نوٹ منظوری کا منتظر ہے',
            'route' => 'grn.show',
            'reference' => 'GRN',
        ],
        PurchaseMaster::class => [
            'type' => 'purchase_invoice',
            'permission' => 'purchase-module.view',
            'pending_statuses' => ['Unverified'],
            'title_en' => 'Purchase invoice awaiting approval',
            'title_ur' => 'پرچیز انوائس منظوری کا منتظر ہے',
            'route' => 'purchase-invoice.show',
            'reference' => 'Purchase Invoice',
        ],
        PlotPurchaseMaster::class => [
            'type' => 'plot_purchase',
            'permission' => 'purchase-module.view',
            'pending_statuses' => ['Unverified'],
            'title_en' => 'Plot purchase awaiting approval',
            'title_ur' => 'پلاٹ کی خریداری منظوری کی منتظر ہے',
            'route' => 'plot-purchase-invoice.show',
            'reference' => 'Plot Purchase',
        ],
        SaleInvoice::class => [
            'type' => 'sale_invoice',
            'permission' => 'sale-module.view',
            'pending_statuses' => ['Unverified'],
            'title_en' => 'Sale invoice awaiting approval',
            'title_ur' => 'سیل انوائس منظوری کا منتظر ہے',
            'route' => 'sale-invoice.show',
            'reference' => 'Sale Invoice',
        ],
        PurchaseReturnMaster::class => [
            'type' => 'purchase_return',
            'permission' => 'purchase-module.view',
            'pending_statuses' => ['Unverified'],
            'title_en' => 'Purchase return awaiting approval',
            'title_ur' => 'پرچیز ریٹرن منظوری کا منتظر ہے',
            'route' => 'purchase-return.show',
            'reference' => 'Purchase Return',
        ],
        ClientInvoice::class => [
            'type' => 'client_invoice',
            'permission' => 'sale-module.view',
            'pending_statuses' => ['draft'],
            'title_en' => 'Client invoice awaiting approval',
            'title_ur' => 'کلائنٹ انوائس منظوری کا منتظر ہے',
            'route' => 'client-invoices.show',
            'reference' => 'Client Invoice',
        ],
        ContractorBill::class => [
            'type' => 'contractor_bill',
            'permission' => 'construction-module.view',
            'pending_statuses' => ['draft'],
            'title_en' => 'Contractor bill awaiting approval',
            'title_ur' => 'ٹھیکیدار بل منظوری کا منتظر ہے',
            'route' => 'contractor-bills.show',
            'reference' => 'Contractor Bill',
        ],
    ];

    public function created(Model $model): void
    {
        $this->notifyApproversIfPending($model);
    }

    public function updated(Model $model): void
    {
        if ($model->wasChanged('status')) {
            $this->notifyApproversIfPending($model);
        }
    }

    private function notifyApproversIfPending(Model $model): void
    {
        $approval = self::APPROVALS[$model::class] ?? null;
        $status = $model->getAttribute('status');

        if (!$approval || !in_array($status, $approval['pending_statuses'], true)) {
            return;
        }

        $id = $model->getKey();
        $reference = trim($approval['reference'] . ' #' . $id);
        $url = route($approval['route'], $id);
        $approvers = User::permission($approval['permission'])->get();

        foreach ($approvers as $approver) {
            $approver->notify(new ApprovalPendingNotification(
                $approval['title_en'],
                $approval['title_ur'],
                $reference . ' is awaiting your approval.',
                $reference . ' آپ کی منظوری کا منتظر ہے۔',
                $url,
                $approval['type'],
                $id,
            ));
        }
    }
}
