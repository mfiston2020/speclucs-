<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Livewire\Manager\PurchaseOrder;
use App\Http\Livewire\Manager\Report\AdjustMentReport;
use App\Http\Livewire\Manager\Report\ClosingReport;
use App\Http\Livewire\Manager\Report\ProductReport;
use App\Http\Livewire\Manager\Report\StockEfficiency;
use App\Http\Livewire\Manager\Report\StockHistory;
use App\Http\Livewire\Manager\Report\UserPerfomanceReport;
use App\Http\Livewire\Manager\Sales\BulkOrder;
use App\Http\Livewire\Manager\Sales\ProductRetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::user()) {
        return redirect()->intended('manager');
    } else {
        return view('auth.login');
    }
});

Route::get('lang/{lang}', ['as' => 'lang.switch', 'uses' => 'App\Http\Controllers\LanguageController@switchLang']);
Route::get('lang/{lang}', [App\Http\Controllers\LanguageController::class, 'switchLang'])->name('lang.switch');

Route::get('/home', [\App\Http\Controllers\Manager\DashboardController::class, 'index'])->middleware('manager');
Route::get('client', [\App\Http\Controllers\Client\DashboardController::class, 'index'])->name('client')->middleware('auth');
// Route::get('manager',[\App\Http\Controllers\Manager\DashboardController::class,'index'])->name('manager')->middleware('manager');


// ===========================================================================
// all routes for Admin   ====================================================
// ===========================================================================
Route::group(['middleware' => 'admin', 'prefix' => 'admin'], function () {
    // ============ all routes for company management ============================
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin');
    Route::get('/companies', [\App\Http\Controllers\Admin\CompaniesController::class, 'index'])->name('admin.companies');
    Route::get('/addcompanies', [\App\Http\Controllers\Admin\CompaniesController::class, 'add'])->name('admin.company.add');
    Route::post('/savecompanies', [\App\Http\Controllers\Admin\CompaniesController::class, 'save'])->name('admin.company.save');

    Route::get('/activateCompanies/{activateCompanies}', [\App\Http\Controllers\Admin\CompaniesController::class, 'activate'])->name('admin.company.activate');
    Route::get('/deactivateCompanies/{deactivateCompanies}', [\App\Http\Controllers\Admin\CompaniesController::class, 'deactivate'])->name('admin.company.deactivate');

    Route::get('/activateCompanies/clinic/{id}', [\App\Http\Controllers\Admin\CompaniesController::class, 'clinic_activate'])->name('admin.company.activate.clinic');
    Route::get('/deactivateCompanies/clinic/{id}', [\App\Http\Controllers\Admin\CompaniesController::class, 'clinic_deactivate'])->name('admin.company.deactivate.clinic');

    // ============= All categories routes =========================
    Route::get('/category', [\App\Http\Controllers\Admin\CategoriesController::class, 'index'])->name('admin.category');
    Route::get('/createCategory', [\App\Http\Controllers\Admin\CategoriesController::class, 'create'])->name('admin.category.create');
    Route::post('/saveCategory', [\App\Http\Controllers\Admin\CategoriesController::class, 'save'])->name('admin.category.save');
    Route::get('/deleteCategory/{deleteCategory}', [\App\Http\Controllers\Admin\CategoriesController::class, 'delete'])->name('admin.category.delete');

    // ============= All Payment methods routes =========================
    Route::get('/paymentMethods', [\App\Http\Controllers\Admin\PaymentMethodsController::class, 'index'])->name('admin.paymentMethods');
    Route::get('/createPaymentMethods', [\App\Http\Controllers\Admin\PaymentMethodsController::class, 'create'])->name('admin.paymentMethods.create');
    Route::get('/editPaymentMethods/{editPaymentMethods}', [\App\Http\Controllers\Admin\PaymentMethodsController::class, 'edit'])->name('admin.paymentMethods.edit');
    Route::post('/updatePaymentMethods/{updatePaymentMethods}', [\App\Http\Controllers\Admin\PaymentMethodsController::class, 'update'])->name('admin.paymentMethods.update');
    Route::post('/savePaymentMethods', [\App\Http\Controllers\Admin\PaymentMethodsController::class, 'save'])->name('admin.paymentMethods.save');
    Route::get('/removePaymentMethods/{removePaymentMethods}', [\App\Http\Controllers\Admin\PaymentMethodsController::class, 'delete'])->name('admin.remove.paymentMethod');

    // ====================== all routes about product settings =======================
    Route::get('/productSettings', [\App\Http\Controllers\Admin\ProductSettingsController::class, 'index'])->name('admin.productSettings');
    Route::post('/saveLensType', [\App\Http\Controllers\Admin\ProductSettingsController::class, 'saveLensType'])->name('admin.saveLensType');
    Route::post('/saveLensCoating', [\App\Http\Controllers\Admin\ProductSettingsController::class, 'saveLensCoating'])->name('admin.saveLensCoating');
    Route::post('/saveLensIndexes', [\App\Http\Controllers\Admin\ProductSettingsController::class, 'saveLensIndexes'])->name('admin.saveLensIndexes');
    Route::post('/saveLensPhotoChromatics', [\App\Http\Controllers\Admin\ProductSettingsController::class, 'saveLensPhotoChromatics'])->name('admin.saveLensPhotoChromatics');
    Route::post('/inlineupdate', [\App\Http\Controllers\Admin\UpdatingController::class, 'updateProductSettings'])->name('admin.update.psettings');


    Route::get('/deleteType/{deleteType}', [\App\Http\Controllers\Admin\ProductSettingsController::class, 'deleteType'])->name('admin.delete.type');
    Route::get('/deleteindex/{deleteindex}', [\App\Http\Controllers\Admin\ProductSettingsController::class, 'deleteindex'])->name('admin.delete.index');
    Route::get('/deleteCoating/{deleteCoating}', [\App\Http\Controllers\Admin\ProductSettingsController::class, 'deleteCoating'])->name('admin.delete.coating');
    Route::get('/deletechromatics/{deletechromatics}', [\App\Http\Controllers\Admin\ProductSettingsController::class, 'deletechromatics'])->name('admin.delete.chromatics');

    // ====================== all routes about Company settings =======================
    Route::get('/companySettings/{id}', [\App\Http\Controllers\Admin\CompaniesController::class, 'settings'])->name('admin.company.Settings');
    Route::get('/company/subscriptionPayment/{id}', [\App\Http\Controllers\Admin\CompaniesController::class, 'payment'])->name('admin.company.subscription.pay');
    Route::post('/settingsUpdate', [\App\Http\Controllers\Admin\CompaniesController::class, 'allowsms'])->name('admin.company.settings.update');

    // ====================== all routes about roles for users =======================
    Route::get('/user-management', [RoleController::class, 'index'])->name('admin.users.roles');
});

// ===========================================================================
// all routes for Manager ====================================================o
// ===========================================================================
// Route::group(['middleware'=>'manager','prefix'=>'manager','name'=>'manager.'], function()
Route::get('/manager', [\App\Http\Controllers\Manager\DashboardController::class, 'index'])->middleware('manager')->name('manager');

Route::prefix('manager')->name('manager.')->middleware('manager')->group(function () {
    // ============================== all routes for user profile =============================
    Route::get('/all_invoices', [\App\Http\Controllers\Manager\DashboardController::class, 'all_invoice'])->name('all.invoices');

    //===========================================================
    Route::get('/get_chart_data', [\App\Http\Controllers\Manager\ChartsController::class, 'getMonthlyData'])->name('getChartData');

    // ============================== all routes for user profile =============================
    Route::get('/profile', [\App\Http\Controllers\Manager\ProfileController::class, 'index'])->name('profile');
    Route::post('/profileInfo', [\App\Http\Controllers\Manager\ProfileController::class, 'username'])->name('profile.username');
    Route::post('/profilePassword', [\App\Http\Controllers\Manager\ProfileController::class, 'password'])->name('profile.password');
    Route::post('/profilecompany', [\App\Http\Controllers\Manager\ProfileController::class, 'company'])->name('profile.company');
    Route::post('/profile/Bank', [\App\Http\Controllers\Manager\ProfileController::class, 'bankDetail'])->name('bank.company');
    Route::post('/profile/sms/message', [\App\Http\Controllers\Manager\ProfileController::class, 'sms_message'])->name('profile.sms.message');

    Route::get('/profile/supplier/request/{id}', [\App\Http\Controllers\Manager\ProfileController::class, 'request_supply'])->name('profile.supplier.request');
    Route::get('/profile/supplier/reply/{id}/{reply}', [\App\Http\Controllers\Manager\ProfileController::class, 'request_supply_reply'])->name('profile.supplier.reply');

    // ================= All product routes =========================
    Route::get('/product', [\App\Http\Controllers\Manager\ProductsController::class, 'index'])->name('product');
    Route::post('/saveProduct', [\App\Http\Controllers\Manager\ProductsController::class, 'save'])->name('product.save');
    Route::get('/createProduct', [\App\Http\Controllers\Manager\ProductsController::class, 'create'])->name('product.create');
    Route::get('/importProduct', [\App\Http\Controllers\Manager\ProductsController::class, 'importProduct'])->name('product.import');
    Route::post('/import/update/Product', [\App\Http\Controllers\Manager\ProductsController::class, 'importProductUpdate'])->name('update.product.import');
    Route::post('/saveImportProduct', [\App\Http\Controllers\Manager\ProductsController::class, 'saveImport'])->name('product.import.save');
    Route::post('/importOtherProduct', [\App\Http\Controllers\Manager\ProductsController::class, 'importOtherProducts'])->name('product.import.other.product');

    // ============================== all routes for user settings =============================
    Route::get('/settings', [\App\Http\Controllers\Manager\SettingsController::class, 'index'])->name('settings');
    Route::post('/showStock', [\App\Http\Controllers\Manager\SettingsController::class, 'showStock'])->name('supplier.stock.state');
    Route::post('/supplierConfirm', [\App\Http\Controllers\Manager\SettingsController::class, 'supplierConfirm'])->name('supplier.confirm');

    // ============================== all routes for Clinic settings =============================
    Route::get('/clinic_settings', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'index'])->name('clinic.settings');

    // ============================== all routes for Clinic settings [ Lens Pricing ] =============================
    Route::post('/lensPricing', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'lensPricingSave'])->name('clinic.settings.lens.pricing.save');
    Route::delete('/remove/lensPricing/{id}', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'destroy'])->name('clinic.pricing.remove');

    // exam routes
    Route::post('/clinic_exam', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'save_clinic_exam'])->name('clinic.exam.save');
    Route::get('/clinic_exam_remove/{id}', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'save_clinic_exam_remove'])->name('exam.remove');

    // Insurances routes
    Route::post('/clinic_insurance', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'save_clinic_insurance'])->name('clinic.insurance.save');
    Route::get('/clinic_insurance_remove/{id}', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'save_clinic_insurance_remove'])->name('insurance.remove');

    // Complaint routes
    Route::post('/clinic_complaint', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'save_clinic_complaint'])->name('clinic.complaint.save');
    Route::get('/clinic_complaint_remove/{id}', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'save_complaint_remove'])->name('complaint.remove');

    // Complaint routes
    Route::post('/clinic_hospital', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'save_clinic_hospital'])->name('clinic.hospital.save');
    Route::get('/clinic_hospital_remove/{id}', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'save_hospital_remove'])->name('hospital.remove');

    // Complaint routes
    Route::post('/clinic_history', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'save_clinic_history'])->name('clinic.history.save');
    Route::get('/clinic_history_remove/{id}', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'save_history_remove'])->name('history.remove');

    // Insurances routes
    Route::post('/clinic_insurance_percentage', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'save_insurance_percentage'])->name('insurance.percentage.save');
    Route::get('/clinic_insurance_percentage_remove/{id}', [\App\Http\Controllers\Manager\ClinicSettingController::class, 'save_insurance_percentage_remove'])->name('insurance.percentage.remove');

    // ============= All suppliers routes =========================
    Route::get('/suppliers', [\App\Http\Controllers\Manager\SuppliersController::class, 'index'])->name('suppliers');
    Route::post('/saveSupplier', [\App\Http\Controllers\Manager\SuppliersController::class, 'save'])->name('supplier.save');
    Route::get('/editSupplier/{id}', [\App\Http\Controllers\Manager\SuppliersController::class, 'edit'])->name('supplier.edit');
    Route::get('/createSupplier', [\App\Http\Controllers\Manager\SuppliersController::class, 'create'])->name('supplier.create');
    Route::post('/updateSupplier', [\App\Http\Controllers\Manager\SuppliersController::class, 'update'])->name('supplier.update');

    // ============= All sales routes =========================
    Route::get('/sales', [\App\Http\Controllers\Manager\SalesController::class, 'index'])->name('sales');
    Route::get('/customerSales', [\App\Http\Controllers\Manager\SalesController::class, 'addCustomerSale'])->name('sales.customer.add');
    Route::post('/customerSales', [\App\Http\Controllers\Manager\SalesController::class, 'createCustomerInvoice'])->name('sales.customer');

    Route::get('/createSales', [\App\Http\Controllers\Manager\SalesController::class, 'create'])->name('sales.create');
    Route::get('/editSales/{editSales}', [\App\Http\Controllers\Manager\SalesController::class, 'edit'])->name('sales.edit');
    Route::post('/finalize/{finalize}', [\App\Http\Controllers\Manager\SalesController::class, 'finalize'])->name('sales.finalize');
    Route::get('/addSalesProduct/{addSalesProduct}', [\App\Http\Controllers\Manager\SalesController::class, 'addSalesProduct'])->name('sales.add');
    Route::post('/saveSales', [\App\Http\Controllers\Manager\SalesController::class, 'save'])->name('sales.save');
    Route::post('/fetchProduct', [\App\Http\Controllers\Manager\SalesController::class, 'fetchProduct'])->name('fetchProduct');

    Route::post('/fetchProductData', [\App\Http\Controllers\Manager\SalesController::class, 'fetchProductData'])->name('fetchProductData');

    Route::get('/removeSaleProduct/{removeSaleProduct}', [\App\Http\Controllers\Manager\SalesController::class, 'removeSaleProduct'])->name('sales.remove.product');
    Route::get('/editSaleProduct/{editSaleProduct}', [\App\Http\Controllers\Manager\SalesController::class, 'editSaleProduct'])->name('sales.edit.product');
    Route::post('/updateSaleProduct/{updateSaleProduct}', [\App\Http\Controllers\Manager\SalesController::class, 'updateSaleProduct'])->name('sales.update.product');

    Route::get('/payInvoice/{payInvoice}', [\App\Http\Controllers\Manager\SalesController::class, 'payInvoice'])->name('pay.invoice');
    Route::get('/due-payInvoice/{id}', [\App\Http\Controllers\Manager\SalesController::class, 'payDueInvoice'])->name('pay.invoice.due');
    Route::post('/payInvoice/{payInvoice}', [\App\Http\Controllers\Manager\SalesController::class, 'invoiceTransaction'])->name('invoice.pay');
    Route::post('/deleteInvoice/{deleteInvoice}', [\App\Http\Controllers\Manager\SalesController::class, 'invoicedelete'])->name('invoice.delete');

    // sales
    Route::get('/sales/retail', ProductRetail::class)->name('retail');
    Route::get('/sales/bulk/Orders', BulkOrder::class)->name('bulk.order');

    // ============= All lab requests routes =========================
    Route::get('/request/request/{type}', [\App\Http\Controllers\Manager\LabRequestController::class, 'indexWithTye'])->name('lab.requests.type');
    Route::get('/request/labRequest', [\App\Http\Controllers\Manager\LabRequestController::class, 'index'])->name('lab.requests');
    Route::get('/request/sendToLab/{id}', [\App\Http\Controllers\Manager\LabRequestController::class, 'sendToLab'])->name('send.request.lab');
    Route::get('/request/receive-order/{type}', [\App\Http\Controllers\Manager\LabRequestController::class, 'receiveOrder'])->name('receive.order.from');
    Route::post('/request/completed', [\App\Http\Controllers\Manager\LabRequestController::class, 'sendToCompleted'])->name('sent.request.to.complete');
    Route::post('/request/deliver', [\App\Http\Controllers\Manager\LabRequestController::class, 'sendToDelivered'])->name('sent.request.to.delivery');
    Route::post('/request/inStock-receive', [\App\Http\Controllers\Manager\LabRequestController::class, 'receiveRequest'])->name('sent.request.to.receive');
    Route::post('/request/inStock-dispense', [\App\Http\Controllers\Manager\LabRequestController::class, 'dispenseRequest'])->name('sent.request.to.dispense');
    Route::post('/request/send-to-production', [\App\Http\Controllers\Manager\LabRequestController::class, 'sendToProduction'])->name('sent.request.to.production');

    Route::post('/request/addPricing', [\App\Http\Controllers\Manager\LabRequestController::class, 'addpriceRequest'])->name('sent.request.to.addprice');

    Route::get('/request/confirmPayment/{id}', [\App\Http\Controllers\Manager\LabRequestController::class, 'requestConfirmPayment'])->name('sent.request.confirm.payment');

    Route::get('/request/cancelayment/{id}', [\App\Http\Controllers\Manager\LabRequestController::class, 'requestCancelPayment'])->name('sent.request.cancel.payment');

    // order pending when not in stock
    Route::post('/request/sendToSupplier', [\App\Http\Controllers\Manager\LabRequestController::class, 'sendRequestToSupplier'])->name('sent.request.send.to.supplier');
    Route::post('/request/sendTolab', [\App\Http\Controllers\Manager\LabRequestController::class, 'sendRequestTolab'])->name('sent.request.send.to.lab');

    Route::get('/request/labRequest/na', [\App\Http\Controllers\Manager\LabRequestController::class, 'naOrders'])->name('order.not.available');


    // pending orders
    Route::get('/pendingRequest', [\App\Http\Controllers\Manager\PendingOrderController::class, 'index'])->name('pending.orders');
    Route::get('/orderStatus', [\App\Http\Controllers\Manager\PendingOrderController::class, 'orderStatus'])->name('orders.status');
    Route::post('/pendingCancel', [\App\Http\Controllers\Manager\PendingOrderController::class, 'cancelOrder'])->name('pending.order.cancel');
    Route::post('/pendingRequest/setPrice', [\App\Http\Controllers\Manager\PendingOrderController::class, 'setOrderPrice'])->name('pending.order.price');
    Route::post('/pendingSell/setPrice', [\App\Http\Controllers\Manager\PendingOrderController::class, 'sellPendingOrder'])->name('pending.order.sell');
    Route::post('/pendingRequests/feedback', [\App\Http\Controllers\Manager\PendingOrderController::class, 'clientRequestfeedback'])->name('client.request.feedback');

    // pending sales
    Route::post('/adjustPrice', [\App\Http\Controllers\Manager\PendingOrderController::class, 'adjustPrice'])->name('adjust.order.price');
    Route::post('/sellProduct', [\App\Http\Controllers\Manager\PendingOrderController::class, 'sellProduct'])->name('sell.na.product');
    Route::get('/sellProductOff/{id}', [\App\Http\Controllers\Manager\PendingOrderController::class, 'sellProductOff'])->name('sell.na.product.off');

    // Route::get('/sales/order/tolab/{id}',[\App\Http\Controllers\Manager\SalesController::class,'send_lab_form'])->name('sales.send.to.lab');

    // ======================= All routes about receipt ==============================
    Route::get('/receipts', [\App\Http\Controllers\Manager\ReceiptsController::class, 'index'])->name('receipt');
    Route::get('/receiptsAdd', [\App\Http\Controllers\Manager\ReceiptsController::class, 'add'])->name('receipt.add');
    Route::post('/receiptsSave', [\App\Http\Controllers\Manager\ReceiptsController::class, 'save'])->name('receipt.save');
    Route::get('/receiptsDetail/{receiptsDetail}', [\App\Http\Controllers\Manager\ReceiptsController::class, 'detail'])->name('receipt.detail');
    Route::get('/receiptsProductAdd/{receiptsProductAdd}', [\App\Http\Controllers\Manager\ReceiptsController::class, 'addProdcut'])->name('receipt.add.product');
    Route::post('/receiptsProductAdd', [\App\Http\Controllers\Manager\ReceiptsController::class, 'saveProdcut'])->name('receipt.save.product');

    Route::get('/receiptEdit/{receiptEdit}', [\App\Http\Controllers\Manager\ReceiptsController::class, 'editDetail'])->name('edit.receipt');
    Route::post('/receiptSave/{receiptSave}', [\App\Http\Controllers\Manager\ReceiptsController::class, 'saveDetail'])->name('save.receipt');
    Route::post('/receiptfinalize/{receiptfinalize}', [\App\Http\Controllers\Manager\ReceiptsController::class, 'finalizeReceipt'])->name('finalize.receipt');

    Route::get('/receiptInvoice/{receiptInvoice}', [\App\Http\Controllers\Manager\ReceiptsController::class, 'invoiceDetail'])->name('invoice.receipt');

    Route::post('/receiptNewProduct', [\App\Http\Controllers\Manager\ReceiptsController::class, 'newProduct'])->name('receipt.new.product');
    Route::get('/receiptremoveProduct/{receiptremoveProduct}', [\App\Http\Controllers\Manager\ReceiptsController::class, 'removeReceiptProduct'])->name('receipt.remover.product');

    // ======================= All routes about expenses ==============================
    Route::get('/expenses', [\App\Http\Controllers\Manager\ExpensesController::class, 'index'])->name('expenses');
    Route::get('/expenseAdd', [\App\Http\Controllers\Manager\ExpensesController::class, 'add'])->name('expense.add');
    Route::post('/expenseAdd', [\App\Http\Controllers\Manager\ExpensesController::class, 'save'])->name('expense.save');
    Route::get('/expenseRemove/{expenseRemove}', [\App\Http\Controllers\Manager\ExpensesController::class, 'delete'])->name('expense.remove');

    // ======================= All routes about Income ==============================
    Route::get('/income', [\App\Http\Controllers\Manager\IncomeController::class, 'index'])->name('income');
    Route::get('/incomeAdd', [\App\Http\Controllers\Manager\IncomeController::class, 'add'])->name('income.add');
    Route::post('/incomeAdd', [\App\Http\Controllers\Manager\IncomeController::class, 'save'])->name('income.save');

    // ====================== all routes about payments =======================
    Route::get('/payment', [\App\Http\Controllers\Manager\PaymentsController::class, 'index'])->name('payment');
    Route::get('/paymentAdd', [\App\Http\Controllers\Manager\PaymentsController::class, 'add'])->name('payment.add');
    Route::post('/paymentAdd', [\App\Http\Controllers\Manager\PaymentsController::class, 'save'])->name('payment.save');
    Route::post('/fetchReceipt', [\App\Http\Controllers\Manager\PaymentsController::class, 'fetchReceipt'])->name('fetchReceipt');

    // ====================== all routes about export and import =======================
    Route::get('/exportProduct', [\App\Http\Controllers\Manager\ProductsController::class, 'exportProducts'])->name('product.export');
    // Route::get('/exportProduct', [\App\Http\Controllers\Manager\ProductsController::class, 'exportProducts'])->name('product.export');

    // ====================== all routes about Lens Matrix viewing =======================
    Route::get('/lensStock/{lensStock}', [\App\Http\Controllers\Manager\LensStockController::class, 'index'])->name('lens.stock');
    Route::post('/lensStockSearch', [\App\Http\Controllers\Manager\LensStockController::class, 'lensStockSearch'])->name('search.lens.stock');

    // ====================== all routes about Clients Management =======================
    Route::get('/clients', [\App\Http\Controllers\Manager\ClientsController::class, 'index'])->name('clients');
    Route::get('/addClients', [\App\Http\Controllers\Manager\ClientsController::class, 'addClient'])->name('client.add');
    Route::post('/addClients', [\App\Http\Controllers\Manager\ClientsController::class, 'saveClient'])->name('client.save');

    Route::get('/editClients/{editClients}', [\App\Http\Controllers\Manager\ClientsController::class, 'editClients'])->name('client.edit');
    Route::post('/editClients/{editClients}', [\App\Http\Controllers\Manager\ClientsController::class, 'updateClients'])->name('client.update');
    Route::get('/deleteClients/{deleteClients}', [\App\Http\Controllers\Manager\ClientsController::class, 'deleteClients'])->name('client.delete');

    // ======================= all routes about Orders ================================
    Route::get('/orders', [\App\Http\Controllers\Manager\OrdersController::class, 'index'])->name('orders');
    Route::get('/addOrders', [\App\Http\Controllers\Manager\OrdersController::class, 'addOrder'])->name('orders.add');

    // ======================= all routes about Customer ================================
    Route::get('/cutomerInvoice', [\App\Http\Controllers\Manager\CustomerInvoice::class, 'index'])->name('cutomerInvoice');
    Route::get('/cutomerInvoices', [\App\Http\Controllers\Manager\CustomerInvoice::class, 'search'])->name('cutomerInvoice.search');
    Route::post('/cutomerInvoicePay', [\App\Http\Controllers\Manager\CustomerInvoice::class, 'pay'])->name('cutomerInvoice.pay');
    Route::post('/cutomerInvoiceemail/{cutomerInvoiceemail}', [\App\Http\Controllers\Manager\CustomerInvoice::class, 'email'])->name('cutomerInvoice.email');

    // ======================= all routes about Customer Invoice emailing ================================
    Route::get('/customerInvoiceMail/{customerInvoiceMail}', [\App\Http\Controllers\Manager\CustomerInvoiceMailController::class, 'sendInvoice'])->name('cutomerInvoice.send.mail');


    // ======================= all routes about Inline editing ================================
    Route::post('/updateStock', [\App\Http\Controllers\Manager\UpdateContent::class, 'updateStock'])->name('edit.stock');

    // ======================= all routes about Statement Invoice ================================
    Route::get('/invoiceSummary', [\App\Http\Controllers\Manager\CustomerInvoice::class, 'summary'])->name('statementInvoice.summary');
    Route::get('/statementDetail/{statementDetail}', [\App\Http\Controllers\Manager\CustomerInvoice::class, 'detail'])->name('statementInvoice.detial');
    Route::post('/statementInvoice', [\App\Http\Controllers\Manager\CustomerInvoice::class, 'statementInvoiceCreate'])->name('statementInvoice.create');
    Route::get('/statementInvoice', [\App\Http\Controllers\Manager\CustomerInvoice::class, 'statementInvoiceSearch'])->name('statementInvoice.search');

    Route::post('/statementInvoicePay', [\App\Http\Controllers\Manager\CustomerInvoice::class, 'statementInvoicePay'])->name('statementInvoice.pay');

    Route::get('/statement/detail/{id}', [\App\Http\Controllers\Manager\CustomerInvoice::class, 'statementInvoice_detail'])->name('statementInvoice.details');

    // ====================== Routes about Reporting ==========================
    Route::get('/report/closing', ClosingReport::class)->name('closing.report');
    Route::get('/report/adjusted', AdjustMentReport::class)->name('adjustment.report');
    Route::get('/report/user-perfomance', UserPerfomanceReport::class)->name('user.perfomance.report');
    Route::get('/report', [\App\Http\Controllers\Manager\ReportingController::class, 'index'])->name('report');

    // ====================== Routes about Users management ==========================
    Route::get('/users', [\App\Http\Controllers\Manager\UsersController::class, 'index'])->name('users');
    Route::get('/addUser', [\App\Http\Controllers\Manager\UsersController::class, 'addUser'])->name('user.add');
    Route::post('/saveUser', [\App\Http\Controllers\Manager\UsersController::class, 'saveUser'])->name('user.save');
    Route::get('/editUser/{editUser}', [\App\Http\Controllers\Manager\UsersController::class, 'editUser'])->name('user.edit');
    Route::post('/updateUser/{updateUser}', [\App\Http\Controllers\Manager\UsersController::class, 'updateUser'])->name('user.update');

    Route::get('/disableUser/{disableUser}', [\App\Http\Controllers\Manager\UsersController::class, 'disableUser'])->name('user.disable');
    Route::get('/activateUser/{activateUser}', [\App\Http\Controllers\Manager\UsersController::class, 'activateUser'])->name('user.activate');

    // ====================== Routes about Orders ==========================
    Route::get('/myOrder', [\App\Http\Controllers\Manager\OrdersController::class, 'index'])->name('order');
    Route::get('/addOrder', [\App\Http\Controllers\Manager\OrdersController::class, 'add'])->name('order.add');
    Route::post('/placeOrder', [\App\Http\Controllers\Manager\OrdersController::class, 'placeOrder'])->name('order.placeOrder');
    Route::get('/cancelOrder/{cancelOrder}', [\App\Http\Controllers\Manager\OrdersController::class, 'cancelOrder'])->name('order.cancelOrder');
    Route::get('/receivedOrder/{receivedOrder}', [\App\Http\Controllers\Manager\OrdersController::class, 'receivedOrder'])->name('order.receivedOrder');
    Route::post('/fetchSupplierProduct', [\App\Http\Controllers\Manager\OrdersController::class, 'fetchSupplierProduct'])->name('order.fetchSupplierProduct');
    Route::get('/cancelLabOrder/{id}', [\App\Http\Controllers\Manager\OrdersController::class, 'cancelLabOrders'])->name('lab.order.cancel');

    // ====================== Routes about Received Orders ==========================
    Route::get('/receivedOrder', [\App\Http\Controllers\Manager\ReceivedOrdersController::class, 'index'])->name('received.order');
    Route::get('/newreceivedOrder', [\App\Http\Controllers\Manager\ReceivedOrdersController::class, 'new'])->name('received.order.new');
    Route::get('/inProductionOrder', [\App\Http\Controllers\Manager\ReceivedOrdersController::class, 'inProduction'])->name('received.order.inProduction');
    Route::get('/incompleteOrder', [\App\Http\Controllers\Manager\ReceivedOrdersController::class, 'incomplete'])->name('received.order.incomplete');
    Route::get('/indeliveryOrder', [\App\Http\Controllers\Manager\ReceivedOrdersController::class, 'indelivery'])->name('received.order.indelivery');

    Route::post('/toproductionOrder', [\App\Http\Controllers\Manager\ReceivedOrdersController::class, 'production'])->name('received.order.production');
    Route::post('/tocompletedOrder', [\App\Http\Controllers\Manager\ReceivedOrdersController::class, 'completed'])->name('received.order.completed');
    Route::post('/todeliveryOrder', [\App\Http\Controllers\Manager\ReceivedOrdersController::class, 'delivery'])->name('received.order.delivery');
    // Route::get('/receivedOrderDetail/{receivedOrderDetail}',[\App\Http\Controllers\Manager\ReceivedOrdersController::class,'receivedOrderDetail'])->name('received.order.detail');



    // ====================== Routes about Notifications ==========================
    Route::get('/read/{read}', [\App\Http\Controllers\Manager\NotificationsController::class, 'read'])->name('notification');
    Route::get('/clear', [\App\Http\Controllers\Manager\NotificationsController::class, 'clear'])->name('notification.clear');


    // ====================== Routes about Orders Invoicing ==========================
    Route::get('/myOrders', [\App\Http\Controllers\Manager\OrderInvoicesController::class, 'myOrders'])->name('my.order.invoice');
    Route::get('/searchOrders', [\App\Http\Controllers\Manager\OrderInvoicesController::class, 'searchOrders'])->name('search.order.invoice');
    Route::get('/searchMyInvoice', [\App\Http\Controllers\Manager\OrderInvoicesController::class, 'searchMyInvoice'])->name('search.my.invoice');
    Route::post('/payMyInvoice', [\App\Http\Controllers\Manager\OrderInvoicesController::class, 'payMyInvoice'])->name('search.my.invoice.pay');
    Route::get('/receivedOrders', [\App\Http\Controllers\Manager\OrderInvoicesController::class, 'receivedOrders'])->name('received.order.invoice');

    Route::get('/trackOrder', [\App\Http\Controllers\Manager\OrderInvoicesController::class, 'trackOrder'])->name('order.track');
    Route::post('/trackingOrder', [\App\Http\Controllers\Manager\OrderInvoicesController::class, 'trackingOrder'])->name('order.tracking');

    Route::get('/track/request/{id}', [\App\Http\Controllers\Manager\OrderInvoicesController::class, 'trackRequest'])->name('request.tracking');

    // ====================== Routes about Orders Credits ==========================
    Route::get('/Credits', [\App\Http\Controllers\Manager\OrderCreditsController::class, 'index'])->name('credit');
    Route::get('/myCredits', [\App\Http\Controllers\Manager\OrderCreditsController::class, 'myCredits'])->name('my.credit');
    Route::get('/myCredits/{myCredits}', [\App\Http\Controllers\Manager\OrderCreditsController::class, 'requestCredit'])->name('credit.request');
    Route::post('/saveCredits', [\App\Http\Controllers\Manager\OrderCreditsController::class, 'creditSave'])->name('credit.save');

    Route::get('/requestdCredits', [\App\Http\Controllers\Manager\OrderCreditsController::class, 'requestdcredit'])->name('requestd.credit');
    Route::post('/declineCredits', [\App\Http\Controllers\Manager\OrderCreditsController::class, 'declinecredit'])->name('decline.credit');
    Route::post('/acceptCredits', [\App\Http\Controllers\Manager\OrderCreditsController::class, 'acceptcredit'])->name('accept.credit');

    // ====================== Routes about Purchase Orders ==========================
    Route::get('/purchaseOrder', PurchaseOrder::class)->name('po');
    // Route::get('/purchase order', [\App\Http\Controllers\Manager\PurchaseOrderController::class, 'index'])->name('po');
    // Route::get('/purchase_order', [\App\Http\Controllers\Manager\PurchaseOrderController::class, 'proceed'])->name('proceed');
    // Route::post('/purchase_order_quotation', [\App\Http\Controllers\Manager\PurchaseOrderController::class, 'quotation'])->name('quotation');

    // ====================== Routes about Quotations ==========================
    Route::get('/quations', [\App\Http\Controllers\Manager\QuotationsController::class, 'index'])->name('quations');
    Route::get('/quations_detail/{quations_detail}', [\App\Http\Controllers\Manager\QuotationsController::class, 'quations_detail'])->name('quation.detail');
    Route::post('/quations_order', [\App\Http\Controllers\Manager\QuotationsController::class, 'quations_order'])->name('quation.order');

    // ====================== Routes about Patients management ==========================
    Route::get('/patient', [\App\Http\Controllers\Manager\PatientsController::class, 'index'])->name('patients');
    Route::get('/patientAdd', [\App\Http\Controllers\Manager\PatientsController::class, 'add'])->name('patient.add');
    Route::post('/patientSave', [\App\Http\Controllers\Manager\PatientsController::class, 'save'])->name('patient.save');
    Route::get('/patientDetail/{id}', [\App\Http\Controllers\Manager\PatientsController::class, 'detail'])->name('patient.detail');
    Route::get('/patient/remove/{id}', [\App\Http\Controllers\Manager\PatientsController::class, 'delete'])->name('patient.delete');
    Route::get('/patientDiagnose/{id}', [\App\Http\Controllers\Manager\PatientsController::class, 'diagnose'])->name('patient.diagnose');
    Route::get('/patient/invoices', [\App\Http\Controllers\Manager\PatientsController::class, 'all_invoices'])->name('patients.invoices');
    Route::get('/patient/file/edit/{id}', [\App\Http\Controllers\Manager\PatientsController::class, 'file_edit'])->name('patient.file.edit');
    Route::post('/patient/file/update', [\App\Http\Controllers\Manager\PatientsController::class, 'file_update'])->name('patient.file.update');
    Route::post('/patientDiagnoseSave', [\App\Http\Controllers\Manager\PatientsController::class, 'diagnoseSave'])->name('patient.diagnose.save');
    Route::get('/patient/file/detail/{id}', [\App\Http\Controllers\Manager\PatientsController::class, 'fileDetail'])->name('patient.file.detail');
    Route::get('/patient/file/delete/{id}', [\App\Http\Controllers\Manager\PatientsController::class, 'filedelete'])->name('patient.file.delete');
    Route::get('/patient/file/invoice/{id}', [\App\Http\Controllers\Manager\PatientsController::class, 'fileinvoice'])->name('patient.file.invoice');
    Route::get('/patient/file/remove/mp/{id}', [\App\Http\Controllers\Manager\PatientsController::class, 'removeMedication'])->name('patient.file.medical.remove');
    Route::get('/patientfinal/prescription/{id}', [\App\Http\Controllers\Manager\PatientsController::class, 'final_prescription'])->name('patient.final.prescription');
    Route::get('/patient/medical/prescription/{id}', [\App\Http\Controllers\Manager\PatientsController::class, 'medical_prescription'])->name('patient.medical.prescription');

    // ====================== Routes about Insurances and proforma invoices ==========================
    Route::get('/proforma', [\App\Http\Controllers\Manager\ProformaController::class, 'index'])->name('proforma');
    Route::get('/proforma/create', [\App\Http\Controllers\Manager\ProformaController::class, 'create'])->name('proforma.create');
    Route::post('/proforma/create/new', [\App\Http\Controllers\Manager\ProformaController::class, 'create_new'])->name('proforma.create.new');

    Route::get('/proforma/detail/{id}', [\App\Http\Controllers\Manager\ProformaController::class, 'detail'])->name('proforma.detail');
    Route::get('/proforma/add/product/{id}', [\App\Http\Controllers\Manager\ProformaController::class, 'addProduct'])->name('proforma.add.product');

    Route::get('/proforma/edit/product/{id}', [\App\Http\Controllers\Manager\ProformaController::class, 'editProduct'])->name('proforma.edit.product');
    Route::post('/proforma/update/product', [\App\Http\Controllers\Manager\ProformaController::class, 'updateProduct'])->name('proforma.update.product');

    Route::post('/proforma/add/product/save', [\App\Http\Controllers\Manager\ProformaController::class, 'saveProduct'])->name('proforma.save.product');
    Route::get('/proforma/add/product/remove/{id}', [\App\Http\Controllers\Manager\ProformaController::class, 'removeProduct'])->name('proforma.remove.product');
    Route::get('/proforma/add/product/finalize/{id}', [\App\Http\Controllers\Manager\ProformaController::class, 'finalizeProforma'])->name('proforma.finalize.proforma');
    Route::get('/proforma/add/print/{id}', [\App\Http\Controllers\Manager\ProformaController::class, 'printProforma'])->name('proforma.print.proforma');

    Route::get('/proforma/add/approve/{id}', [\App\Http\Controllers\Manager\ProformaController::class, 'approveProforma'])->name('proforma.approve.proforma');
    Route::get('/proforma/add/decline/{id}', [\App\Http\Controllers\Manager\ProformaController::class, 'declineProforma'])->name('proforma.decline.proforma');

    Route::get('/insurance/proforma', [\App\Http\Controllers\Manager\ProformaController::class, 'insuranceProforma'])->name('insurance.proforma');

    // ===================== New orders ===============
    Route::get('/newOrder', [\App\Http\Controllers\Manager\NewSalesController::class, 'newOrder'])->name('new.order');

    // stock reporting
    Route::get('/product-report', ProductReport::class)->name('product.report');
    Route::get('/product-history-report', StockHistory::class)->name('product.stock.report');
    Route::get('/stock-efficiency-report', StockEfficiency::class)->name('stock.efficiency.report');
});

// ===========================================================================
// all routes for Manager ====================================================
// ===========================================================================
Route::group(['middleware' => 'supplier', 'prefix' => 'supplier'], function () {
    Route::get('/', [\App\Http\Controllers\Supplier\DashboardController::class, 'index'])->name('supplier');

    // =============== all routes for Tarrif ========================
    Route::get('/pricing', [\App\Http\Controllers\Supplier\PricingController::class, 'index'])->name('supplier.pricing.index');
    Route::get('/addpricing', [\App\Http\Controllers\Supplier\PricingController::class, 'add'])->name('supplier.pricing.add');
    Route::post('/savepricing', [\App\Http\Controllers\Supplier\PricingController::class, 'save'])->name('supplier.pricing.save');


    // =============== all routes for Tarrif ========================
    Route::get('/orders', [\App\Http\Controllers\Supplier\OrdersController::class, 'index'])->name('supplier.orders.index');

    // ============================== all routes for Supplier profile =============================
    Route::get('/profile', [\App\Http\Controllers\Supplier\ProfileController::class, 'index'])->name('supplier.profile');
});
