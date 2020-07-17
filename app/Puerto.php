<?php

namespace App;

use App\Libraries\Model;
use App\Libraries\SoftDeletes;

class Puerto extends Model
{
  use SoftDeletes;

  protected $table = 'cmc_puerto';
  protected $primaryKey = 'puerto_id';
}
