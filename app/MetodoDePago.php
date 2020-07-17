<?php

namespace App;

use App\Libraries\Model;
use App\Libraries\SoftDeletes;

class MetodoDePago extends Model
{
  use SoftDeletes;

  protected $table = 'metodo_pago';
  protected $primaryKey = 'metodo_pago_id';

  public function ordenados($query) {
    return $query->orderBy('orden');
  }
}
