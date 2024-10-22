<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $type
 * @property string|null $value
 * @property int $user_id
 */
class IntegrationSetting extends Model
{

    protected $fillable = [
        'id',
        'type',
        'value',
        'user_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;
        return $this;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
