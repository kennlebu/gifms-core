<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class InventoryStatus extends Model
{
    public $timestamps = false;
    protected $table = 'inventory_statuses';
}
