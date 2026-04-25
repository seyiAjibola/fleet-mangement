<?php

namespace App\Services\Compliance;

use App\Models\ComplianceNotificationLog;
use App\Models\ComplianceRecord;
use App\Models\User;
use App\Notifications\ComplianceStatusNotification;
use Illuminate\Support\Collection;

class ComplianceNotificationService
{
    public function sendForRecord(ComplianceRecord $record): int
    {
        if (! in_array($record->status, ['expiring', 'expired', 'non_compliant'], true)) {
            return 0;
        }

        $sent = 0;
        $contextKey = $this->contextKey($record);

        foreach ($this->recipients($record) as $recipient) {
            $alreadySent = ComplianceNotificationLog::query()
                ->where('compliance_record_id', $record->getKey())
                ->where('user_id', $recipient->getKey())
                ->where('notification_type', $record->status)
                ->where('context_key', $contextKey)
                ->exists();

            if ($alreadySent) {
                continue;
            }

            $notification = new ComplianceStatusNotification($record, $record->status);
            $channels = $notification->via($recipient);
            $recipient->notify($notification);

            ComplianceNotificationLog::query()->create([
                'compliance_record_id' => $record->getKey(),
                'user_id' => $recipient->getKey(),
                'notification_type' => $record->status,
                'status_snapshot' => $record->status,
                'context_key' => $contextKey,
                'channels' => $channels,
                'notified_at' => now(),
            ]);

            $sent++;
        }

        if ($sent > 0) {
            $record->forceFill(['last_notified_at' => now()])->saveQuietly();
        }

        return $sent;
    }

    private function recipients(ComplianceRecord $record): Collection
    {
        $users = User::query()
            ->where('role', 'admin')
            ->get();

        $owner = $record->entity && method_exists($record->entity, 'creator')
            ? $record->entity->creator
            : null;

        if ($owner) {
            $users->push($owner);
        } elseif ($record->creator) {
            $users->push($record->creator);
        }

        return $users->unique('id')->values();
    }

    private function contextKey(ComplianceRecord $record): string
    {
        return implode('|', [
            $record->status,
            $record->expiry_date?->toDateString() ?? 'none',
            (string) $record->compliance_type_id,
            (string) $record->entity_type,
            (string) $record->entity_id,
        ]);
    }
}
