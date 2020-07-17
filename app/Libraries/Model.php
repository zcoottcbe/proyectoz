<?php
namespace App\Libraries;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel {
  function getIdAttribute() {
    return $this->getAttributeFromArray($this->primaryKey);
  }
 
}
