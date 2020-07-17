<?php

namespace App;

use App\Libraries\Model;
use App\Libraries\SoftDeletes;

class Moneda extends Model
{
  use SoftDeletes;

  protected $table = "cmc_moneda";
  protected $primaryKey = "moneda_id";

  function alterna() {
    return self::find($this->id==1? 2: 1);
  }
}
