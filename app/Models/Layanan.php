<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Support\LogOptions;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Layanan extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'layanan';

    protected $fillable = [
        'nama',
        'slug',
        'ringkasan',
        'persyaratan',
        'alur',
        'biaya',
        'waktu_penyelesaian',
        'penanggung_jawab',
        'formulir_file',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function (Layanan $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nama);
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('urutan');
    }
}
