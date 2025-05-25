<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RajaOngkirController extends BaseController
{
    public function searchDestination(Request $request)
    {
        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key')
        ])->get("https://rajaongkir.komerce.id/api/v1/destination/domestic-destination", [
            'search' => $request->search,
            'limit' => 5,
            "offset" => 0
        ]);


        if ($response->status() !== 200) {
            return $this->sendError(error: $response->json()['meta']['message'], code: $response->json()['meta']['code']);
        }

        return $this->sendResponse(result: $response->json()["data"], message: $response->json()['meta']['message'], code: $response->json()['meta']['code']);
    }

    public function domesticCost(Request $request)
    {
        $courierDefault = "jne:sicepat:ide:sap:jnt:ninja:tiki:lion:anteraja:pos:ncs:rex:rpx:sentral:star:wahana:dse";
        $priceDefault = "lowest";

        $response = Http::withHeaders([
            'key' => config('rajaongkir.api_key')
        ])->asForm()->post("https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost", [
            "origin" => $request->origin,
            "destination" => $request->destination,
            "weight" => $request->weight,
            "courier" => $request->courier ?? $courierDefault,
            "price" => $request->price ?? $priceDefault
        ]);

        $priceDefault = "lowest";
        if ($response->status() !== 200) {
            return $this->sendError(error: $response->json()['meta']['message'], code: $response->json()['meta']['code']);
        }

        return $this->sendResponse(result: $response->json()["data"], message: $response->json()['meta']['message'], code: $response->json()['meta']['code']);
    }
}
