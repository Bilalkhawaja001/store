<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequisition;
use App\Models\StockIssue;
use App\Models\StockReceipt;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@materialtracking.local'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('admin12345'),
                'email_verified_at' => now(),
            ]
        );

        $electrical = Category::updateOrCreate(['name' => 'Electrical'], ['description' => 'Electrical maintenance materials', 'is_active' => true]);
        $civil = Category::updateOrCreate(['name' => 'Civil'], ['description' => 'Civil and structural materials', 'is_active' => true]);

        $cable = Item::updateOrCreate(
            ['item_code' => 'ITM-0001'],
            [
                'name' => 'Copper Cable 4mm',
                'category_id' => $electrical->id,
                'unit' => 'Meter',
                'minimum_stock_level' => 50,
                'description' => 'Standard copper cable',
                'is_active' => true,
            ]
        );

        $cement = Item::updateOrCreate(
            ['item_code' => 'ITM-0002'],
            [
                'name' => 'Cement Bag',
                'category_id' => $civil->id,
                'unit' => 'Bag',
                'minimum_stock_level' => 20,
                'description' => 'OPC cement bag',
                'is_active' => true,
            ]
        );

        $mainStore = Store::updateOrCreate(
            ['store_code' => 'STR-0001'],
            [
                'name' => 'Main Store',
                'location' => 'Plant Block A',
                'incharge_name' => 'Store Supervisor',
                'contact_no' => '03001234567',
                'remarks' => 'Primary material store',
                'is_active' => true,
            ]
        );

        $transitStore = Store::updateOrCreate(
            ['store_code' => 'STR-0002'],
            [
                'name' => 'Transit Store',
                'location' => 'Warehouse B',
                'incharge_name' => 'Transit Custodian',
                'contact_no' => '03007654321',
                'remarks' => 'Used for transfer and loan tracking',
                'is_active' => true,
            ]
        );

        $pr = PurchaseRequisition::updateOrCreate(
            ['pr_no' => 'PR-20260425-0001'],
            [
                'pr_date' => now()->toDateString(),
                'category_id' => $electrical->id,
                'item_id' => $cable->id,
                'required_qty' => 200,
                'purpose' => 'Maintenance',
                'required_for' => 'Panel rewiring',
                'requested_by' => 'Admin User',
                'remarks' => 'Urgent maintenance stock',
                'status' => 'Converted to PO',
            ]
        );

        $po = PurchaseOrder::updateOrCreate(
            ['po_no' => 'PO-20260425-0001'],
            [
                'po_date' => now()->toDateString(),
                'purchase_requisition_id' => $pr->id,
                'vendor_name' => 'Alpha Traders',
                'item_id' => $cable->id,
                'ordered_qty' => 200,
                'unit_rate' => 350,
                'total_amount' => 70000,
                'status' => 'Partial Received',
                'remarks' => 'Standard vendor',
            ]
        );

        StockReceipt::updateOrCreate(
            ['grn_no' => 'GRN-20260425-0001'],
            [
                'receive_date' => now()->toDateString(),
                'purchase_order_id' => $po->id,
                'purchase_requisition_id' => $pr->id,
                'item_id' => $cable->id,
                'store_id' => $mainStore->id,
                'ordered_qty' => 200,
                'already_received_qty' => 0,
                'received_qty' => 120,
                'pending_qty' => 80,
                'acquisition_type' => 'Loan',
                'acquisition_reference' => 'LN-2026-001',
                'lender_name' => 'Maintenance Loan Pool',
                'loan_due_date' => now()->addDays(14)->toDateString(),
                'loan_status' => 'On Loan',
                'source_store_id' => $transitStore->id,
                'challan_no' => 'CH-1001',
                'received_by' => 'Store Supervisor',
                'handover_to' => 'Electrical Team',
                'remarks' => 'Partial delivery received',
            ]
        );

        StockIssue::updateOrCreate(
            ['issue_no' => 'ISS-20260425-0001'],
            [
                'issue_date' => now()->toDateString(),
                'store_id' => $mainStore->id,
                'category_id' => $electrical->id,
                'item_id' => $cable->id,
                'purchase_requisition_id' => $pr->id,
                'purchase_order_id' => $po->id,
                'available_qty' => 120,
                'issue_qty' => 20,
                'issued_to_person' => 'Ali Raza',
                'department_location' => 'Maintenance Department',
                'used_at' => 'Boiler Area',
                'usage_purpose' => 'Maintenance',
                'handover_name' => 'Imran',
                'remarks' => 'Issued for urgent repair work',
            ]
        );

        PurchaseRequisition::updateOrCreate(
            ['pr_no' => 'PR-20260425-0002'],
            [
                'pr_date' => now()->subDay()->toDateString(),
                'category_id' => $civil->id,
                'item_id' => $cement->id,
                'required_qty' => 100,
                'purpose' => 'Project Work',
                'required_for' => 'Drain repair',
                'requested_by' => 'Admin User',
                'remarks' => 'For civil works',
                'status' => 'Approved',
            ]
        );
    }
}
