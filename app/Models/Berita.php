<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Berita extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'berita';

    protected $fillable = [
        'judul',
        'slug',
        'ringkasan',
        'isi',
        'gambar_utama',
        'alt_text',
        'kategori',
        'tags',
        'status',
        'tanggal_terbit',
        'views_count',
        'author_id',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'tags' => 'array',
        'tanggal_terbit' => 'datetime',
        'views_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Berita $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->judul);
            }

            if ($model->isDirty('gambar_utama') && !empty($model->gambar_utama)) {
                $model->gambar_utama = app(\App\Services\ImageConversionService::class)
                    ->convertStorageImageToWebp($model->gambar_utama);
            }
        });
    }

    /**
     * Konfigurasi log aktivitas Spatie.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    /**
     * Scope untuk berita yang terbit dan dapat dibaca publik.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where(function (Builder $q) {
            $q->where('status', 'terbit')
              ->where(function (Builder $sub) {
                  $sub->whereNull('tanggal_terbit')
                      ->orWhere('tanggal_terbit', '<=', now());
              });
        })->orWhere(function (Builder $q) {
            $q->where('status', 'terjadwal')
              ->where('tanggal_terbit', '<=', now());
        });
    }

    /**
     * Scope untuk berita terjadwal (belum waktu tayang).
     */
    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', 'terjadwal')
            ->where('tanggal_terbit', '>', now());
    }

    /**
     * Scope untuk berita berstatus draft.
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }

    /**
     * Relasi ke penulis / admin.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
