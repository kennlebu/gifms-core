<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class InventoryCategory extends Model
{
    public $timestamps = false;
    protected $table = 'inventory_categories';
}
