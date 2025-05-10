<?php

namespace App\Repositories;

use App\Interface\ProductInterface;
use App\Models\CompanyInformation;
use App\Models\Invoice;
use App\Models\LensType;
use App\Models\Order;
use App\Models\PhotoChromatics;
use App\Models\PhotoCoating;
use App\Models\PhotoIndex;
use App\Models\Power;
use App\Models\Product;
use App\Models\SoldProduct;
use App\Models\UnavailableProduct;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductRepo implements ProductInterface
{

    public String $message  =   '';
    public bool $showProductDetails  =   false;
    public $products, $productList;

    function searchProduct(array $productDescription)
    {
        // dd($productDescription['supplier']);
        if ($productDescription['productType'] == 'lens') {
            $lensTypeFull   =   LensType::find($productDescription['type']);

            if ($lensTypeFull) {

                if (initials($lensTypeFull->name) == 'SV') {
                    if (
                        $productDescription['type'] != null && $productDescription['index'] != null
                        && $productDescription['chromatic'] != null
                        && $productDescription['coating'] != null
                        && $productDescription['sphere'] != null
                        && $productDescription['cylinder'] != null
                    ) {

                        $product_id     =   Power::where('type_id', $productDescription['type'])
                            ->where('index_id', $productDescription['index'])
                            ->where('chromatics_id', $productDescription['chromatic'])
                            ->where('coating_id', $productDescription['coating'])
                            ->where('sphere', format_values($productDescription['sphere']))
                            ->where('cylinder', format_values($productDescription['cylinder']))
                            ->where('eye', 'any')
                            ->where('company_id', $productDescription['supplier'])
                            ->where('company_id', userInfo()->company_id)
                            ->select('product_id')->first();

                        $productResult   =   Product::find($product_id);
                        if (!$productResult) {
                            return 'product-not-found';
                        } else {
                            return $productResult;
                        }
                    } else {
                        return 'product-not-found';
                    }
                } else {

                    if (
                        $productDescription['type'] != null && $productDescription['index'] != null &&
                        $productDescription['chromatic'] != null && $productDescription['coating'] != null &&
                        $productDescription['sphere'] != null && $productDescription['cylinder'] != null &&
                        $productDescription['axis'] != null && $productDescription['addition'] != null && $productDescription['eye'] != null
                    ) {
                        $product_id     =   Power::where('type_id', $productDescription['type'])
                            ->where('index_id', $productDescription['index'])
                            ->where('chromatics_id', $productDescription['chromatic'])
                            ->where('coating_id', $productDescription['coating'])
                            ->where('sphere', format_values($productDescription['sphere']))
                            ->where('cylinder', format_values($productDescription['cylinder']))
                            ->where('axis', format_values($productDescription['axis']))
                            ->where('add', format_values($productDescription['addition']))
                            ->where('eye', $productDescription['type'] == '3' ?
                                ($productDescription['eye'] == 'right' ? 'Right' : 'Left') : ($productDescription['eye'] == 'right' ? 'R' : 'L'))
                            ->where('company_id', userInfo()->company_id)
                            ->where('company_id', $productDescription['supplier'])
                            ->select('product_id')->first();

                        $productResult    =   Product::find($product_id);
                        // dd($productResult);
                        if (!$productResult) {
                            return 'product-not-found';
                        } else {
                            return $productResult;
                        }
                    } else {
                        return 'product-not-found';
                    }
                }
            } else {
                return 'product-not-found';
            }
        } else {
            return   Product::find($productDescription['product_id']);
        }
    }

    // function to show the product stock efficiency
    function productStockEfficiency(string $product_id, int $usag, int $stoc, int $cat, int $days = null){

        $usage              =   0;
        $status             =   0;
        $quantityEfficiency =   0;
        $inventoryEfficiency =   0;
        $usage              =   $usag;
        $stock              =   $stoc;
        $category_id        =   $cat;


        if (($usage * 3) > $stock && $usage > 0) {
            $quantityEfficiency =   $stock - $usage;
            $inventoryEfficiency    =   ($stock / ($usage * 3)) * 100;
        }
        if (($usage * 3) < $stock && $stock > 0) {
            $quantityEfficiency =   $stock - $usage;
            $inventoryEfficiency    =   (($usage * 3) / $stock) * 100;
        }
        if (($usage * 3) > 0 && $stock && $stock == 0) {
            $quantityEfficiency =   $stock - $usage;
            $inventoryEfficiency    =   0;
        }
        if (($usage * 3) == 0 && $stock && $stock == 0) {
            $quantityEfficiency =   $stock - $usage;
            $inventoryEfficiency    =   0;
        }

        // crititcal
        if ($inventoryEfficiency <= 40 && $quantityEfficiency < 0) {
            $status =   'critical';
        }

        // High
        if ($inventoryEfficiency > 40 && $inventoryEfficiency <= 70 && $quantityEfficiency < 0) {
            $status =   'high';
        }

        // Medium
        if ($inventoryEfficiency > 70 && $inventoryEfficiency <= 100 && $quantityEfficiency < 0) {
            $status =   'medium';
        }
        // =============================================================

        // Medium
        if ($inventoryEfficiency > 70 && $inventoryEfficiency <= 100 && $quantityEfficiency >= 0) {
            $status =   'medium';
        }

        // Low
        if ($inventoryEfficiency > 40 && $inventoryEfficiency <= 70 && $quantityEfficiency >= 0) {
            $status =   'low';
        }

        // Over
        if ($inventoryEfficiency <= 40 && $quantityEfficiency >= 0) {
            $status =   'over';
        }

        // Discountinue
        if ($usage == 0 && $stock == 0) {
            $status =   'Discontinued';
        }


        // $leadTimeQuantity   =   ($po['usage']*$leadTime)/$totalDays;
        // $orderQuantity      =   (($po['usage']*2)+$leadTime)-$po['stock'];

        $po   =   [
            'category'  =>  $category_id,
            'stock'     =>  $stock,
            'usage'     =>  $usage,
            'minStock'  =>  $usage,
            'QtyTKeep'  =>  $usage * 3,
            'status'    =>  $status,
            'QtyEfficiency'     =>  $stock - $usage,
            'efficiency_ratio'  =>  round($inventoryEfficiency, 2) . '%',
        ];

        // dd($po);

        return $po;
    }


    // search for unavailable products
    function searchUnavailableProduct(array $productDescription)
    {
        // dd($productDescription)
        // if (initials(LensType::find($productDescription['type'])->pluck('name')->first())!='SV') {
        //     dd('here');
        //     $priceRange = LensPricing::where('type_id',$productDescription['type'])
        //                                         ->where('index_id',$productDescription['index'])
        //                                         ->where('chromatic_id',$productDescription['chromatic'])
        //                                         ->where('coating_id',$productDescription['coating'])
        //                                         ->where('sphere_from','<=',format_values($productDescription['sphere']))
        //                                         ->where('sphere_to','>=',format_values($productDescription['sphere']))
        //                                         ->where('cylinder_from','<=',format_values($productDescription['cylinder']))
        //                                         ->where('cylinder_to','>=',format_values($productDescription['cylinder']))
        //                                         ->where('addition_from','<=',format_values($productDescription['addition']))
        //                                         ->where('addition_to','>=',format_values($productDescription['addition']))
        //                                         ->select('price','cost')->first();
        // } else {
        //     $priceRange = LensPricing::where('type_id',$productDescription['type'])
        //                                         ->where('index_id',$productDescription['index'])
        //                                         ->where('chromatic_id',$productDescription['chromatic'])
        //                                         ->where('coating_id',$productDescription['coating'])
        //                                         ->where('sphere_from','<=',format_values($productDescription['sphere']))
        //                                         ->where('sphere_to','>=',format_values($productDescription['sphere']))
        //                                         ->where('cylinder_from','<=',format_values($productDescription['cylinder']))
        //                                         ->where('cylinder_to','>=',format_values($productDescription['cylinder']))
        //                                         ->select('price','cost')->first();
        // }

        // return $priceRange;
    }

    // save products
    function saveProduct(array $request, string $category, array $pending, bool $isOrder = false)
    {
        if ($category == '1' && $category != null) {
            // ============================================
            $product    =   new Product();

            $lens_type  =   LensType::find($request['type_id']);
            $index      =   PhotoIndex::find($request['index_id']);
            $chromatic  =   PhotoChromatics::find($request['chromatic_id']);
            $coating    =   PhotoCoating::find($request['coating_id']);


            if ($isOrder) {
                $eye        =   $request['eye'];
                $axis       =   $request['axis'];
                $sphere     =   $request['sphere'];
                $cylinder   =   $request['cylinder'];
                $add        =   $request['addition'];
            } else {
                $axis       =   $request['axis_r'] || $request['axis_l'];
                $sphere     =   $request['sphere_r'] || $request['sphere_l'];
                $cylinder   =   $request['cylinder_r'] || $request['cylinder_l'];
                $add        =   $request['addition_r'] || $request['addition_l'];
                $eye        =   $request['axis_r'] ? 'right' : 'left';
            }

            $description    =   initials($lens_type['name']) . " " . $index['name'] . " " . $chromatic['name'] . " " . $coating['name'];

            // checking the existance of the product
            $product_exists =   Power::where('type_id', $lens_type->id)
                ->where('index_id', $index->id)
                ->where('chromatics_id', $chromatic->id)
                ->where('coating_id', $coating->id)
                ->where('sphere', format_values($sphere))
                ->where('cylinder', format_values($cylinder))
                ->where('axis', format_values($axis))
                ->where('add', format_values($add))
                ->where('eye', initials($lens_type->name) == 'SV' ? 'any' : $eye)->first();


            $prdt   =   $product_exists == null ? null : Product::where('id', $product_exists->id)->first();

            if ($product_exists && $prdt->cost == $request['lens_cost']) {
                return redirect()->route('manager.product')->with('warningMsg', 'Product already exists');
            } else {
                if (initials($lens_type->name) == 'SV') {
                    $product->category_id       =   $category;
                    $product->product_name      =   $lens_type->name;
                    $product->description       =   $description;
                    $product->stock             =   count($pending) > 0 ? '1' : $request['lens_stock'];
                    $product->deffective_stock  =   '0';
                    $product->price             =   count($pending) > 0 ? $pending['price'] : $request['lens_price'];
                    $product->cost              =   count($pending) > 0 ? $pending['cost'] : $request['lens_cost'];
                    $product->fitting_cost      =   count($pending) > 0 ? '0' : $request['fitting_cost'];
                    $product->company_id        =   userInfo()->company_id;
                    $product->location          =   $pending['location'];
                    $product->supplier_id       =   $pending['supplier_id'] == null ? null : $pending['supplier_id'];
                    try {
                        $product->save();

                        $power                    =   new Power();
                        $power->product_id        =   $product->id;
                        $power->type_id           =   $lens_type->id;
                        $power->index_id          =   $index->id;
                        $power->chromatics_id     =   $chromatic->id;
                        $power->coating_id        =   $coating->id;
                        $power->sphere            =   format_values($sphere);
                        $power->cylinder          =   format_values($cylinder);
                        $power->axis              =   format_values($axis);
                        $power->add               =   format_values($add);
                        $power->eye               =   'any';
                        $power->company_id        =   userInfo()->company_id;
                        $power->save();
                    } catch (\Throwable $th) {
                        return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
                    }
                } else {

                    $product->category_id       =   $category;
                    $product->product_name      =   $lens_type->name;
                    $product->description       =   $description . ' - ' . $eye;;
                    $product->stock             =   count($pending) > 0 ? '1' : $request['lens_stock'];
                    $product->deffective_stock  =   '0';
                    $product->price             =   count($pending) > 0 ? $pending['price'] : $request['lens_price'];
                    $product->cost              =   count($pending) > 0 ? $pending['cost'] : $request['lens_cost'];
                    $product->fitting_cost      =   count($pending) > 0 ? '0' : $request['fitting_cost'];
                    $product->company_id        =   userInfo()->company_id;

                    $product->save();

                    $power                    =   new Power();
                    $power->product_id        =   $product->id;
                    $power->type_id           =   $lens_type->id;
                    $power->index_id          =   $index->id;
                    $power->chromatics_id     =   $chromatic->id;
                    $power->coating_id        =   $coating->id;
                    $power->sphere            =   format_values($sphere);
                    $power->cylinder          =   format_values($cylinder);
                    $power->axis              =   format_values($axis);
                    $power->add               =   format_values($add);
                    $power->eye               =   $eye;
                    $power->company_id        =   userInfo()->company_id;
                    $power->save();
                }
            }
            return $product;
        }
    }

    // make lab order function
    function makeLabOrder(array $request, string $product_id)
    {
        $lens_type  =   LensType::find($request['type_id']);
        $index      =   PhotoIndex::find($request['index_id']);
        $chromatic  =   PhotoChromatics::find($request['chromatic_id']);
        $coating    =   PhotoCoating::find($request['coating_id']);


        $order  =   new Order();

        $order->company_id      =   userInfo()->company_id;
        // $order->supplier_id     =   $request['supplier'];
        $order->pending_order_id =   $request['id'];
        $order->supplier_id     =   userInfo()->company_id;
        $order->product_id      =   $product_id;
        $order->type_id         =   $lens_type->id;
        $order->coating_id      =   $coating->id;
        $order->index_id        =   $index->id;
        $order->chromatic_id    =   $chromatic->id;
        // $order->order_number    =   $request['order_number'];
        $order->order_number    =   Order::where('company_id', userInfo()->company_id)->count() + 1;
        $order->patient_number  =   $request['patient_number'];
        $order->firstname       =   $request['firstname'];
        $order->lastname        =   $request['lastname'];
        $order->invoice_date    =   date('Y-m-d');
        $order->prescription    =   CompanyInformation::where('id', userInfo()->company_id)->pluck('company_name')->first();
        // $order->prescription    =   $request['prescription_hospital'] || CompanyInformation::where('id',userInfo()->company_id) ->pluck('company_name')->first();
        $order->order_cost      =   $request['order_cost'];
        // $order->frame_id        =   $request['frame'];
        $order->status          =   'submitted';
        $order->quantity        =   '1';

        $order->sphere_r    =   $request['sphere_r'];
        $order->cylinder_r  =   $request['cylinder_r'];
        $order->axis_r      =   $request['axis_r'];
        $order->addition_r  =   $request['addition_r'];

        $order->sphere_l    =   $request['sphere_l'];
        $order->cylinder_l  =   $request['cylinder_l'];
        $order->axis_l      =   $request['axis_l'];
        $order->addition_l  =   $request['addition_l'];

        try {
            $power          =   Power::where(['product_id' => $product_id])->select('*')->first();
            // $company        =   CompanyInformation::find($company_id);
            // $this_company   =   CompanyInformation::find(userInfo()->company_id);

            $product           =   Product::find($product_id);

            if (initials($product->product_name) == 'SV') {
                $lenPower   =   $product->description . " " . $power->sphere . " / " . $power->cylinder;
            } else {
                $lenPower   =   $product->description . " " . $power->sphere . " / " . $power->cylinder . " * " . $power->axis . " " . $power->add;
            }

            // Notification::route('mail', $company->company_email)->notify(new OrderPlaced($this_company->company_name,$lenPower));
            $order->save();

            // $notification   =   new SupplierNotify();
            // $notification->company_id   =   userInfo()->company_id;
            // // $notification->supplier_id  =   $company_id;
            // $notification->order_id     =   $order->id;
            // $notification->notification  =   'New Order Received';
            // $notification->save();

            return 'Payment Processed and Order sent to Lab!';
        } catch (\Throwable $th) {
            return 'Something Went Wrong!' . $th;
        }
    }

    // selling orders & creating the invoice for the client
    function sellPendingOrder(array $product)
    {

        $reference  =   count(DB::table('invoices')->select('reference_number')->where('company_id', userInfo()->company_id)->get());
        $pending    =   count(DB::table('invoices')->select('*')->where('status', '=', 'pending')->where('company_id', userInfo()->company_id)->where('user_id', '=', userInfo()->id)->get());

        $invoice    =   new Invoice();

        $invoice->reference_number  =   $reference + 1;
        $invoice->client_name       =   $product['firstname'] . ' ' . $product['lastname'];
        $invoice->phone             =   $product['patient_number'];
        $invoice->status            =   'pending';
        $invoice->user_id           =   userInfo()->id;
        $invoice->total_amount      =   '0';
        $invoice->company_id        =   userInfo()->company_id;
        $invoice->save();

        $sold   =   new SoldProduct();

        $sold->invoice_id   =   $invoice->id;
        $sold->product_id   =   $product['product_id'];
        $sold->quantity     =   '1';
        $sold->unit_price   =   $product['order_cost'];
        $sold->discount     =   '0';
        $sold->total_amount =   $product['order_cost'];
        $sold->company_id   =   Auth::user()->company_id;

        try {
            $sold->save();
            return $invoice->id;
        } catch (\Throwable $th) {
            return 'Sorry Something Went Wrong! ';
        }
    }

    // adding un available products
    function addUnavailableProduct(array $request)
    {
        $sold   =   new UnavailableProduct();

        $sold->company_id   =   Auth::user()->company_id;
        $sold->invoice_id   =   $request['invoice_id'];

        $sold->type_id      =   $request['type'];
        $sold->coating_id   =   $request['coating'];
        $sold->index_id     =   $request['index'];
        $sold->chromatic_id =   $request['chromatic'];

        $sold->eye       =   $request['eye'];
        $sold->sphere    =   $request['sphere'];
        $sold->cylinder  =   $request['cylinder'];
        $sold->axis      =   $request['axis'];
        $sold->addition  =   $request['addition'];

        $sold->price    =   $request['price'];
        $sold->cost     =   $request['cost'];

        $sold->mono_pd    =   $request['mono_pd'];
        $sold->segment_h  =   $request['segment_h'];

        $sold->save();
    }

    // save unavailable products to stock
    function saveUnavailableToStock(array $request)
    {
        $product    =   new Product();

        $lens_type  =   LensType::find($request['type_id']);
        $index      =   PhotoIndex::find($request['index_id']);
        $chromatic  =   PhotoChromatics::find($request['chromatic_id']);
        $coating    =   PhotoCoating::find($request['coating_id']);

        $description =   initials($lens_type['name']) . " " . $index['name'] . " " . $chromatic['name'] . " " . $coating['name'];

        $eye        =   $request['eye'];
        $add        =   $request['addition'];
        $axis       =   $request['axis'];
        $sphere     =   $request['sphere'];
        $cylinder   =   $request['cylinder'];


        $product->category_id       =   '1';
        $product->deffective_stock  =   '0';
        $product->fitting_cost      =   '0';
        $product->description       =   $description;
        $product->cost              =   $request['cost'];
        $product->product_name      =   $lens_type->name;
        $product->price             =   $request['price'];
        $product->stock             =   $request['quantity'];
        $product->location          =   $request['location'];
        $product->company_id        =   userInfo()->company_id;
        $product->wholesale_price   =   $request['wholesale_price'];
        $product->supplier_id       =   $request['supplier_id'];

        $product->save();

        $power                    =   new Power();
        $power->product_id        =   $product->id;
        $power->type_id           =   $lens_type->id;
        $power->index_id          =   $index->id;
        $power->chromatics_id     =   $chromatic->id;
        $power->coating_id        =   $coating->id;
        $power->sphere            =   format_values($sphere);
        $power->cylinder          =   format_values($cylinder);
        $power->axis              =   format_values($axis);
        $power->add               =   format_values($add);
        $power->eye               =   initials($lens_type->name) == 'SV' ? 'any' : $eye;
        $power->company_id        =   userInfo()->company_id;
        $power->save();

        return $product;
    }
}
