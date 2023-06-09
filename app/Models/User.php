<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeWithBad(Builder $query, string $terms = null): void
    {
        collect(explode(' ', $terms))->filter()->each(function ($text) use ($query) {
            $search = "%$text%";
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', $search)
                    ->orWhere('last_name', 'like', $search)
                    ->orWhereHas('company', function ($query) use ($search) {
                        $query->where('name', 'like', $search);
                    });
            });
        });
    }

    public function scopeWithBetter(Builder $query, string $terms = null): void
    {
        collect(explode(' ', $terms))->filter()->each(function ($text) use ($query) {
            $search = "$text%";
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', $search)
                    ->orWhere('last_name', 'like', $search)
                    ->orWhereIn('company_id', function ($query) use ($search) {
                        $query->select('id')->from('companies')
                            ->where('name', 'like', $search);
                    });
            });
        });
    }

    public function scopeWithGood(Builder $query, string $terms = null): void
    {
        collect(explode(' ', $terms))->filter()->each(function ($text) use ($query) {
            $search = "$text%";
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', $search)
                    ->orWhere('last_name', 'like', $search)
                    ->orWhereIn('company_id', Company::query()
                        ->where('name', 'like', $search)
                        ->pluck('id')
                    );
            });
        });
    }

    public function scopeWithBest(Builder $query, string $terms = null): void
    {
        collect(explode(' ', $terms))->filter()->each(function ($text) use ($query) {
            $search = "$text%";
            $query->whereIn('id', function ($query) use ($search) {
                $query->select('id')->from(function ($query) use ($search) {
                    $query->select('id')
                        ->from('users')
                        ->where('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->union($query->newQuery()
                            ->select('users.id')
                            ->from('users')
                            ->join('companies', 'users.company_id', '=', 'companies.id')
                            ->where('companies.name', 'like', $search));
                }, 'matches');
            });
        });
    }
}
