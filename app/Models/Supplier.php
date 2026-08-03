<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';


    protected $fillable = array (
  0 => 'code',
  1 => 'name',
  2 => 'contact_person',
  3 => 'phone',
  4 => 'email',
  5 => 'tax_code',
  6 => 'bank_name',
  7 => 'bank_account',
  8 => 'address',
  9 => 'note',
  10 => 'status',
);


}