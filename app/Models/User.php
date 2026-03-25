<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $guarded = [];

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

      public function getRouteKeyName(): string
    {
        return 'key';
    }
public function country():BelongsTo{
    return $this->belongsTo(Country::class);
}

public function city():BelongsTo{
    return $this->belongsTo(City::class);
}

public function commune():BelongsTo{
    return $this->belongsTo(Commune::class);
}

    /**
     * Générer un code OTP
     */
    public function generateOtpCode(): string
    {
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $this->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        return $otp;
    }

    /**
     * Vérifier le code OTP
     */
    public function verifyOtpCode(string $code): bool
    {
        if (
            $this->otp_code === $code &&
            $this->otp_expires_at &&
            $this->otp_expires_at->isFuture()
        ) {
            $this->update([
                'email_verified' => true,
                'otp_code' => null,
                'otp_expires_at' => null,
            ]);

            return true;
        }

        return false;
    }

    /**
     * Vérifier si l'OTP a expiré
     */
    public function isOtpExpired(): bool
    {
        return $this->otp_expires_at === null || $this->otp_expires_at->isPast();
    }

}
