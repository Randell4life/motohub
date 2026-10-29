<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;

#[Fillable(['name', 'email', 'password', 'phone'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
public function motorcycles(): HasMany
{
    return $this->hasMany(Motorcycle::class);
}

public function partListings(): HasMany
{
    return $this->hasMany(PartListing::class);
}

public function canAccessPanel(\Filament\Panel $panel): bool
{
    return (bool) $this->is_admin
        && $this->email === 'braincell2003@gmail.com';
}

public function rentalBookings(): HasMany
{
    return $this->hasMany(RentalBooking::class);
}

public function serviceBookings(): HasMany
{
    return $this->hasMany(ServiceBooking::class);
}
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
