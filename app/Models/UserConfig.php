<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserConfig extends Model
{
    protected $fillable = [
        'user_id',
        'header',
        'property',
        'value',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getValue(string $property): string
    {
        return UserConfig::query()
            ->where('user_id', auth()->id())
            ->where('property', $property)
            ->value('value') ?? '';
    }

    public static function setProperty(string $property, string $value, string $header = 'all'): void
    {
        UserConfig::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'property' => $property,
                'header' => $header,
            ],
            [
                'value' => $value,
            ]
        );

    }
}
