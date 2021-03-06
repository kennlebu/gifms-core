<?php

namespace App\Http\Controllers;

use App\Models\Inventory\Inventory;
use App\Models\Inventory\InventoryCategory;
use App\Models\Inventory\InventoryDescription;
use App\Models\Inventory\InventoryMovement;
use App\Models\Inventory\InventoryName;
use App\Models\Inventory\InventoryStatus;
use Illuminate\Http\Request;

class InventoryApi extends Controller
{
    public function getInventory(Request $request) {
        $inventory = Inventory::query();
        if(!$request->lean) {
            $inventory = Inventory::with('status','description','category', 'name');
        }

        $inventory = $inventory->orderBy('inventory_category_id')->groupBy('inventory_name_id')->get();

        return response()->json($inventory, 200);
    }

    public function addInventory(Request $request) {

        $descriptions_array = json_decode($request->descriptions);
        foreach($descriptions_array as $da) {
            $inventory = new Inventory();
            $inventory->inventory_name_id = $request->inventory_name_id;
            $inventory->inventory_category_id = $request->inventory_category_id;
            $inventory->description_id = $da->description_id;
            $inventory->quantity = $da->quantity;
            $inventory->lpo_id = $request->lpo_id;
            $inventory->added_by_id = $this->current_user()->id;
            $inventory->status_id = $request->status_id;
            $inventory->delivery_item_id = $request->delivery_item_id ?? null;
            $inventory->save();

            $movement = new InventoryMovement();
            $movement->inventory_id = $inventory->id;
            $movement->inventory_name_id = $request->inventory_name_id;
            $movement->description_id = $da->description_id;
            // $movement->from = 'addition';
            $movement->to = 'Store';
            $movement->initiated_by_id = $this->current_user()->id;
            $movement->quantity = $da->quantity;
            $movement->to_type = 'Store';
            $movement->save();
        }

        return response()->json(['msg'=>'Success'], 200);
    }

    public function updateInventory(Request $request, $id) {
        $inventory = Inventory::findOrFail($id);
        $inventory->inventory_name_id = $request->inventory_name_id;
        $inventory->inventory_category_id = $request->inventory_category_id;
        $inventory->description_id = $request->description_id;
        $inventory->quantity = $request->quantity;
        $inventory->lpo_id = $request->lpo_id;
        $inventory->save();

        return response()->json(['msg'=>'Success', 'inventory'=>$inventory], 200);
    }

    public function getOneInventory(Request $request, $id) {
        $inventory = Inventory::with('name.descriptions', 'description', 'category', 'movements', 'status', 'lpo.deliveries', 'added_by')->findOrFail($id);
        $multiple = Inventory::with('description')->where('inventory_name_id', $inventory->inventory_name_id)->groupBy('description_id')->get();
        $inventory['descriptions_array'] = $multiple;

        return response()->json($inventory, 200);
    }

    public function deleteInventory($id) {
        $inventory = Inventory::where('inventory_name_id', $id)->get();

        foreach($inventory as $item) {
            $movement = new InventoryMovement();
            $movement->inventory_id = $item->id;
            $movement->inventory_name_id = $item->inventory_name_id;
            $movement->description_id = $item->description_id;
            $movement->from = 'Store';
            $movement->to = 'Deleted';
            $movement->initiated_by_id = $this->current_user()->id;
            $movement->quantity = 0 - (int)$item->quantity;
            $movement->to_type = 'Delete';
            $movement->save();

            $inventory->delete();
        }

        return response()->json(['msg'=>'Success'], 200);
    }

    public function issueInventory(Request $request) {

        $descriptions_array = json_decode($request->descriptions);
        foreach($descriptions_array as $da) {
            // Checking available quantity
            $inv_desc = InventoryDescription::find($da->description_id);
            
            if(!empty($inv_desc) && $inv_desc->total < (int)$da->quantity) {
                return response()->json(['error'=>'You can not issue more inventory than available'], 403);
            }

            $movement = new InventoryMovement();
            // $movement->inventory_id = $inventory->id;
            $movement->inventory_name_id = $request->inventory_name_id;
            $movement->description_id = $da->description_id;
            $movement->from = 'Store';
            if($request->to_type === 'Staff') {
                $movement->to = $request->staff_id;
            }
            else {
                $movement->to = $request->to_type;            
            }
            $movement->initiated_by_id = $this->current_user()->id;
            $movement->quantity = 0 - (int)$da->quantity;
            $movement->to_type = $request->to_type;
            $movement->save();
        }

        return response()->json(['msg'=>'Success'], 200);
    }

    public function issueMultipleInventory(Request $request) {

        $items_array = json_decode($request->items);

        foreach($items_array as $i) {
            // Checking available quantity
            $inv_desc = InventoryDescription::find($i->description_id);
            
            if(!empty($inv_desc) && $inv_desc->total < (int)$i->quantity) {
                return response()->json(['error'=>'You cannot issue more '. $inv_desc->description .' than available'], 403);
            }
        }

        foreach($items_array as $item) {
            // Checking available quantity
            $inv_desc = InventoryDescription::find($item->description_id);
            
            if(!empty($inv_desc) && $inv_desc->total < (int)$item->quantity) {
                return response()->json(['error'=>'You cannot issue more '. $inv_desc->description .' than available'], 403);
            }

            $movement = new InventoryMovement();
            $movement->inventory_name_id = $item->inventory_name_id;
            $movement->description_id = $item->description_id;
            $movement->from = 'Store';
            if($request->to_type === 'Staff') {
                $movement->to = $request->staff_id;
            }
            else {
                $movement->to = $request->to_type;            
            }
            $movement->initiated_by_id = $this->current_user()->id;
            $movement->quantity = 0 - (int)$item->quantity;
            $movement->to_type = $request->to_type;
            $movement->save();
        }

        return response()->json(['msg'=>'Success'], 200);
    }

    public function reportLoss(Request $request) {
        $descriptions_array = json_decode($request->descriptions);
        foreach($descriptions_array as $da) {

            $movement = new InventoryMovement();
            // $movement->inventory_id = $inventory->id;
            $movement->inventory_name_id = $request->inventory_name_id;
            $movement->description_id = $da->description_id;
            $movement->from = 'Store';
            $movement->to = 'Loss';
            $movement->initiated_by_id = $this->current_user()->id;
            $movement->quantity = 0 - (int)$da->quantity;
            $movement->remarks = $request->remarks;
            $movement->save();
        }

        return response()->json(['msg'=>'Success'], 200);
    }

    public function getStatuses() {
        $statuses = InventoryStatus::all();
        return response()->json($statuses, 200);
    }

    public function addCategory(Request $request) {
        $category = new InventoryCategory();
        $category->category = $request->category;
        $category->save();

        return response()->json($category, 200);
    }

    public function addInventoryName(Request $request) {
        $name = new InventoryName();
        $name->name = $request->name;
        $name->save();
        
        return response()->json($name, 200);
    }

    public function addDescription(Request $request) {
        $description = new InventoryDescription();
        $description->description = $request->description;
        $description->inventory_name_id = $request->inventory_name_id;
        $description->save();
        
        return response()->json($description, 200);
    }

    public function getCategories() {
        $categories = InventoryCategory::all();
        return response()->json($categories, 200);
    }

    public function getDescriptions(Request $request) {
        $descriptions = InventoryDescription::query();
        if(!empty($request->inventory_name_id)){
            $descriptions = $descriptions->where('inventory_name_id', $request->inventory_name_id);
        }
        $descriptions = $descriptions->get();
        return response()->json($descriptions, 200);
    }

    public function getNames() {
        $names = InventoryName::all();
        return response()->json($names, 200);
    }

    public function getMovement(Request $request) {
        $movement = InventoryMovement::with('inventory', 'name', 'description', 'initiated_by', 'staff_to');
        if(!empty($request->inventory_name_id)) {
            $movement = $movement->where('inventory_name_id', $request->inventory_name_id);
        }

        return response()->json($movement->get(), 200);
    }
}
