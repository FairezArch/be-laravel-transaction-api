<?php

namespace App\Models;

use App\Models\Transaction;
use App\Models\CustomerAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Authenticatable implements JWTSubject
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get all of the customer_address for the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customer_address(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    /**
     * Get all of the transaction for the Customer
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }
}
