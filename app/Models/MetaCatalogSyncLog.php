<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class MetaCatalogSyncLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'response_payload' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function subjectLabel(): string
    {
        $carTranslation = $this->car?->translations?->firstWhere('locale', 'en')
            ?? $this->car?->translations?->first();

        if (filled($carTranslation?->name)) {
            return (string) $carTranslation->name;
        }

        if ($this->car_id) {
            return __('Car #:id', ['id' => $this->car_id]);
        }

        return __('All cars');
    }

    public function notificationType(): string
    {
        return match ($this->status) {
            'success' => 'success',
            'partial' => 'warning',
            'failed' => 'error',
            default => 'info',
        };
    }

    public function notificationTitle(): string
    {
        return match ($this->status) {
            'queued' => __('Catalog Sync Queued'),
            'running' => __('Catalog Sync Started'),
            'success' => __('Catalog Sync Completed'),
            'partial' => __('Catalog Sync Completed With Issues'),
            'failed' => __('Catalog Sync Failed'),
            default => __('Catalog Sync Update'),
        };
    }

    public function notificationMessage(): string
    {
        $subject = $this->subjectLabel();

        return match ($this->status) {
            'queued' => __(':subject is waiting in the queue and will start shortly.', [
                'subject' => $subject,
            ]),
            'running' => __(':subject is syncing to the Meta catalog now.', [
                'subject' => $subject,
            ]),
            'success' => __(':subject synced successfully. :count cars updated.', [
                'subject' => $subject,
                'count' => $this->success_count ?: $this->total_count,
            ]),
            'partial' => __(':subject finished with :failed failed items out of :total.', [
                'subject' => $subject,
                'failed' => $this->failed_count,
                'total' => $this->total_count,
            ]),
            'failed' => filled($this->message)
                ? (string) $this->message
                : __(':subject could not be synced to the Meta catalog.', [
                    'subject' => $subject,
                ]),
            default => $this->message ?: __('Meta catalog sync status updated.'),
        };
    }

    public function notificationTimestamp(): ?Carbon
    {
        return $this->finished_at
            ?? $this->started_at
            ?? $this->created_at;
    }

    public function notificationTimeLabel(): ?string
    {
        return $this->notificationTimestamp()?->diffForHumans();
    }

    public function isUnreadNotification(): bool
    {
        return in_array($this->status, ['queued', 'running', 'partial', 'failed'], true);
    }
}
