<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RewardsController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MotoAssistantController;
use App\Http\Controllers\MotorcyclePassportController;
use App\Http\Controllers\MotorcycleAccountController;
use App\Http\Controllers\MotorcycleCatalogController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\PartRequestController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\Workshop1BookingController;
use App\Http\Controllers\Workshop1Controller;
use App\Http\Controllers\WorkshopApplicationController;
use App\Http\Controllers\WorkshopBookingController;
use App\Http\Controllers\WorkshopCatalogController;
use App\Http\Controllers\WorkshopCustomerController;
use App\Http\Controllers\WorkshopDashboardController;
use App\Http\Controllers\MotorcycleServiceRecordController;
use App\Http\Controllers\WorkshopMotorcycleController;
use App\Http\Controllers\WorkshopServiceRecordController;
use App\Models\Motorcycle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyServiceCenterController;
use App\Http\Controllers\ServiceBookingController;
use App\Http\Controllers\ServiceCenterController;
use App\Http\Controllers\MyServiceBookingsController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\TransportPartnerController;
use App\Http\Controllers\TransportAccountController;
use App\Http\Controllers\PartListingController;
use App\Models\PartListing;
use App\Models\Part;



/*
|--------------------------------------------------------------------------
| бѓ›бѓ—бѓђбѓ•бѓђбѓ бѓ бѓ’бѓ•бѓ”бѓ бѓ“бѓ
|--------------------------------------------------------------------------
*/


Route::get('/invite/{code}', function (string $code, Request $request) {
    $request->session()->put('referral_code', strtoupper($code));
    return redirect()->route('register');
})->where('code', '[A-Za-z0-9]{4,20}')->name('referral.invite');

Route::middleware('auth')->group(function () {
    Route::get('/rewards', [RewardsController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/redeem', [RewardsController::class, 'redeem'])->middleware('throttle:5,1')->name('rewards.redeem');
});

Route::get('/', function (Request $request) {
    $motorcycles = Motorcycle::with('images')
        ->where('status', 'published')
        ->when($request->filled('q'), function ($query) use ($request) {
            $search = trim($request->input('q'));

            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('model', 'like', "%{$search}%");
            });
        })
        ->when(
            $request->filled('budget')
                && is_numeric($request->input('budget'))
                && (float) $request->input('budget') >= 0,
            function ($query) use ($request) {
                $query->where('price', '<=', (float) $request->input('budget'));
            }
        )
        ->when(
            in_array($request->input('type'), ['new', 'used'], true),
            function ($query) use ($request) {
                $query->where('condition', $request->input('type'));
            }
        )
        ->orderByDesc('is_featured')
        ->orderByDesc('published_at')
        ->orderByDesc('created_at')
        ->limit(4)
        ->get();

$partListings = PartListing::query()
    ->with('user')
    ->where('status', 'published')
    ->latest()
    ->limit(4)
    ->get();

$partFitments = Part::query()
    ->where('is_active', true)
    ->get([
        'motorcycle_brand',
        'motorcycle_model',
        'motorcycle_year',
        'engine_cc',
    ])
    ->concat(
        PartListing::query()
            ->where('status', 'published')
            ->get([
                'motorcycle_brand',
                'motorcycle_model',
                'motorcycle_year',
                'engine_cc',
            ])
    )
    ->filter(fn ($item) => $item->motorcycle_brand)
    ->unique(fn ($item) => implode('|', [
        $item->motorcycle_brand,
        $item->motorcycle_model,
        $item->motorcycle_year,
        $item->engine_cc,
    ]))
    ->values();

$partCategories = Part::query()
    ->where('is_active', true)
    ->whereNotNull('category')
    ->pluck('category')
    ->merge(
        PartListing::query()
            ->where('status', 'published')
            ->whereNotNull('category')
            ->pluck('category')
    )
    ->filter()
    ->unique()
    ->sort()
    ->values();

return view('home', compact(
    'motorcycles',
    'partListings',
    'partFitments',
    'partCategories'
));
})->name('home');



/*
|--------------------------------------------------------------------------
| бѓЎбѓђбѓ—бѓђбѓ“бѓђбѓ бѓбѓ’бѓќ бѓњбѓђбѓ¬бѓбѓљбѓ”бѓ‘бѓбѓЎ бѓ›бѓќбѓ—бѓ®бѓќбѓ•бѓњбѓ”бѓ‘бѓ
|--------------------------------------------------------------------------
*/

Route::get('/part-requests', [PartRequestController::class, 'create'])
    ->name('part-requests.create');

Route::post('/part-requests', [PartRequestController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('part-requests.store');


/*
|--------------------------------------------------------------------------
| бѓ’бѓђбѓҐбѓбѓ бѓђбѓ•бѓ”бѓ‘бѓђ
|--------------------------------------------------------------------------
*/

Route::get('/rentals', [RentalController::class, 'index'])
    ->name('rentals.index');

Route::get('/rentals/{rentalScooter:slug}', [
    RentalController::class,
    'show',
])->name('rentals.show');

Route::post('/rentals/{rentalScooter:slug}/bookings', [
    RentalController::class,
    'store',
])->middleware(['auth', 'throttle:5,1'])
    ->name('rentals.bookings.store');


/*
|--------------------------------------------------------------------------
| MotoTransport вЂ” бѓ›бѓќбѓўбѓќбѓЄбѓбѓ™бѓљбѓ”бѓ‘бѓбѓЎ бѓўбѓ бѓђбѓњбѓЎбѓћбѓќбѓ бѓўбѓбѓ бѓ”бѓ‘бѓђ
|--------------------------------------------------------------------------
*/

Route::get('/transport', [TransportController::class, 'index'])
    ->name('transport.index');

Route::get('/transport/companies', [
    TransportController::class,
    'companies',
])->name('transport.companies');    

Route::get('/transport/companies/{slug}', [
    TransportController::class,
    'company',
])->name('transport.companies.show');

Route::get('/transport/create', [TransportController::class, 'create'])
    ->name('transport.create');

Route::post('/transport', [TransportController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('transport.store');

// бѓ’бѓђбѓ“бѓђбѓ›бѓ–бѓбѓ“бѓђбѓ•бѓбѓЎ бѓћбѓђбѓњбѓ”бѓљбѓ
Route::middleware('auth')
    ->prefix('transport/partner')
    ->name('transport.partner.')
    ->group(function () {
        Route::get('/', [
            TransportPartnerController::class,
            'index',
        ])->name('index');

        Route::get('/offers', [
            TransportPartnerController::class,
            'offers',
        ])->name('offers');

        Route::get('/orders', [
    TransportPartnerController::class,
    'orders',
])->name('orders');


Route::get('/orders/{transportRequest}', [
    TransportPartnerController::class,
    'showOrder',
])->whereNumber('transportRequest')
    ->name('orders.show');


        Route::post('/requests/{transportRequest}/offers', [
            TransportPartnerController::class,
            'storeOffer',
        ])->whereNumber('transportRequest')
            ->middleware('throttle:10,1')
            ->name('offers.store');
        Route::patch('/requests/{transportRequest}/status', [
    TransportPartnerController::class,
    'updateStatus',
])->whereNumber('transportRequest')
    ->middleware('throttle:10,1')
    ->name('requests.status');    
    });

// бѓ›бѓќбѓ›бѓ®бѓ›бѓђбѓ бѓ”бѓ‘бѓљбѓбѓЎ бѓћбѓбѓ бѓђбѓ“бѓ бѓўбѓ бѓђбѓњбѓЎбѓћбѓќбѓ бѓўбѓбѓ бѓ”бѓ‘бѓбѓЎ бѓ’бѓ•бѓ”бѓ бѓ“бѓ”бѓ‘бѓ
Route::middleware('auth')
    ->prefix('account')
    ->name('account.')
    ->group(function () {
        Route::get('/transport', [
            TransportAccountController::class,
            'index',
        ])->name('transport.index');

Route::get('/transport/{transportRequest}', [
    TransportAccountController::class,
    'show',
])->whereNumber('transportRequest')
    ->name('transport.show');

        Route::post('/transport/offers/{offer}/accept', [
            TransportAccountController::class,
            'acceptOffer',
        ])->whereNumber('offer')
            ->middleware('throttle:10,1')
            ->name('transport.offers.accept');
    });


/*    |--------------------------------------------------------------------------
| бѓ›бѓќбѓўбѓќбѓЄбѓбѓ™бѓљбѓ”бѓ‘бѓбѓЎ бѓ™бѓђбѓўбѓђбѓљбѓќбѓ’бѓ
|--------------------------------------------------------------------------
*/

Route::get('/moto-assistant', [MotoAssistantController::class, 'index'])->name('moto-assistant.index');
Route::post('/moto-assistant/diagnose', [MotoAssistantController::class, 'diagnose'])->middleware('throttle:10,1')->name('moto-assistant.diagnose');
Route::get('/motorcycles/{motorcycle:slug}/passport', [MotorcyclePassportController::class, 'show'])->name('motorcycles.passport');

Route::get('/motorcycles', [MotorcycleCatalogController::class, 'index'])
    ->name('motorcycles.index');

Route::get('/motorcycles/{motorcycle:slug}', function (Motorcycle $motorcycle) {
    abort_unless($motorcycle->status === 'published', 404);

    $motorcycle->load(['images', 'user', 'partner']);

    return view('motorcycles.show', compact('motorcycle'));
})->name('motorcycles.show');


/*
|--------------------------------------------------------------------------
| бѓ бѓ”бѓ’бѓбѓЎбѓўбѓ бѓђбѓЄбѓбѓђ бѓ“бѓђ бѓђбѓ•бѓўбѓќбѓ бѓбѓ–бѓђбѓЄбѓбѓђ
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1')
        ->name('register.store');

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});


/*
|--------------------------------------------------------------------------
| бѓЎбѓђбѓ—бѓђбѓ“бѓђбѓ бѓбѓ’бѓќ бѓњбѓђбѓ¬бѓбѓљбѓ”бѓ‘бѓбѓЎ бѓ™бѓђбѓўбѓђбѓљбѓќбѓ’бѓ
|--------------------------------------------------------------------------
*/

Route::get('/parts', [PartController::class, 'index'])
    ->name('parts.index');

Route::get('/parts/{source}/{id}', [PartController::class, 'show'])
    ->whereIn('source', ['store', 'marketplace'])
    ->whereNumber('id')
    ->name('parts.show');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
    
// бѓђбѓ®бѓђбѓљбѓ бѓ“бѓђ бѓ›бѓ”бѓќбѓ бѓђбѓ“бѓ бѓњбѓђбѓ¬бѓбѓљбѓ”бѓ‘бѓбѓЎ бѓ’бѓђбѓњбѓЄбѓ®бѓђбѓ“бѓ”бѓ‘бѓ”бѓ‘бѓ
Route::get('/parts/new', fn () => redirect()->route('parts.index', ['source' => 'marketplace', 'condition' => 'new']))
    ->name('part-listings.new');

Route::get('/parts/used', fn () => redirect()->route('parts.index', ['source' => 'marketplace', 'condition' => 'used']))
    ->name('part-listings.used');

/*
|--------------------------------------------------------------------------
| бѓ›бѓќбѓ›бѓ®бѓ›бѓђбѓ бѓ”бѓ‘бѓљбѓбѓЎ бѓћбѓбѓ бѓђбѓ“бѓ бѓ™бѓђбѓ‘бѓбѓњбѓ”бѓўбѓ
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('account')
    ->name('account.')
    ->group(function () {
        Route::get('/', [MotorcycleAccountController::class, 'index'])
            ->name('index');

        Route::get('/motorcycles/create', [MotorcycleAccountController::class, 'create'])
            ->name('motorcycles.create');

        Route::post('/motorcycles', [MotorcycleAccountController::class, 'store'])
            ->name('motorcycles.store');

        Route::get('/motorcycles/{motorcycle}/service', [
            MotorcycleServiceRecordController::class,
            'index',
        ])->whereNumber('motorcycle')
            ->name('motorcycles.service.index');

        Route::post('/motorcycles/{motorcycle}/service', [
            MotorcycleServiceRecordController::class,
            'store',
        ])->whereNumber('motorcycle')
            ->name('motorcycles.service.store');

        Route::get('/motorcycles/{motorcycle}/edit', [MotorcycleAccountController::class, 'edit'])
            ->whereNumber('motorcycle')
            ->name('motorcycles.edit');

        Route::put('/motorcycles/{motorcycle}', [MotorcycleAccountController::class, 'update'])
            ->whereNumber('motorcycle')
            ->name('motorcycles.update');

        Route::delete('/motorcycles/{motorcycle}', [MotorcycleAccountController::class, 'destroy'])
            ->whereNumber('motorcycle')
            ->name('motorcycles.destroy');

        Route::put('/profile', [MotorcycleAccountController::class, 'updateProfile'])
            ->name('profile.update');
 

        // бѓњбѓђбѓ¬бѓбѓљбѓ”бѓ‘бѓбѓЎ бѓ’бѓђбѓњбѓЄбѓ®бѓђбѓ“бѓ”бѓ‘бѓ”бѓ‘бѓбѓЎ бѓ›бѓђбѓ бѓ—бѓ•бѓђ
Route::get('/part-listings', [PartListingController::class, 'accountIndex'])
    ->name('part-listings.index');

Route::get('/part-listings/create', [PartListingController::class, 'create'])
    ->name('part-listings.create');

Route::post('/part-listings', [PartListingController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('part-listings.store');

Route::get('/part-listings/{partListing}/edit', [PartListingController::class, 'edit'])
    ->whereNumber('partListing')
    ->name('part-listings.edit');

Route::put('/part-listings/{partListing}', [PartListingController::class, 'update'])
    ->whereNumber('partListing')
    ->name('part-listings.update');

Route::delete('/part-listings/{partListing}', [PartListingController::class, 'destroy'])
    ->whereNumber('partListing')
    ->name('part-listings.destroy');    
    });


/*
|--------------------------------------------------------------------------
| бѓ«бѓ•бѓ”бѓљбѓ бѓЎбѓђбѓЇбѓђбѓ бѓќ бѓЎбѓђбѓ®бѓ”бѓљбѓќбѓЎбѓњбѓќбѓ”бѓ‘бѓбѓЎ бѓЎбѓбѓЎбѓўбѓ”бѓ›бѓђ
|--------------------------------------------------------------------------
*/

Route::get('/workshops', function (Request $request) {
    $workshops = \App\Models\Partner::query()
        ->where('type', 'workshop')
        ->where('application_status', 'approved')
        ->where('is_active', true)
        ->when($request->filled('q'), function ($query) use ($request) {
            $search = trim($request->input('q'));

            $query->where(function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        })
        ->withCount([
            'services as active_services_count' => function ($query) {
                $query->where('is_active', true);
            },
        ])
        ->orderBy('name')
        ->paginate(9)
        ->withQueryString();

    return view('workshops.catalog_rebuilt', compact('workshops'));
})->name('workshops.index');

Route::get('/workshops/{slug}', [WorkshopCatalogController::class, 'show'])
    ->name('workshops.show');

Route::get('/workshops/{slug}/booking', [
    WorkshopBookingController::class,
    'create',
])->name('workshops.booking.create');

Route::post('/workshops/{slug}/booking', [
    WorkshopBookingController::class,
    'store',
])->middleware('throttle:5,1')
    ->name('workshops.booking.store');


/*
|--------------------------------------------------------------------------
| бѓђбѓ•бѓўбѓќбѓ бѓбѓ–бѓ”бѓ‘бѓЈбѓљбѓ бѓЎбѓђбѓ®бѓ”бѓљбѓќбѓЎбѓњбѓќбѓЎ бѓ›бѓђбѓ бѓ—бѓ•бѓбѓЎ бѓЎбѓбѓЎбѓўбѓ”бѓ›бѓђ
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('workshop')
    ->name('workshop.')
    ->group(function () {
        Route::get('/apply', [WorkshopApplicationController::class, 'create'])
            ->name('apply');

        Route::post('/apply', [WorkshopApplicationController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('apply.store');

        Route::get('/dashboard', [WorkshopDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/customers', [WorkshopCustomerController::class, 'index'])
            ->name('customers.index');

        Route::post('/customers', [WorkshopCustomerController::class, 'store'])
            ->middleware('throttle:10,1')
            ->name('customers.store');

        Route::get('/customers/{customer}/edit', [WorkshopCustomerController::class, 'edit'])
            ->name('customers.edit');

        Route::put('/customers/{customer}', [WorkshopCustomerController::class, 'update'])
            ->name('customers.update');

        Route::delete('/customers/{customer}', [WorkshopCustomerController::class, 'destroy'])
            ->name('customers.destroy');

        Route::post('/customers/{customer}/motorcycles', [
            WorkshopMotorcycleController::class,
            'attach',
        ])->middleware('throttle:10,1')
            ->whereNumber('customer')
            ->name('customers.motorcycles.attach');

        Route::delete('/customers/{customer}/motorcycles/{motorcycle}', [
            WorkshopMotorcycleController::class,
            'detach',
        ])->whereNumber('customer')
            ->whereNumber('motorcycle')
            ->name('customers.motorcycles.detach');

        Route::get(
            '/customers/{customer}/motorcycles/{motorcycle}/service',
            [WorkshopServiceRecordController::class, 'index']
        )->whereNumber('customer')
            ->whereNumber('motorcycle')
            ->name('customers.motorcycles.service.index');

        Route::get(
            '/customers/{customer}/motorcycles/{motorcycle}/service/{record}/edit',
            [WorkshopServiceRecordController::class, 'edit']
        )->whereNumber('customer')
            ->whereNumber('motorcycle')
            ->whereNumber('record')
            ->name('customers.motorcycles.service.edit');

        Route::put(
            '/customers/{customer}/motorcycles/{motorcycle}/service/{record}',
            [WorkshopServiceRecordController::class, 'update']
        )->middleware('throttle:10,1')
            ->whereNumber('customer')
            ->whereNumber('motorcycle')
            ->whereNumber('record')
            ->name('customers.motorcycles.service.update');

        Route::delete(
            '/customers/{customer}/motorcycles/{motorcycle}/service/{record}',
            [WorkshopServiceRecordController::class, 'destroy']
        )->middleware('throttle:10,1')
            ->whereNumber('customer')
            ->whereNumber('motorcycle')
            ->whereNumber('record')
            ->name('customers.motorcycles.service.destroy');

        Route::post(
            '/customers/{customer}/motorcycles/{motorcycle}/service',
            [WorkshopServiceRecordController::class, 'store']
        )->middleware('throttle:10,1')
            ->whereNumber('customer')
            ->whereNumber('motorcycle')
            ->name('customers.motorcycles.service.store');  
    });


/*
|--------------------------------------------------------------------------
| MotoHub вЂ” бѓ›бѓќбѓ›бѓ®бѓ›бѓђбѓ бѓ”бѓ‘бѓљбѓбѓЎ бѓЎбѓ”бѓ бѓ•бѓбѓЎбѓбѓЎ бѓЇбѓђбѓ•бѓЁбѓњбѓ”бѓ‘бѓ
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('account')
    ->name('account.')
    ->group(function () {
        Route::get('/service-bookings', [
            MyServiceBookingsController::class,
            'index',
        ])->name('service-bookings.index');

        Route::patch('/service-bookings/{booking}/cancel', [
            MyServiceBookingsController::class,
            'cancel',
        ])->whereNumber('booking')
            ->name('service-bookings.cancel');
    });    


/*
|--------------------------------------------------------------------------
| Workshops1 вЂ” бѓђбѓ®бѓђбѓљбѓ, бѓ“бѓђбѓ›бѓќбѓЈбѓ™бѓбѓ“бѓ”бѓ‘бѓ”бѓљбѓ бѓЎбѓбѓЎбѓўбѓ”бѓ›бѓђ
|--------------------------------------------------------------------------
| бѓ”бѓЎ бѓ›бѓђбѓ бѓЁбѓ бѓЈбѓўбѓ”бѓ‘бѓ бѓ’бѓђбѓњбѓ–бѓ бѓђбѓ® бѓ›бѓќбѓ—бѓђбѓ•бѓЎбѓ”бѓ‘бѓЈбѓљбѓбѓђ auth бѓЇбѓ’бѓЈбѓ¤бѓбѓЎ бѓ’бѓђбѓ бѓ”бѓ—.
*/

Route::get('/workshops1', [
    Workshop1Controller::class,
    'index',
])->name('workshops1.index');

Route::get('/workshops1/{slug}', [
    Workshop1Controller::class,
    'show',
])->name('workshops1.show');

Route::get('/workshops1/{slug}/booking', [
    Workshop1BookingController::class,
    'create',
])->name('workshops1.booking.create');

Route::post('/workshops1/{slug}/booking', [
    Workshop1BookingController::class,
    'store',
])->name('workshops1.booking.store');

/*
|--------------------------------------------------------------------------
| MotoHub вЂ” бѓЎбѓђбѓ®бѓ”бѓљбѓќбѓЎбѓњбѓќбѓ”бѓ‘бѓ бѓ“бѓђ бѓќбѓњбѓљбѓђбѓбѓњ бѓ“бѓђбѓЇбѓђбѓ•бѓЁбѓњбѓђ
|--------------------------------------------------------------------------
*/

Route::get('/service-centers', [ServiceCenterController::class, 'index'])
    ->name('service-centers.index');

Route::get('/service-work-orders/{token}', [\App\Http\Controllers\ServiceCenterOperationsController::class, 'customerStatus'])
    ->middleware('throttle:30,1')->name('service-centers.work-orders.status');

Route::get('/service-centers/{serviceCenter:slug}', [
    ServiceCenterController::class,
    'show',
])->name('service-centers.show');

Route::middleware('auth')->group(function () {
    Route::get('/my-service-center/passport-records/create', [\App\Http\Controllers\ServiceCenterPassportController::class, 'create'])
        ->name('my-service-center.passport-records.create');

    Route::post('/my-service-center/passport-records', [\App\Http\Controllers\ServiceCenterPassportController::class, 'store'])
        ->middleware('throttle:10,1')
        ->name('my-service-center.passport-records.store');

    Route::get('/my-service-center', [
        MyServiceCenterController::class,
        'dashboard',
    ])->name('my-service-center.dashboard');

    
Route::get('/my-service-center/bookings', [
    MyServiceCenterController::class,
    'bookings',
])->name('my-service-center.bookings');

Route::get('/my-service-center/services', [
    MyServiceCenterController::class,
    'services',
])->name('my-service-center.services');

Route::get('/my-service-center/hours', [
    MyServiceCenterController::class,
    'hours',
])->name('my-service-center.hours');

Route::get('/my-service-center/profile', [
    MyServiceCenterController::class,
    'profile',
])->name('my-service-center.profile');


    Route::get('/my-service-center/register', [
        MyServiceCenterController::class,
        'register',
    ])->name('my-service-center.register');

    Route::post('/my-service-center/register', [
        MyServiceCenterController::class,
        'store',
    ])->name('my-service-center.store');

    Route::post('/my-service-center/services', [
        MyServiceCenterController::class,
        'storeService',
    ])->name('my-service-center.services.store');

    Route::put('/my-service-center/services/{service}', [
        MyServiceCenterController::class,
        'updateService',
    ])->name('my-service-center.services.update');

    Route::delete('/my-service-center/services/{service}', [
        MyServiceCenterController::class,
        'deleteService',
    ])->name('my-service-center.services.delete');

    Route::put('/my-service-center/hours', [
        MyServiceCenterController::class,
        'updateHours',
    ])->name('my-service-center.hours.update');

    Route::patch('/my-service-center/bookings/{booking}/status', [
        MyServiceCenterController::class,
        'updateBookingStatus',
    ])->name('my-service-center.bookings.status');

    Route::post('/service-centers/{serviceCenter:slug}/bookings', [
        ServiceBookingController::class,
        'store',
    ])->middleware('throttle:5,1')
      ->name('service-centers.bookings.store');
    
   Route::get('/service-centers/{serviceCenter:slug}/availability', [
    ServiceCenterController::class,
    'availability',
])->name('service-centers.availability');   

Route::post('/my-service-center/subscription', [MyServiceCenterController::class, 'requestSubscriptionChange'])
    ->name('my-service-center.subscription.request');

Route::get('/my-service-center/operations', [\App\Http\Controllers\ServiceCenterOperationsController::class, 'index'])
    ->name('my-service-center.operations');

Route::post('/my-service-center/technicians', [\App\Http\Controllers\ServiceCenterOperationsController::class, 'storeTechnician'])
    ->name('my-service-center.technicians.store');

Route::post('/my-service-center/bays', [\App\Http\Controllers\ServiceCenterOperationsController::class, 'storeBay'])
    ->name('my-service-center.bays.store');

Route::post('/my-service-center/inventory', [\App\Http\Controllers\ServiceCenterOperationsController::class, 'storeInventory'])
    ->name('my-service-center.inventory.store');

Route::patch('/my-service-center/work-orders/{workOrder}/assignment', [\App\Http\Controllers\ServiceCenterOperationsController::class, 'assign'])
    ->whereNumber('workOrder')->name('my-service-center.work-orders.assignment');

Route::post('/my-service-center/work-orders/{workOrder}/parts', [\App\Http\Controllers\ServiceCenterOperationsController::class, 'addPart'])
    ->whereNumber('workOrder')->name('my-service-center.work-orders.parts');

Route::post('/my-service-center/work-orders/{workOrder}/payments', [\App\Http\Controllers\ServiceCenterOperationsController::class, 'payment'])
    ->whereNumber('workOrder')->name('my-service-center.work-orders.payments');

Route::post('/my-service-center/work-orders/{workOrder}/updates', [\App\Http\Controllers\ServiceCenterOperationsController::class, 'notify'])
    ->whereNumber('workOrder')->name('my-service-center.work-orders.updates');

Route::get(
    '/my-service-center/work-orders',
    [\App\Http\Controllers\WorkOrderController::class, 'index']
)->name('my-service-center.work-orders.index');

Route::post(
    '/my-service-center/work-orders',
    [\App\Http\Controllers\WorkOrderController::class, 'store']
)->name('my-service-center.work-orders.store');

Route::patch(
    '/my-service-center/work-orders/{workOrder}/status',
    [\App\Http\Controllers\WorkOrderController::class, 'updateStatus']
)->whereNumber('workOrder')
  ->name('my-service-center.work-orders.status');

Route::get(
    '/my-service-center/work-orders/{workOrder}',
    [\App\Http\Controllers\WorkOrderController::class, 'show']
)->whereNumber('workOrder')
  ->name('my-service-center.work-orders.show');

  Route::get(
    '/my-service-center/work-orders/{workOrder}/edit',
    [\App\Http\Controllers\WorkOrderController::class, 'edit']
)->whereNumber('workOrder')
  ->name('my-service-center.work-orders.edit');

Route::put(
    '/my-service-center/work-orders/{workOrder}',
    [\App\Http\Controllers\WorkOrderController::class, 'update']
)->whereNumber('workOrder')
  ->name('my-service-center.work-orders.update');

});


/*
|--------------------------------------------------------------------------
| MotoHub вЂ” бѓћбѓбѓ бѓђбѓ“бѓ бѓЁбѓ”бѓўбѓ§бѓќбѓ‘бѓбѓњбѓ”бѓ‘бѓ”бѓ‘бѓ
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/messages', [MessageController::class, 'index'])
        ->name('messages.index');

    Route::post('/messages', [MessageController::class, 'start'])
        ->middleware('throttle:10,1')
        ->name('messages.start');

    Route::get('/messages/{conversation}', [MessageController::class, 'show'])
        ->whereNumber('conversation')
        ->name('messages.show');

    Route::post('/messages/{conversation}', [MessageController::class, 'store'])
        ->whereNumber('conversation')
        ->middleware('throttle:30,1')
        ->name('messages.store');
});


/*
|--------------------------------------------------------------------------
| MotoHub Community вЂ” бѓ›бѓќбѓўбѓќ бѓЎбѓђбѓ–бѓќбѓ’бѓђбѓ“бѓќбѓ”бѓ‘бѓђ
|--------------------------------------------------------------------------
*/
Route::get('/community', [CommunityController::class, 'index'])
    ->name('community.index');

Route::middleware('auth')->prefix('community')->name('community.')->group(function () {
    Route::post('/avatar', [CommunityController::class, 'uploadAvatar'])->middleware('throttle:5,1')->name('avatar');
    Route::post('/notifications/read', [CommunityController::class, 'markNotificationsRead'])->name('notifications.read');
    Route::post('/posts', [CommunityController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('posts.store');

    Route::post('/posts/{post}/comments', [CommunityController::class, 'comment'])
        ->whereNumber('post')
        ->middleware('throttle:10,1')
        ->name('posts.comments.store');

    Route::post('/posts/{post}/like', [CommunityController::class, 'toggleLike'])
        ->whereNumber('post')
        ->middleware('throttle:30,1')
        ->name('posts.like');

    Route::delete('/posts/{post}', [CommunityController::class, 'destroy'])
        ->whereNumber('post')
        ->name('posts.destroy');
});



/*
|--------------------------------------------------------------------------
| MotoHub Growth вЂ” My Garage, Marketplace 2.0 and Moto Clubs
|--------------------------------------------------------------------------
*/
Route::get('/hub', [\App\Http\Controllers\MotoHubFeaturesController::class, 'hub'])
    ->name('hub.index');

Route::get('/compare-motorcycles', [\App\Http\Controllers\MotoHubFeaturesController::class, 'compareMotorcycles'])
    ->name('motorcycles.compare');

Route::get('/clubs', [\App\Http\Controllers\MotoHubFeaturesController::class, 'clubs'])
    ->name('clubs.index');

Route::get('/clubs/create', [\App\Http\Controllers\MotoHubFeaturesController::class, 'createClub'])
    ->middleware('auth')
    ->name('clubs.create');

Route::get('/clubs/{slug}', [\App\Http\Controllers\MotoHubFeaturesController::class, 'showClub'])
    ->name('clubs.show');

Route::middleware('auth')->group(function () {
    Route::get('/my-garage', [\App\Http\Controllers\MotoHubFeaturesController::class, 'garage'])
        ->name('garage.index');

    Route::post('/my-garage/reminders', [\App\Http\Controllers\MotoHubFeaturesController::class, 'storeReminder'])
        ->middleware('throttle:10,1')
        ->name('garage.reminders.store');

    Route::patch('/my-garage/reminders/{reminder}/complete', [\App\Http\Controllers\MotoHubFeaturesController::class, 'completeReminder'])
        ->whereNumber('reminder')
        ->name('garage.reminders.complete');

    Route::post('/my-garage/expenses', [\App\Http\Controllers\MotoHubFeaturesController::class, 'storeExpense'])
        ->middleware('throttle:10,1')
        ->name('garage.expenses.store');

    Route::delete('/my-garage/expenses/{expense}', [\App\Http\Controllers\MotoHubFeaturesController::class, 'destroyExpense'])
        ->whereNumber('expense')
        ->name('garage.expenses.destroy');

    Route::get('/account/favorites', [\App\Http\Controllers\MotoHubFeaturesController::class, 'favorites'])
        ->name('favorites.index');

    Route::post('/favorites/motorcycles/{motorcycle}', [\App\Http\Controllers\MotoHubFeaturesController::class, 'toggleMotorcycleFavorite'])
        ->whereNumber('motorcycle')
        ->name('favorites.motorcycles.toggle');

    Route::post('/favorites/parts/{source}/{part}', [\App\Http\Controllers\MotoHubFeaturesController::class, 'togglePartFavorite'])
        ->whereIn('source', ['store', 'marketplace'])
        ->whereNumber('part')
        ->name('favorites.parts.toggle');

    Route::post('/clubs', [\App\Http\Controllers\MotoHubFeaturesController::class, 'storeClub'])
        ->middleware('throttle:5,1')
        ->name('clubs.store');

    Route::post('/clubs/{club}/join', [\App\Http\Controllers\MotoHubFeaturesController::class, 'joinClub'])
        ->whereNumber('club')
        ->middleware('throttle:10,1')
        ->name('clubs.join');

    Route::patch('/clubs/{club}/members/{member}/approve', [\App\Http\Controllers\MotoHubFeaturesController::class, 'approveClubMember'])
        ->whereNumber('club')->whereNumber('member')->name('clubs.members.approve');

    Route::post('/clubs/{club}/events', [\App\Http\Controllers\MotoHubFeaturesController::class, 'storeEvent'])
        ->whereNumber('club')
        ->middleware('throttle:5,1')
        ->name('clubs.events.store');

    Route::post('/clubs/events/{event}/attend', [\App\Http\Controllers\MotoHubFeaturesController::class, 'attendEvent'])
        ->whereNumber('event')
        ->middleware('throttle:10,1')
        ->name('clubs.events.attend');
});
