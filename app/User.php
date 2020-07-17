<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Libraries\SoftDeletes;
class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    protected $table = 'usr';
    protected $primaryKey = 'usr_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getIdAttribute() {
      return $this->usr_id;
    }

    public function getAuthPassword() {
      return $this->passwd;
    }

    public function taquillas() {
      $filtroNombre = explode(",", env('NEOPOS_TAQUILLAS'));
      return Taquilla::whereRaw('find_in_set(?, usr_id)', $this->id)
        ->whereIn("nombre", $filtroNombre)
        ->orderBy('nombre')
        ->get();
    }
}
