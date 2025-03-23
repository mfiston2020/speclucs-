<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\LensType;
use App\Models\PhotoChromatics;
use App\Models\PhotoCoating;
use App\Models\PhotoIndex;
use App\Models\Power;
use App\Models\Product;
use App\Models\Receipt;
use App\Models\ReceivedProduct;
use App\Models\SoldProduct;
use App\Models\Supplier;
use App\Repositories\StockTrackRepo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class ReceiptsController extends Controller
{
    private $stocktrackRepo, $allProduct;

    public function __construct()
    {
        $this->stocktrackRepo = new StockTrackRepo();
        $this->allProduct   =   Product::all();
    }

    public function index()
    {
        $receipts   =   Receipt::where('company_id', Auth::user()->company_id)->orderBy('created_at', 'desc')->get();
        return view('manager.recu.index', compact('receipts'));
    }

    public function add()
    {
        $provider   =   Supplier::where('company_id', Auth::user()->company_id)->get();
        return view('manager.recu.create', compact('provider'));
    }

    public function save(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'provider' => 'required',
        ]);
        $receipt   =   new Receipt();

        $receipt->title =   $request->title;
        $receipt->supplier_id =   $request->provider;
        $receipt->status =   'pending';
        $receipt->user_id =   Auth::user()->id;
        $receipt->company_id    =   Auth::user()->company_id;

        try {
            $receipt->save();
            return redirect()->route('manager.receipt.detail', Crypt::encrypt($receipt->id))->with('successMsg', 'Receipt Created Successfully');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
        }
    }

    public function detail($id)
    {
        $receiptDetail  =   Receipt::find(Crypt::decrypt($id));
        $products       =   ReceivedProduct::where(['receipt_id' => Crypt::decrypt($id)])->where('company_id', Auth::user()->company_id)->select('*')->get();
        return view('manager.recu.detail', compact('receiptDetail', 'products'));
    }

    public function addProdcut($id)
    {
        $products   =   Product::where('company_id', Auth::user()->company_id)->get();

        $id         =   Crypt::decrypt($id);

        $products   =   Product::orderBy('product_name', 'DESC')->where('company_id', Auth::user()->company_id)->where('category_id', '<>', '1')->get();

        $categories =   Category::all();
        $lens_types =   LensType::all();
        $chromatics =   PhotoChromatics::all();
        $coatings   =   PhotoCoating::all();
        $index      =   PhotoIndex::all();
        $categories =   Category::all();

        // return view('manager.recu.addProduct',compact('products','id','categories','lens_types','chromatics','coatings','index'));
        return view('manager.recu.productAdd', compact('products', 'id', 'categories', 'lens_types', 'chromatics', 'coatings', 'index'));
    }

    public function saveProdcut(Request $request)
    {
        $this->validate($request, [
            'product' => 'required',
            'stock' => 'required | integer',
        ]);

        $id   =   0;
        $existing_product   =   0;

        $_product   =   ReceivedProduct::where('company_id', Auth::user()->company_id)->get();

        foreach ($_product as $key => $item) {
            if ($request->product == $item->product_id && $request->receipt_id == $item->receipt_id) {
                $existing_product   =   1;
                $id                 =   $item->id;
            }
        }

        if ($existing_product == 0) {
            $products   =   new ReceivedProduct();

            $products->receipt_id   =   $request->receipt_id;
            $products->product_id   =   $request->product;
            $products->stock        =   $request->stock;
            $products->cost         =   $request->cost;
            $products->company_id   =   Auth::user()->company_id;

            try {
                $products->save();
                return redirect()->route('manager.receipt.detail', Crypt::encrypt($request->receipt_id))->with('successMsg', 'Product has been successfully Added!');
            } catch (\Throwable $th) {
                //throw $th;
                return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
            }
        } else {
            $product = ReceivedProduct::find($id);

            $product->stock        =   $request->stock + $product->stock;

            try {
                $product->save();
                return redirect()->route('manager.receipt.detail', Crypt::encrypt($request->receipt_id))->with('successMsg', 'Product has been successfully Added!');
            } catch (\Throwable $th) {
                //throw $th;
                return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
            }
        }
    }

    public function editDetail($id)
    {
        $product =   ReceivedProduct::find(Crypt::decrypt($id));
        return view('manager.recu.editProduct', compact('product'));
    }

    public function saveDetail(Request $request, $id)
    {
        $this->validate($request, [
            'stock' => 'required | integer',
        ]);

        $product    =   ReceivedProduct::find(Crypt::decrypt($id));
        $product->stock =   $request->stock;
        try {
            $product->save();
            return redirect()->route('manager.receipt.detail', Crypt::encrypt($product->receipt_id))->with('successMsg', 'Product has been successfully Updated!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
        }
    }

    public function finalizeReceipt(Request $request, $id)
    {
        $id =   Crypt::decrypt($id);
        $quantity   =   0;
        $allProducts    =   ReceivedProduct::where(['receipt_id' => $id])->where('company_id', Auth::user()->company_id)->select('*')->get();
        // dd($allProducts);

        if ($allProducts->isEmpty()) {
            return redirect()->back()->withInput()->with('warningMsg', 'Please add products first! ');
        } else {
            // getting all  the products of this invoice
            foreach ($allProducts as $product) {
                $p[]    =   $product->product_id;
            }

            $pro    =   array_unique($p);

            foreach ($pro as $product) {
                $product_   =   ReceivedProduct::where(['product_id' => $product])->where('receipt_id', $id)->where('company_id', Auth::user()->company_id)->select('stock')->get();

                foreach ($product_ as $all_quantity) {
                    $product_stock  =   Product::find($product);

                    // adding stock to all products
                    $quantity   =   $product_stock->stock + $all_quantity->stock;

                    // ================================================================================================================
                    $this->stocktrackRepo->saveTrackRecord($product_stock->id, $product_stock->stock, $all_quantity->stock, $quantity, 'receipt', 'rm', 'in');
                    // ================================================================================================================
                    $product_stock->stock   =   $quantity;
                    $product_stock->save();

                    $quantity   =   0;
                }
            }

            // updating the contents of the reciept
            $receipt    =   Receipt::find($id);

            $receipt->total_cost    =   $request->total_cost;
            $receipt->status        =   'completed';

            try {
                $receipt->save();
                return redirect()->route('manager.receipt.detail', Crypt::encrypt($id))->with('successMsg', 'Receipt has been successfully Finalized!');
            } catch (\Throwable $th) {
                //throw $th;
                return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
            }
        }
    }

    // ============================================
    public function invoiceDetail($id)
    {
        $id         =   Crypt::decrypt($id);
        $invoice    =   Invoice::where(['id' => $id])->with('soldproduct')->withsum('soldproduct', 'patient_payment')->withsum('unavailableProducts', 'price')->with('unavailableProducts')->first();

        $companyInfo    =   getuserCompanyInfo();

        return view('manager.receipt.detail', compact('invoice', 'companyInfo'));
    }

    public function newProduct(Request $request)
    {
        // return $request->all();
        $supplierID = Receipt::find($request->receipt_id);

        $product    =   new Product();

        // ========================================
        $lens_type  =   LensType::find($request->lens_type);
        $indx       =   PhotoIndex::find($request->index);
        $chro       =   PhotoChromatics::find($request->chromatics);
        $coat       =   PhotoCoating::find($request->coating);
        // ================================================================

        if (initials($lens_type->name) == 'SV') {
            $this->validate($request, [
                'lens_type' => 'required',
                'index' => 'required',
                'chromatics' => 'required',
                'coating' => 'required',
                // ======================
                'sphere' => 'required',
                'cylinder' => 'required',
                // ======================
                'lens_stock' => 'required | integer',
                'lens_price' => 'required | integer',
                'lens_cost' => 'required | integer',
            ]);
            $description    =   initials($lens_type->name) . " " . $indx->name . " " . $chro->name . " " . $coat->name;

            $product->category_id       =   $request->category;
            $product->product_name      =   $lens_type->name;
            $product->description       =   $description;
            $product->stock             =   '0';
            $product->deffective_stock  =   '0';
            $product->price             =   $request->lens_price;
            $product->cost              =   $request->lens_cost;
            $product->location           =   $request->location;
            $product->supplier_id       =   $supplierID->supplier_id;
            $product->company_id        =   Auth::user()->company_id;
            try {
                $product->save();

                // ================ Saving lens power =================
                $power  =   new Power();
                $power->product_id        =   $product->id;
                $power->type_id           =   $request->lens_type;
                $power->index_id          =   $request->index;
                $power->chromatics_id     =   $request->chromatics;
                $power->coating_id        =   $request->coating;
                $power->sphere            =   format_values($request->sphere);
                $power->cylinder          =   format_values($request->cylinder);
                $power->axis              =   format_values($request->axis);
                $power->add               =   format_values($request->add);
                $power->eye               =   'any';
                $power->company_id        =   Auth::user()->company_id;
                $power->save();

                // ================= adding the saved product to the receipt ===============

                $id   =   0;
                $existing_product   =   0;

                $_product   =   ReceivedProduct::where('company_id', Auth::user()->company_id)->get();

                foreach ($_product as $key => $item) {
                    if ($product->id == $item->product_id && $request->receipt_id == $item->receipt_id) {
                        $existing_product   =   1;
                        $id                 =   $item->id;
                    }
                }

                if ($existing_product == 0) {
                    $products   =   new ReceivedProduct();

                    $products->receipt_id   =   $request->receipt_id;
                    $products->product_id   =   $product->id;
                    $products->stock        =   $request->lens_stock;
                    $products->cost         =   $request->lens_cost;
                    $products->company_id   =   Auth::user()->company_id;

                    try {
                        $products->save();
                        return redirect()->route('manager.receipt.detail', Crypt::encrypt($request->receipt_id))->with('successMsg', 'Product has been successfully Added!');
                    } catch (\Throwable $th) {
                        //throw $th;
                        return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
                    }
                } else {
                    $product = ReceivedProduct::find($id);

                    $product->stock        =   $request->lens_stock + $product->stock;

                    try {
                        $product->save();
                        return redirect()->route('manager.receipt.detail', Crypt::encrypt($request->receipt_id))->with('successMsg', 'Product has been successfully Added!');
                    } catch (\Throwable $th) {
                        //throw $th;
                        return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
                    }
                }

                // return redirect()->route('manager.product')->with('successMsg','Product Created Successfully');
            } catch (\Throwable $th) {
                return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
            }
        } else {

            $this->validate($request, [
                'lens_type' => 'required',
                'index' => 'required',
                'chromatics' => 'required',
                'coating' => 'required',
                // ======================
                'sphere' => 'required',
                'cylinder' => 'required',
                'axis' => 'required',
                'add' => 'required',
                'eye' => 'required',
                // ======================
                'lens_stock' => 'required | integer',
                'lens_price' => 'required | integer',
                'lens_cost' => 'required | integer',
            ]);
            $description    =   initials($lens_type->name) . " " . $indx->name . " " . $chro->name . " " . $coat->name;

            $product->category_id       =   $request->category;
            $product->product_name      =   $lens_type->name;
            $product->description       =   $description . ' - ' . $request->eye;
            $product->stock             =   '0';
            $product->deffective_stock  =   '0';
            $product->price             =   $request->lens_price;
            $product->cost              =   $request->lens_cost;
            $product->location          =   $request->location;
            $product->supplier_id       =   $supplierID->supplier_id;
            $product->company_id        =   Auth::user()->company_id;
            try {
                $product->save();

                $power  =   new Power();
                $power->product_id        =   $product->id;
                $power->type_id           =   $request->lens_type;
                $power->index_id          =   $request->index;
                $power->chromatics_id     =   $request->chromatics;
                $power->coating_id        =   $request->coating;
                $power->sphere            =   format_values($request->sphere);
                $power->cylinder          =   format_values($request->cylinder);
                $power->axis              =   format_values($request->axis);
                $power->add               =   format_values($request->add);
                $power->eye               =   $request->eye;
                $power->company_id        =   Auth::user()->company_id;
                $power->save();


                // ===================================================
                // ================= adding the saved product to the receipt ===============

                $id   =   0;
                $existing_product   =   0;

                $_product   =   ReceivedProduct::where('company_id', Auth::user()->company_id)->get();

                foreach ($_product as $key => $item) {
                    if ($product->id == $item->product_id && $request->receipt_id == $item->receipt_id) {
                        $existing_product   =   1;
                        $id                 =   $item->id;
                    }
                }

                if ($existing_product == 0) {
                    $products   =   new ReceivedProduct();

                    $products->receipt_id   =   $request->receipt_id;
                    $products->product_id   =   $product->id;
                    $products->stock        =   $request->lens_stock;
                    $products->cost         =   $request->lens_cost;
                    $products->company_id   =   Auth::user()->company_id;

                    try {
                        $products->save();
                        return redirect()->route('manager.receipt.detail', Crypt::encrypt($request->receipt_id))->with('successMsg', 'Product has been successfully Added!');
                    } catch (\Throwable $th) {
                        //throw $th;
                        return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
                    }
                } else {
                    $product = ReceivedProduct::find($id);

                    $product->stock        =   $request->lens_stock + $product->stock;

                    try {
                        $product->save();
                        return redirect()->route('manager.receipt.detail', Crypt::encrypt($request->receipt_id))->with('successMsg', 'Product has been successfully Added!');
                    } catch (\Throwable $th) {
                        //throw $th;
                        return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
                    }
                }

                // return redirect()->route('manager.product')->with('successMsg','Product Created Successfully');
            } catch (\Throwable $th) {
                return redirect()->back()->withInput()->with('errorMsg', 'Sorry Something Went Wrong! ');
            }
        }
    }

    public function removeReceiptProduct($id)
    {
        $product    =   ReceivedProduct::find(Crypt::decrypt($id));
        // return $product;
        try {
            $product->delete();
            return redirect()->back()->with('successMsg', 'Product successfully removed');
        } catch (\Throwable $th) {
            return redirect()->back()->with('errorMsg', 'Something went wrong!');
        }
    }

    function packageList($id)
    {
        $id         =   Crypt::decrypt($id);
        $invoice    =   Invoice::where(['id' => $id])->with('soldproduct')->withsum('soldproduct', 'patient_payment')->withsum('unavailableProducts', 'price')->with('unavailableProducts')->first();

        $companyInfo    =   getuserCompanyInfo();

        return view('manager.receipt.package-list', compact('invoice', 'companyInfo'));
    }

    function deliveryNote($id)
    {
        $id         =   Crypt::decrypt($id);
        $invoice    =   Invoice::where(['id' => $id])->with('soldproduct')->withsum('soldproduct', 'patient_payment')->withsum('unavailableProducts', 'price')->with('unavailableProducts')->first();

        $companyInfo    =   getuserCompanyInfo();

        return view('manager.receipt.delivery-note', compact('invoice', 'companyInfo'));
    }
}
