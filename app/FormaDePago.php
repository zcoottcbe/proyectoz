<?php

namespace App;

use App\Libraries\Model;
use App\Libraries\SoftDeletes;

class FormaDePago extends Model
{
  use SoftDeletes;

  protected $table = 'cmc_formapago';

  public function scopeOrdenadas($query) {
    return $query->orderBy('orden')->orderBy('codigo');
  }
}
