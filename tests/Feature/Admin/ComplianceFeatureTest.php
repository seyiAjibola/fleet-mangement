<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\Compliance\ComplianceForm;
use App\Livewire\Admin\Compliance\ComplianceList;
use App\Livewire\Admin\Notifications\Center as NotificationCenter;
use App\Models\ComplianceAuditLog;
use App\Models\ComplianceNotificationLog;
use App\Models\ComplianceRecord;
use App\Models\ComplianceType;
use App\Models\Driver;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Vehicle;
use App\Notifications\ComplianceStatusNotification;
use App\Services\Compliance\ComplianceNotificationService;
use App\Services\Compliance\ComplianceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\TestCase;

class ComplianceFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_vehicle_compliance_form_uses_custom_primary_key_filters_types_and_sets_status(): void
    {
        $user = User::factory()->staff()->create();
        $supplier = $this->createSupplier();
        $vehicle = $this->createVehicle($supplier->supplier_id, $user->id, 'ABC-123');

        $vehicleType = ComplianceType::query()->create([
            'name' => 'Vehicle Insurance',
            'entity_type' => 'vehicle',
            'expiry_required' => true,
            'notification_days_before' => 3,
            'grace_period_days' => 5,
            'is_active' => true,
        ]);

        $driverType = ComplianceType::query()->create([
            'name' => 'Driver License',
            'entity_type' => 'driver',
            'expiry_required' => true,
            'notification_days_before' => 3,
            'grace_period_days' => 5,
            'is_active' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(ComplianceForm::class)
            ->call('open', null, $vehicle->vehicle_id, 'vehicle')
            ->assertSee($vehicleType->name)
            ->assertDontSee($driverType->name)
            ->set('compliance_type_id', $vehicleType->id)
            ->set('document_number', 'COMP-001')
            ->set('issued_date', now()->subMonth()->toDateString())
            ->set('expiry_date', now()->subDay()->toDateString())
            ->call('save');

        $this->assertDatabaseHas('compliance_records', [
            'entity_type' => 'vehicle',
            'entity_id' => $vehicle->vehicle_id,
            'compliance_type_id' => $vehicleType->id,
            'document_number' => 'COMP-001',
            'status' => 'expired',
        ]);
        $this->assertDatabaseHas('compliance_audit_logs', [
            'action' => 'created',
            'actor_id' => $user->id,
        ]);
    }

    public function test_compliance_list_refreshes_stale_statuses_for_display(): void
    {
        $user = User::factory()->staff()->create();
        $supplier = $this->createSupplier();
        $vehicle = $this->createVehicle($supplier->supplier_id, $user->id, 'XYZ-789');
        $type = ComplianceType::query()->create([
            'name' => 'Road Worthiness',
            'entity_type' => 'vehicle',
            'expiry_required' => true,
            'notification_days_before' => 3,
            'grace_period_days' => 5,
            'is_active' => true,
        ]);

        $record = ComplianceRecord::query()->create([
            'compliance_type_id' => $type->id,
            'entity_type' => 'vehicle',
            'entity_id' => $vehicle->vehicle_id,
            'document_number' => 'ROAD-001',
            'expiry_date' => now()->subDays(10)->toDateString(),
            'status' => 'valid',
            'created_by' => $user->id,
        ]);

        $record->forceFill(['status' => 'valid'])->saveQuietly();

        $this->actingAs($user);

        Livewire::test(ComplianceList::class, ['entity' => $vehicle])
            ->assertSee($type->name)
            ->assertSee('Non compliant');

        $this->assertDatabaseHas('compliance_records', [
            'id' => $record->id,
            'status' => 'non_compliant',
        ]);
    }

    public function test_compliance_form_uploads_supporting_documents(): void
    {
        Storage::fake('public');

        $user = User::factory()->staff()->create();
        $supplier = $this->createSupplier();
        $vehicle = $this->createVehicle($supplier->supplier_id, $user->id, 'DOC-123');
        $type = ComplianceType::query()->create([
            'name' => 'Insurance',
            'entity_type' => 'vehicle',
            'expiry_required' => true,
            'notification_days_before' => 3,
            'grace_period_days' => 5,
            'is_active' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(ComplianceForm::class)
            ->call('open', null, $vehicle->vehicle_id, 'vehicle')
            ->set('compliance_type_id', $type->id)
            ->set('document_number', 'INS-01')
            ->set('issued_date', now()->subWeek()->toDateString())
            ->set('expiry_date', now()->addMonth()->toDateString())
            ->set('documents', [
                UploadedFile::fake()->create('insurance.pdf', 100, 'application/pdf'),
            ])
            ->call('save');

        $document = \App\Models\ComplianceDocument::query()->first();

        $this->assertNotNull($document);
        Storage::disk('public')->assertExists($document->file_path);
        $this->assertDatabaseHas('compliance_documents', [
            'compliance_record_id' => $document->compliance_record_id,
            'uploaded_by' => $user->id,
            'file_type' => 'pdf',
        ]);
        $this->assertDatabaseHas('compliance_audit_logs', [
            'compliance_record_id' => $document->compliance_record_id,
            'action' => 'document_added',
            'actor_id' => $user->id,
        ]);
    }

    public function test_service_blocks_staff_from_accessing_hidden_compliance_records(): void
    {
        $owner = User::factory()->staff()->create();
        $otherUser = User::factory()->staff()->create();
        $supplier = $this->createSupplier();
        $vehicle = $this->createVehicle($supplier->supplier_id, $owner->id, 'HID-404');
        $type = ComplianceType::query()->create([
            'name' => 'Vehicle Permit',
            'entity_type' => 'vehicle',
            'expiry_required' => true,
            'notification_days_before' => 3,
            'grace_period_days' => 5,
            'is_active' => true,
        ]);
        $record = ComplianceRecord::query()->create([
            'compliance_type_id' => $type->id,
            'entity_type' => 'vehicle',
            'entity_id' => $vehicle->vehicle_id,
            'document_number' => 'PERMIT-1',
            'expiry_date' => now()->addWeek()->toDateString(),
            'created_by' => $owner->id,
        ]);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);

        app(ComplianceService::class)->findAccessibleRecord($record->id, $otherUser);
    }

    public function test_notification_service_sends_alerts_and_writes_log_without_duplicates(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();
        $creator = User::factory()->staff()->create();
        $supplier = $this->createSupplier();
        $vehicle = $this->createVehicle($supplier->supplier_id, $creator->id, 'NOT-101');
        $type = ComplianceType::query()->create([
            'name' => 'Vehicle Insurance',
            'entity_type' => 'vehicle',
            'expiry_required' => true,
            'notification_days_before' => 7,
            'grace_period_days' => 3,
            'is_active' => true,
        ]);

        $record = ComplianceRecord::query()->create([
            'compliance_type_id' => $type->id,
            'entity_type' => 'vehicle',
            'entity_id' => $vehicle->vehicle_id,
            'document_number' => 'NOTIF-001',
            'expiry_date' => now()->addDays(2)->toDateString(),
            'created_by' => $creator->id,
        ])->load(['complianceType', 'creator', 'entity']);

        $sent = app(ComplianceNotificationService::class)->sendForRecord($record);
        $record->refresh();

        $this->assertSame(2, $sent);
        $this->assertNotNull($record->last_notified_at);
        $this->assertDatabaseCount('compliance_notification_logs', 2);

        Notification::assertSentTo($admin, ComplianceStatusNotification::class);
        Notification::assertSentTo($creator, ComplianceStatusNotification::class);

        $resent = app(ComplianceNotificationService::class)->sendForRecord($record->fresh(['complianceType', 'creator', 'entity']));

        $this->assertSame(0, $resent);
        $this->assertDatabaseCount('compliance_notification_logs', 2);
    }

    public function test_check_service_sends_notifications_for_daily_run(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();
        $creator = User::factory()->staff()->create();
        $supplier = $this->createSupplier();
        $vehicle = $this->createVehicle($supplier->supplier_id, $creator->id, 'RUN-202');
        $type = ComplianceType::query()->create([
            'name' => 'Road Worthiness',
            'entity_type' => 'vehicle',
            'expiry_required' => true,
            'notification_days_before' => 3,
            'grace_period_days' => 5,
            'is_active' => true,
        ]);

        ComplianceRecord::query()->create([
            'compliance_type_id' => $type->id,
            'entity_type' => 'vehicle',
            'entity_id' => $vehicle->vehicle_id,
            'document_number' => 'RUN-001',
            'expiry_date' => now()->subDay()->toDateString(),
            'created_by' => $creator->id,
        ]);

        $result = app(\App\Services\Compliance\ComplianceCheckService::class)->run();

        $this->assertSame(2, $result['notifications']);
        $this->assertDatabaseCount('compliance_notification_logs', 2);
        $this->assertGreaterThanOrEqual(0, $result['updated']);

        Notification::assertSentTo($admin, ComplianceStatusNotification::class);
        Notification::assertSentTo($creator, ComplianceStatusNotification::class);
    }

    public function test_notification_center_marks_notifications_as_read(): void
    {
        $user = User::factory()->staff()->create();

        $notification = $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => ComplianceStatusNotification::class,
            'data' => [
                'message' => 'A compliance document has expired.',
                'status' => 'expired',
                'compliance_type' => 'Insurance',
                'entity_label' => 'Toyota Highlander (ABC-123)',
            ],
        ]);
        $secondNotification = $user->notifications()->create([
            'id' => (string) Str::uuid(),
            'type' => ComplianceStatusNotification::class,
            'data' => [
                'message' => 'A compliance document is approaching expiry.',
                'status' => 'expiring',
                'compliance_type' => 'Road Worthiness',
                'entity_label' => 'Toyota Highlander (XYZ-789)',
            ],
        ]);

        $this->actingAs($user);

        Livewire::test(NotificationCenter::class)
            ->assertSee('A compliance document has expired.')
            ->call('markAsRead', $notification->id);

        $this->assertNotNull($notification->fresh()->read_at);

        Livewire::test(NotificationCenter::class)
            ->call('markAllAsRead');

        $this->assertNotNull($secondNotification->fresh()->read_at);
    }

    public function test_compliance_updates_and_document_removals_are_audited(): void
    {
        Storage::fake('public');

        $user = User::factory()->staff()->create();
        $supplier = $this->createSupplier();
        $vehicle = $this->createVehicle($supplier->supplier_id, $user->id, 'AUD-321');
        $type = ComplianceType::query()->create([
            'name' => 'Insurance',
            'entity_type' => 'vehicle',
            'expiry_required' => true,
            'notification_days_before' => 3,
            'grace_period_days' => 5,
            'is_active' => true,
        ]);

        $this->actingAs($user);

        Livewire::test(ComplianceForm::class)
            ->call('open', null, $vehicle->vehicle_id, 'vehicle')
            ->set('compliance_type_id', $type->id)
            ->set('document_number', 'AUD-001')
            ->set('expiry_date', now()->addDays(14)->toDateString())
            ->set('documents', [
                UploadedFile::fake()->create('audit.pdf', 100, 'application/pdf'),
            ])
            ->call('save');

        $record = ComplianceRecord::query()->latest()->firstOrFail();
        $document = $record->documents()->firstOrFail();

        Livewire::test(ComplianceForm::class)
            ->call('open', $record->id)
            ->set('document_number', 'AUD-002')
            ->call('save');

        Livewire::test(ComplianceForm::class)
            ->call('open', $record->id)
            ->call('removeDocument', $document->id);

        $this->assertDatabaseHas('compliance_audit_logs', [
            'compliance_record_id' => $record->id,
            'action' => 'updated',
            'actor_id' => $user->id,
        ]);
        $this->assertDatabaseHas('compliance_audit_logs', [
            'compliance_record_id' => $record->id,
            'action' => 'document_removed',
            'actor_id' => $user->id,
        ]);
    }

    public function test_status_changes_from_compliance_check_are_audited(): void
    {
        $creator = User::factory()->staff()->create();
        $supplier = $this->createSupplier();
        $vehicle = $this->createVehicle($supplier->supplier_id, $creator->id, 'STA-909');
        $type = ComplianceType::query()->create([
            'name' => 'Permit',
            'entity_type' => 'vehicle',
            'expiry_required' => true,
            'notification_days_before' => 3,
            'grace_period_days' => 1,
            'is_active' => true,
        ]);

        $record = ComplianceRecord::query()->create([
            'compliance_type_id' => $type->id,
            'entity_type' => 'vehicle',
            'entity_id' => $vehicle->vehicle_id,
            'document_number' => 'STA-1',
            'expiry_date' => now()->subDays(4)->toDateString(),
            'created_by' => $creator->id,
        ]);
        $record->forceFill(['status' => 'valid'])->saveQuietly();

        $result = app(\App\Services\Compliance\ComplianceCheckService::class)->run();

        $this->assertGreaterThanOrEqual(1, $result['updated']);
        $this->assertDatabaseHas('compliance_audit_logs', [
            'compliance_record_id' => $record->id,
            'action' => 'status_changed',
            'actor_id' => null,
        ]);

        $auditLog = ComplianceAuditLog::query()
            ->where('compliance_record_id', $record->id)
            ->where('action', 'status_changed')
            ->latest()
            ->first();

        $this->assertSame('compliance_check', $auditLog->meta['source'] ?? null);
    }

    private function createSupplier(): Supplier
    {
        return Supplier::query()->create([
            'business_name' => 'Supplier One',
            'business_type' => 'Fleet',
            'contact_person' => 'Alice',
            'phone_number' => '08000000000',
            'email' => 'supplier@example.com',
            'city' => 'Lagos',
            'business_address' => '123 Fleet Street',
            'years_in_business' => 5,
            'status' => 'active',
            'supplier_score' => 80,
            'supplier_tier' => 'gold',
        ]);
    }

    private function createVehicle(int $supplierId, int $userId, string $plateNumber): Vehicle
    {
        return Vehicle::query()->create([
            'created_by_user_id' => $userId,
            'supplier_id' => $supplierId,
            'vehicle_make' => 'Toyota',
            'vehicle_model' => 'Highlander',
            'vehicle_year' => 2024,
            'vehicle_color' => 'Black',
            'plate_number' => $plateNumber,
            'vehicle_category' => 'SUV',
            'passenger_capacity' => 4,
            'vehicle_condition' => 'excellent',
            'air_condition' => true,
            'vehicle_location' => 'Lagos',
            'status' => 'available',
        ]);
    }
}
