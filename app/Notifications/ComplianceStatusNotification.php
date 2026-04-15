<?php

namespace App\Notifications;

use App\Models\ComplianceRecord;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ComplianceStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public ComplianceRecord $record,
        public string $notificationType,
    ) {
    }

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if (! empty($notifiable->email)) {
            $channels[] = 'mail';
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Compliance Alert: ' . $this->record->complianceType?->name)
            ->line($this->messageLine())
            ->line('Entity: ' . $this->entityLabel())
            ->line('Status: ' . ucfirst(str_replace('_', ' ', $this->record->status)))
            ->line('Expiry: ' . ($this->record->expiry_date?->toDateString() ?? 'N/A'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'compliance_record_id' => $this->record->getKey(),
            'notification_type' => $this->notificationType,
            'status' => $this->record->status,
            'compliance_type' => $this->record->complianceType?->name,
            'entity_type' => $this->record->entity_type,
            'entity_label' => $this->entityLabel(),
            'document_number' => $this->record->document_number,
            'expiry_date' => $this->record->expiry_date?->toDateString(),
            'message' => $this->messageLine(),
        ];
    }

    private function messageLine(): string
    {
        return match ($this->notificationType) {
            'expiring' => 'A compliance document is approaching expiry.',
            'expired' => 'A compliance document has expired.',
            'non_compliant' => 'A compliance document is now non-compliant and requires escalation.',
            default => 'A compliance record changed status.',
        };
    }

    private function entityLabel(): string
    {
        $entity = $this->record->entity;

        return match ($this->record->entity_type) {
            'vehicle' => trim(($entity?->vehicle_make ?? '') . ' ' . ($entity?->vehicle_model ?? '')) . ' (' . ($entity?->plate_number ?? '—') . ')',
            'driver' => ($entity?->driver_name ?? 'Unknown') . ' (' . ($entity?->license_number ?? '—') . ')',
            'supplier' => $entity?->business_name ?? 'Unknown',
            default => 'Unknown',
        };
    }
}
