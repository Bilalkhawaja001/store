<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockReceiptAcquisitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_receipt_and_acquisition_report_pages_render_required_fields(): void
    {
        $this->seed();

        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/stock-receipts')
            ->assertOk()
            ->assertSee('Loan')
            ->assertSee('LN-2026-001');

        $this->actingAs($user)
            ->get('/stock-receipts/create')
            ->assertOk()
            ->assertSee('lender_name', false)
            ->assertSee('loan_due_date', false)
            ->assertSee('loan_status', false)
            ->assertSee('source_store_id', false)
            ->assertDontSee('acquisition_source', false)
            ->assertDontSee('acquisition_cost', false)
            ->assertDontSee('acquisition_notes', false);

        $this->actingAs($user)
            ->get('/reports?tab=acquisition-summary')
            ->assertOk()
            ->assertSee('Loan-linked')
            ->assertSee('Transfer-linked');
    }
}
