<?php

namespace App;

use App\Libraries\Model;
use App\Libraries\SoftDeletes;

class Muelle extends Model
{
  use SoftDeletes;

  protected $table = 'cmc_muelle';
  protected $primaryKey = 'muelle_id';

  public function puerto() {
    return $this->belongsTo('App\Puerto', 'puerto_id');
  }
}
