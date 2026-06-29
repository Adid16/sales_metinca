<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\AuthViewController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PoListController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\ContractListController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\QuotationListController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserListController;
use App\Http\Controllers\UserCustomerListController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\RequestProjectController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PurchaseOrderItemController;
use App\Http\Controllers\PurchaseOrderInternalController;
use App\Http\Controllers\NegotiateController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('home.main');
});

require __DIR__ . '/auth.php';

// ============================================
// GUEST ROUTES (belum login)
// ============================================
Route::middleware('guest')->group(function () {

    // GET - Show Forms (Custom Views)
    Route::get('login', [AuthViewController::class, 'showLogin'])
        ->name('login');

    Route::get('register', [AuthViewController::class, 'showRegister'])
        ->name('register');

    Route::get('forgot-password', [AuthViewController::class, 'showForgotPassword'])
        ->name('password.request');

    Route::get('reset-password/{token}', [AuthViewController::class, 'showResetPassword'])
        ->name('password.reset');

    // POST - Handle Logic (Breeze Controllers)
    Route::post('register', [RegisteredUserController::class, 'store'])
        ->name('register.store');

    Route::post('login', [AuthenticatedSessionController::class, 'store'])
        ->name('login.store');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');

    Route::prefix('home')->group(function () {

        Route::get('/', function () {
            return view('home.main');
        })->name('home.main');

        Route::get('/products', function () {
            return view('home.products');
        })->name('home.products');

        Route::get('/divisions', function () {
            return view('home.divisions');
        })->name('home.divisions');

        Route::get('/facilities', function () {
            return view('home.facilities');
        })->name('home.facilities');

        Route::get('/gallery', function () {
            return view('home.galleries');
        })->name('home.gallery');

        Route::get('/contact', function () {
            $sales = User::where('role', '=', 'staff')->where('divisi', '=', 'sales')->get();
            return view('home.contacts', compact('sales'));
        })->name('home.contact');
    });

    Route::prefix('customer')->group(function () {

        Route::get('/', function () {
            return view('customer_home.main');
        })->name('customer_home.main');

        Route::get('/products', function () {
            return view('customer_home.products');
        })->name('customer_home.products');

        Route::get('/divisions', function () {
            return view('customer_home.divisions');
        })->name('customer_home.divisions');

        Route::get('/facilities', function () {
            return view('customer_home.facilities');
        })->name('customer_home.facilities');

        Route::get('/gallery', function () {
            return view('customer_home.galleries');
        })->name('customer_home.gallery');

        Route::get('/contact', function () {
            $sales = User::where('role', '=', 'staff')->where('divisi', '=', 'sales')->get();
            return view('customer_home.contacts', compact('sales'));
        })->name('customer_home.contact');

        Route::get('/login', function () {
            return view('auth.login2');
        })->name('customer_home.login');
        Route::get('/track', [\App\Http\Controllers\PurchaseOrderController::class, 'trackPublic'])->name('customer.track');
    });
});
Route::post('/requests-project', [RequestProjectController::class, 'store'])->name('requests-project.store');

// ============================================
// Auth ROUTES (sudah login)
// ============================================


Route::middleware(['auth'])->group(function () {

    Route::get('/users/customer', [UserController::class, 'index_customer'])->name('users.customer');

    // Export users
    Route::get('/users/export', [UserController::class, 'export'])->name('users.export');
    // Export customers
    Route::get('/users/customer/export', [UserController::class, 'exportCustomers'])->name('users.customers.export');

    Route::post('/users/customer/{requestProject}/from-request',[UserController::class, 'storeCustomer'])->name('users.customer.request');

    Route::resource('users', UserController::class);

    Route::get('/po-schedule',[PurchaseOrderController::class,'schedule'])->name('po-schedule.index');
    Route::put('/po-schedule/arrange',[PurchaseOrderController::class,'arrangeSchedule'])->name('po-schedule.arrange');
    Route::get('/purchase-orders/{id}/amandement',[PurchaseOrderController::class,'createAmandement'])->name('purchase-orders.create-amandement');
    Route::post('/purchase-orders/{id}/amandement',[PurchaseOrderController::class,'storeAmandement'])->name('purchase-orders.store-amandement');
    Route::get('contract-po/{idPO}/create', [PurchaseOrderController::class, 'createContract'])->name('purchase-orders.create-contract');

    //purchase order internal
    Route::get('/purchase-orders-internal', [PurchaseOrderInternalController::class, 'index'])->name('purchase-orders-internal.index');
    Route::get('/purchase-orders-internal/{purchaseOrder}/create', [PurchaseOrderInternalController::class, 'create'])->name('purchase-orders-internal.create');
    Route::post('/purchase-orders-internal/{purchaseOrder}', [PurchaseOrderInternalController::class, 'store'])->name('purchase-orders-internal.store');
    Route::get('/purchase-orders-internal/{purchaseOrder}', [PurchaseOrderInternalController::class, 'show'])->name('purchase-orders-internal.show');
    Route::get('/purchase-orders-internal/{purchaseOrder}/edit', [PurchaseOrderInternalController::class, 'edit'])->name('purchase-orders-internal.edit');
    Route::put('/purchase-orders-internal/{purchaseOrder}', [PurchaseOrderInternalController::class, 'update'])->name('purchase-orders-internal.update');
    Route::get('/purchase-orders-internal/item/{internal}', [PurchaseOrderInternalController::class, 'showItem'])->name('purchase-orders-internal.show-item');

    // Export purchase orders to Excel
    Route::get('/purchase-orders/export', [PurchaseOrderController::class, 'export'])->name('purchase-orders.export');
    Route::resource('purchase-orders', PurchaseOrderController::class);

    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    //account
    Route::get('/account', [AccountController::class, 'show'])->name('account.show');
    Route::get('/account/create',[AccountController::class, 'create'])->name('account.create');
    Route::post('/account',[AccountController::class, 'store'])->name('account.store');
    Route::get('/account/edit', [AccountController::class, 'edit'])->name('account.edit');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');
    
    //notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'notifikasi'])->name('notifikasi');
    Route::get('/notifikasi/{id}/read', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
    Route::post('/notifikasi/mark-all-read', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.markAllRead');

    //quotation
    Route::patch('send/{id}/quotation', [QuotationController::class, 'send'])->name('quotations.send');


    // Negotiate routes
    Route::get('quotations/{quotation}/negotiate', [NegotiateController::class, 'show'])->name('negotiate.show');
    Route::post('quotations/{quotation}/negotiate', [NegotiateController::class, 'store'])->name('negotiate.store');

    // View negotiate untuk staff/manager/admin (halaman baru)
    Route::get('quotations/{quotation}/show-nego', [NegotiateController::class, 'viewNego'])->name('negotiate.show-nego');
    
    //negotiate
    Route::get('quotations/{quotation}/negotiate', [NegotiateController::class, 'show'])->name('negotiate.show');
    Route::post('quotations/{quotation}/negotiate', [NegotiateController::class, 'store'])->name('negotiate.store');    // Export quotations to Excel
    Route::get('/quotations/export', [QuotationController::class, 'export'])->name('quotations.export');
    Route::resource('quotations', QuotationController::class);

    //export quotation to pdf
    Route::get('quotations/{quotation}/export-pdf', [QuotationController::class, 'exportPdf'])
            ->name('quotations.export-pdf');
    
    //polistint
    Route::get('/polistint', [PoListController::class, 'polistint'])->name('polistint');

    Route::patch('approver_manager/{contractId}/contract', [ContractController::class, 'approveManager'])->name('contracts.approve-manager');
    Route::patch('rejection_manager/{contractId}/contract', [ContractController::class, 'rejectManager'])->name('contracts.reject-manager');
    Route::get('/contract/{contractId}/pdf', [ContractController::class, 'generatePdf'])
    ->name('contract.pdf');
    Route::post('/contracts/{id}/finalize', [ContractController::class, 'finalize'])->name('contracts.finalize');

    //request project
    Route::post('requests-project/{id}/assign', [RequestProjectController::class, 'assign'])->name('requests-project.assign');

    // Export contracts to Excel
    Route::get('/contracts/export', [ContractController::class, 'export'])->name('contracts.export');

    Route::resource('contracts', ContractController::class);

    Route::get('/requests-project/customer/{id}',[RequestProjectController::class,'getByCustomer'])->name('requests-project.getByCustomer');
    Route::get('/requests-project/detail/{id}',[RequestProjectController::class,'detail'])->name('requests-project.detail');
    // Export request projects
    Route::get('/requests-project/export', [RequestProjectController::class, 'export'])->name('requests-project.export');

    Route::resource('requests-project', RequestProjectController::class)->except('store');

    Route::resource('articles',ArticleController::class);
    Route::get('/articles/requirements/{articleNo}', [ArticleController::class, 'requirements']);

    Route::get('/article-requirements/{partNumber}', [ArticleController::class, 'requirements']);
    
});
