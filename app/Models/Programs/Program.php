<?php

namespace App\Models\Programs;

use App\Models\Ensembles\Ensemble;
use App\Models\Schools\School;
use App\Models\Tag;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

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

    public function getEnsembleSelectionsVO(int $ensembleId): Collection
    {
        return DB::table('program_selections')
            ->join('lib_items', 'lib_items.id', '=', 'program_selections.lib_item_id')
            ->join('lib_titles', 'lib_titles.id', '=', 'lib_items.lib_title_id')
            ->where('program_id', $this->id)
            ->where('ensemble_id', $ensembleId)
            ->select(
                'lib_titles.title'
            )
            ->orderBy('program_selections.order_by')
            ->get();
    }

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
