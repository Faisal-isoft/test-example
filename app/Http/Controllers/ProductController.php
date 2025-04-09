<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a list of all products.
     * Each product includes conversion to EUR using a real-time exchange rate.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::all();
        $exchangeRate = $this->getExchangeRate(); // Fetch exchange rate from USD to EUR

        return view('products.list', compact('products', 'exchangeRate'));
    }

    /**
     * Display the details of a single product.
     * Converts price to EUR using exchange rate.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $id = $request->route('product_id');
        $product = Product::findOrfail($id); // 404 if not found
        $exchangeRate = $this->getExchangeRate();

        return view('products.show', compact('product', 'exchangeRate'));
    }

    /**
     * Get the latest USD to EUR exchange rate using an external API.
     * If API fails, fall back to a default rate from the environment.
     *
     * @return float
     */
    private function getExchangeRate()
    {
        try {
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://open.er-api.com/v6/latest/USD", // External exchange rate API
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            // If API call is successful and contains EUR rate, return it
            if (!$err) {
                $data = json_decode($response, true);
                if (isset($data['rates']['EUR'])) {
                    return $data['rates']['EUR'];
                }
            }
        } catch (\Exception $e) {
            // Optionally log the error for monitoring/debugging
            // Log::error('Exchange rate fetch failed: ' . $e->getMessage());
        }

        // Fallback to a default rate if API fails
        return env('EXCHANGE_RATE', 0.85);
    }
}
