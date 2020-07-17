<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Libraries\SoftDeletes;

class UsoCFDI extends Model
{
  use SoftDeletes;

  protected $table = 'cmc_usocfdi';

  public function ordenados($query) {
    return $query->orderBy('orden')
      ->orderBy('codigo');
  }
}

