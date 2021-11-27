<?php

namespace App\Repositories;

use App\Models\CartStorageModel as DatabaseStorageModel;
use Darryldecode\Cart\CartCollection;

class CartDBStorageRepository {

    public function has($key)
    {
        return DatabaseStorageModel::find($key);
    }

    public function get($key)
    {
        if($this->has($key))
        {
            return new CartCollection(DatabaseStorageModel::find($key)->cart_data);
        }
        else
        {
            return [];
        }
    }

    public function put($key, $value)
    {
        
        if($row = DatabaseStorageModel::find($key))
        {
            // update
            $row->cart_data = $value;
            $row->save();
        }
        else
        {
            DatabaseStorageModel::create([
                'id' => $key,
                'cart_data' => $value,
                'vendor_id' => auth()->user()->restaurant_id,
                'user_id'=>auth()->user()->id,
                'type'=>strlen($key)<15?'3':(strlen($key)<19?"2":"1"),
                'receipt_number'=>now()->timestamp."-".sprintf('%03d', auth()->user()->id)."-".rand(1000,9999)
            ]);
        }
    }
}