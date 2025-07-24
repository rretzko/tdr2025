<?php

namespace App\Models\Programs;

use App\Models\Ensembles\Ensemble;
use App\Models\Schools\School;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Program extends Model
{
    use HasFactory;

    protected $fillable = [
        'organized_by',
        'performance_date',
        'school_id',
        'school_year',
        'subtitle',
        'title'
    ];

    public function getHumanPerformanceDateAttribute(): string
    {
        return Carbon::parse($this->performance_date)
            ->format('M j, Y');
    }

    public function getHumanPerformanceDateLongAttribute(): string
    {
        return Carbon::parse($this->performance_date)
            ->format('F j, Y');
    }

    public function getSelectionCountAttribute(): int
    {
        return ProgramSelection::query()
            ->where('program_id', $this->id)
            ->count();
    }

    public function isOrganizedByEnsemble(): bool
    {
        return $this->organized_by === 'ensemble';
    }

    public function programSelection(): HasMany
    {
        return $this->hasMany(ProgramSelection::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
