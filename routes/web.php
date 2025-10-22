<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\layouts\Fluid;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\LivresController;
use App\Http\Controllers\Admin\AvisController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\ContributorController;
use App\Http\Controllers\BookEventController;
use Spatie\Prometheus\Http\Controllers\PrometheusMetricsController;
use App\Services\GroqEmbeddingService;

Route::get('/prometheus', PrometheusMetricsController::class);
// Front-Office
Route::middleware(['auth'])->group(function () {
    // Explore all public bibliotheques
    Route::get('/explore', [\App\Http\Controllers\FrontBibliothequeController::class, 'index'])->name('front.bibliotheques.index');
    // View a single public bibliotheque
    Route::get('/explore/bibliotheques/{id}', [\App\Http\Controllers\FrontBibliothequeController::class, 'show'])->name('front.bibliotheques.show');

    // Discussion routes
    Route::post('/discussions/{bibliothequeId}', [\App\Http\Controllers\DiscussionController::class, 'store'])->name('discussions.store');
    Route::post('/discussions/{discussionId}/comments', [\App\Http\Controllers\DiscussionController::class, 'storeComment'])->name('discussions.comments.store');
    Route::patch('/discussions/{discussionId}/resolve', [\App\Http\Controllers\DiscussionController::class, 'markResolved'])->name('discussions.resolve');
    Route::patch('/discussions/{discussionId}/unresolve', [\App\Http\Controllers\DiscussionController::class, 'markUnresolved'])->name('discussions.unresolve');
    Route::post('/comments/{commentId}/vote', [\App\Http\Controllers\DiscussionController::class, 'voteComment'])->name('comments.vote');
    Route::delete('/comments/{commentId}', [\App\Http\Controllers\DiscussionController::class, 'deleteComment'])->name('comments.delete');
    Route::get('/discussions/{discussionId}/comments', [\App\Http\Controllers\DiscussionController::class, 'getComments'])->name('discussions.comments.get');
    Route::post('/discussions/{discussionId}/summarize', [\App\Http\Controllers\DiscussionController::class, 'generateSummary'])->name('discussions.summarize');
});
Route::get('/home', function () {
    return view('front.home', ['useCustomJs' => true], ['usePreloader' => true]);
});

// Front events listing and details
Route::get('/events', [\App\Http\Controllers\FrontEventController::class, 'index'])->name('front.events.index');
Route::get('/events/{event}', [\App\Http\Controllers\FrontEventController::class, 'show'])->name('front.events.show');

// Front-end participations
Route::get('/my-participations', [\App\Http\Controllers\ParticipationDefiController::class, 'myParticipations'])->name('front.participations.my');

// Auth page (login + register)
Route::get('/auth', function () {
    return view('auth.register');
})->name('auth');
Route::post('/auth/login', [\App\Http\Controllers\authentications\LoginBasic::class, 'login'])->name('auth.login');
// Registration POST route
Route::post('/auth/register', [\App\Http\Controllers\authentications\LoginBasic::class, 'register'])->name('auth.register');
// Logout route (still needed)
Route::post('/logout', [\App\Http\Controllers\authentications\LoginBasic::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/password/forgot', [\App\Http\Controllers\authentications\ForgotPasswordBasic::class, 'index'])->name('password.request');
Route::post('/password/email', [\App\Http\Controllers\authentications\ForgotPasswordBasic::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [\App\Http\Controllers\authentications\ForgotPasswordBasic::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [\App\Http\Controllers\authentications\ForgotPasswordBasic::class, 'reset'])->name('password.update');

// Profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

// Livres routes
Route::get('/livres', [LivresController::class, 'index'])->name('livres');
Route::get('/livres/{livreId}/avis', [LivresController::class, 'getAvis']);
Route::post('/avis', [LivresController::class, 'storeAvis']);
Route::put('/avis/{avisId}', [LivresController::class, 'updateAvis']);
Route::delete('/avis/{avisId}', [LivresController::class, 'deleteAvis']);


// controlleur book event


// controlleur book event & nested defis (defis first to avoid route shadowing)
Route::prefix('book-events')->middleware(['auth','admin'])->group(function () {
    Route::resource('defis', \App\Http\Controllers\Admin\DefiController::class)->names([
        'index' => 'defis.index',
        'create' => 'defis.create',
        'store' => 'defis.store',
        'show' => 'defis.show',
        'edit' => 'defis.edit',
        'update' => 'defis.update',
        'destroy' => 'defis.destroy',
    ]);

    // Routes pour gérer l'association des livres aux défis
    Route::get('defis/{defi}/add-books', [\App\Http\Controllers\Admin\DefiController::class, 'addBooks'])->name('defis.add-books');
    Route::post('defis/{defi}/associate-books', [\App\Http\Controllers\Admin\DefiController::class, 'associateBooks'])->name('defis.associate-books');
    Route::delete('defis/{defi}/remove-book/{livre}', [\App\Http\Controllers\Admin\DefiController::class, 'removeBook'])->name('defis.remove-book');

    // Participants list for a defi
    Route::get('defis/{defi}/participants', [\App\Http\Controllers\Admin\DefiController::class, 'participants'])->name('defis.participants');

    // Ranking routes
    Route::get('defis/{defi}/ranking', [\App\Http\Controllers\Admin\RankingController::class, 'show'])->name('defis.ranking');
    Route::get('ranking/global', [\App\Http\Controllers\Admin\RankingController::class, 'global'])->name('ranking.global');
    Route::post('ranking/recalculate', [\App\Http\Controllers\Admin\RankingController::class, 'recalculate'])->name('ranking.recalculate');
});
Route::resource('book-events', BookEventController::class)->middleware(['auth', 'admin']);

// Main Page Route
// Set the root URL to the current /home view
Route::get('/', function () {
    return view('front.home', ['useCustomJs' => true], ['usePreloader' => true]);
});

// Admin Dashboard (list all bibliotheques) - admin only
Route::get('/admin', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware(['auth', 'admin']);
// Admin view of a single bibliotheque - admin only (moved to admin group)

// Move the dashboard analytics to /dashboard
Route::get('/dashboard', [Analytics::class, 'index'])->name('dashboard-analytics')->middleware(['auth', 'admin']);

// Contributor Dashboard (accessible to authenticated users)
Route::get('/contributor', function () {
    return redirect()->route('contributor.dashboard');
})->middleware(['auth', 'role:contributor,user']);

// Contributor Livres New (Book Metadata)
Route::get('/contributor/livres/new', [\App\Http\Controllers\ContributorController::class, 'livresNew'])->name('contributor.livres.new')->middleware(['auth', 'role:contributor,user']);
Route::post('/contributor/livres/new', [\App\Http\Controllers\ContributorController::class, 'livresStoreMetadata'])->name('contributor.livres.store-metadata')->middleware(['auth', 'role:contributor,user']);

// Contributor Livres Index
Route::get('/contributor/livres', [\App\Http\Controllers\ContributorController::class, 'livresIndex'])->name('contributor.livres.index')->middleware(['auth', 'role:contributor,user']);

Route::get('/contributor/livres/create', [\App\Http\Controllers\ContributorController::class, 'livresCreate'])
    ->name('contributor.livres.create')
    ->middleware(['auth', 'role:contributor,user']);

// Contributor Livres Show
Route::get('/contributor/livres/{livre}', [\App\Http\Controllers\ContributorController::class, 'livresShow'])->name('contributor.livres.show')->middleware(['auth', 'role:contributor,user']);

// Contributor Livres Edit
Route::get('/contributor/livres/{livre}/edit', [\App\Http\Controllers\ContributorController::class, 'livresEdit'])->name('contributor.livres.edit')->middleware(['auth', 'role:contributor,user']);

Route::put('/contributor/livres/{livre}', [\App\Http\Controllers\ContributorController::class, 'livresUpdate'])->name('contributor.livres.update')->middleware(['auth', 'role:contributor,user']);

// Contributor Livres Delete
Route::delete('/contributor/livres/{livre}', [\App\Http\Controllers\ContributorController::class, 'livresDestroy'])->name('contributor.livres.destroy')->middleware(['auth', 'role:contributor,user']);

// Contributor Livres Create (AJAX)
Route::post('/contributor/livres/create-book', [\App\Http\Controllers\ContributorController::class, 'createBook'])->name('contributor.livres.create-book')->middleware(['auth', 'role:contributor,user']);



// layout
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// authentication
Route::get('/auth', [LoginBasic::class, 'index'])->name('auth');

// Add login route alias for authentication redirects
Route::get('/login', function () {
    return redirect()->route('auth');
})->name('login');

// Test route for embeddings (separate from /auth)
Route::get('/test-embeddings', function () {
    try {
        $svc = new \App\Services\GroqEmbeddingService();
        $result = $svc->generateEmbedding('Hello world, this is a test for embeddings.');
        return response()->json([
            'ok' => true,
            'dimension' => $result['dimension'],
            'first10' => array_slice($result['vector'], 0, 10),
        ]);
    } catch (\Throwable $e) {
        return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
    }
});

// Quick test route for Groq embeddings (temporary)
Route::get('/groq-test', function (GroqEmbeddingService $svc) {
    try {
        $result = $svc->generateEmbedding('Hello world, this is a test for embeddings.');
        return response()->json([
            'ok' => true,
            'dimension' => $result['dimension'],
            'first10' => array_slice($result['vector'], 0, 10),
        ]);
    } catch (\Throwable $e) {
        return response()->json(['ok' => false, 'error' => $e->getMessage()], 500);
    }
});

// cards
Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

// User Interface
Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// icons
Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');

// form elements
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

// tables
Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');

// Emprunts and HistoriqueEmprunts CRUD routes
Route::resource('emprunts', App\Http\Controllers\EmpruntController::class)->middleware('auth');
Route::resource('historique-emprunts', App\Http\Controllers\HistoriqueEmpruntController::class)->middleware('auth');

// Routes pour l'emprunt de livres
Route::post('livres/{livre}/emprunter', [App\Http\Controllers\EmpruntController::class, 'emprunterLivre'])->name('livres.emprunter')->middleware('auth');
Route::post('emprunts/{emprunt}/retourner', [App\Http\Controllers\EmpruntController::class, 'retournerLivre'])->name('emprunts.retourner')->middleware('auth');

// Route pour afficher les détails d'un livre
Route::get('livres/{id}', [App\Http\Controllers\FrontLivreController::class, 'show'])->name('livres.show');

// Contributor Routes (Protected) - Accessible to contributors and users
Route::middleware(['auth'])->prefix('contributor')->name('contributor.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [ContributorController::class, 'dashboard'])->name('dashboard')->middleware('role:contributor,user');

    // My Reviews
    Route::get('/my-reviews', [ContributorController::class, 'myReviews'])->name('my-reviews')->middleware('role:contributor,user');

    // Bibliotheques Management
    Route::get('/bibliotheques', [ContributorController::class, 'bibliothequesIndex'])->name('bibliotheques.index');
    Route::get('/bibliotheques/create', [ContributorController::class, 'bibliothequesCreate'])->name('bibliotheques.create');
    Route::post('/bibliotheques', [ContributorController::class, 'bibliothequesStore'])->name('bibliotheques.store');
    Route::get('/bibliotheques/{bibliotheque}', [ContributorController::class, 'bibliothequesShow'])->name('bibliotheques.show');
    Route::get('/bibliotheques/{bibliotheque}/edit', [ContributorController::class, 'bibliothequesEdit'])->name('bibliotheques.edit');
    Route::put('/bibliotheques/{bibliotheque}', [ContributorController::class, 'bibliothequesUpdate'])->name('bibliotheques.update');
    Route::delete('/bibliotheques/{bibliotheque}', [ContributorController::class, 'bibliothequesDestroy'])->name('bibliotheques.destroy');

    // Book Selection for Library
    Route::get('/bibliotheques/{bibliotheque}/add-books', [ContributorController::class, 'bibliothequesAddBooks'])->name('bibliotheques.add-books');
    Route::post('/bibliotheques/{bibliotheque}/add-books', [ContributorController::class, 'bibliothequesStoreBooks'])->name('bibliotheques.store-books');

    // Livres Management
    Route::get('/livres/create', [ContributorController::class, 'livresCreate'])->name('livres.create');
    Route::post('/livres', [ContributorController::class, 'livresStore'])->name('livres.store');
    Route::post('/livres/create-book', [ContributorController::class, 'createBook'])->name('livres.create-book');
});

// Admin-only routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Reviews Management (admin only)
    Route::get('avis', [AvisController::class, 'index'])->name('avis.index');
    Route::get('avis/{avis}', [AvisController::class, 'show'])->name('avis.show');
    // Disabled avis modification routes (admin can only read)
    Route::get('avis/create', [AvisController::class, 'create'])->name('avis.create');
    Route::post('avis', [AvisController::class, 'store'])->name('avis.store');
    Route::get('avis/{avis}/edit', [AvisController::class, 'edit'])->name('avis.edit');
    Route::put('avis/{avis}', [AvisController::class, 'update'])->name('avis.update');
    Route::delete('avis/{avis}', [AvisController::class, 'destroy'])->name('avis.destroy');

    // Categories Management
    Route::resource('categories', CategorieController::class);

    // User Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

    // Admin Bibliotheques Management (same as contributor)
    Route::get('bibliotheques', [\App\Http\Controllers\AdminController::class, 'bibliothequesIndex'])->name('bibliotheques.index');
    Route::get('bibliotheques/create', [\App\Http\Controllers\AdminController::class, 'bibliothequesCreate'])->name('bibliotheques.create');
    Route::post('bibliotheques', [\App\Http\Controllers\AdminController::class, 'bibliothequesStore'])->name('bibliotheques.store');
    Route::get('bibliotheques/{bibliotheque}', [\App\Http\Controllers\AdminController::class, 'bibliothequesShow'])->name('bibliotheques.show');
    Route::get('bibliotheques/{bibliotheque}/edit', [\App\Http\Controllers\AdminController::class, 'bibliothequesEdit'])->name('bibliotheques.edit');
    Route::put('bibliotheques/{bibliotheque}', [\App\Http\Controllers\AdminController::class, 'bibliothequesUpdate'])->name('bibliotheques.update');
    Route::delete('bibliotheques/{bibliotheque}', [\App\Http\Controllers\AdminController::class, 'bibliothequesDestroy'])->name('bibliotheques.destroy');

    // Book Selection for Library
    Route::get('bibliotheques/{bibliotheque}/add-books', [\App\Http\Controllers\AdminController::class, 'bibliothequesAddBooks'])->name('bibliotheques.add-books');
    Route::post('bibliotheques/{bibliotheque}/add-books', [\App\Http\Controllers\AdminController::class, 'bibliothequesStoreBooks'])->name('bibliotheques.store-books');

    // Admin Livres Management (same as contributor)
    Route::get('livres', [\App\Http\Controllers\AdminController::class, 'livresIndex'])->name('livres.index');
    Route::get('livres/create', [\App\Http\Controllers\AdminController::class, 'livresCreate'])->name('livres.create');
    Route::post('livres', [\App\Http\Controllers\AdminController::class, 'livresStore'])->name('livres.store');
    Route::get('livres/{livre}', [\App\Http\Controllers\AdminController::class, 'livresShow'])->name('livres.show');
    Route::get('livres/{livre}/edit', [\App\Http\Controllers\AdminController::class, 'livresEdit'])->name('livres.edit');
    Route::put('livres/{livre}', [\App\Http\Controllers\AdminController::class, 'livresUpdate'])->name('livres.update');
    Route::delete('livres/{livre}', [\App\Http\Controllers\AdminController::class, 'livresDestroy'])->name('livres.destroy');
    Route::post('livres/create-book', [\App\Http\Controllers\AdminController::class, 'createBook'])->name('livres.create-book');

    // Admin Historique Emprunts Management
    Route::get('historique-emprunts', [\App\Http\Controllers\HistoriqueEmpruntController::class, 'adminIndex'])->name('historique-emprunts.index');
    Route::get('historique-emprunts/{historiqueEmprunt}', [\App\Http\Controllers\HistoriqueEmpruntController::class, 'adminShow'])->name('historique-emprunts.show');

    // Admin Book Events
    Route::get('book-events', [\App\Http\Controllers\BookEventController::class, 'index'])->name('book-events.index');
    Route::get('book-events/create', [\App\Http\Controllers\BookEventController::class, 'create'])->name('book-events.create');
    Route::post('book-events', [\App\Http\Controllers\BookEventController::class, 'store'])->name('book-events.store');
    Route::get('book-events/{book_event}', [\App\Http\Controllers\BookEventController::class, 'show'])->name('book-events.show');
    Route::get('book-events/{book_event}/edit', [\App\Http\Controllers\BookEventController::class, 'edit'])->name('book-events.edit');
    Route::put('book-events/{book_event}', [\App\Http\Controllers\BookEventController::class, 'update'])->name('book-events.update');
    Route::delete('book-events/{book_event}', [\App\Http\Controllers\BookEventController::class, 'destroy'])->name('book-events.destroy');

    // Admin Défis
    Route::get('defis', [\App\Http\Controllers\DefiController::class, 'index'])->name('defis.index');
    Route::get('defis/create', [\App\Http\Controllers\DefiController::class, 'create'])->name('defis.create');
    Route::post('defis', [\App\Http\Controllers\DefiController::class, 'store'])->name('defis.store');
    Route::get('defis/{defi}', [\App\Http\Controllers\DefiController::class, 'show'])->name('defis.show');
    Route::get('defis/{defi}/edit', [\App\Http\Controllers\DefiController::class, 'edit'])->name('defis.edit');
    Route::put('defis/{defi}', [\App\Http\Controllers\DefiController::class, 'update'])->name('defis.update');
    Route::delete('defis/{defi}', [\App\Http\Controllers\DefiController::class, 'destroy'])->name('defis.destroy');

    // Discussions (delete only - admin only)
    Route::delete('discussions/{discussion}', [\App\Http\Controllers\AdminController::class, 'deleteDiscussion'])->name('discussions.destroy');
});

// Discussions view (admin only)
Route::get('admin/bibliotheques/{id}/discussions', [\App\Http\Controllers\AdminController::class, 'bibliothequeDiscussions'])->name('admin.bibliotheques.discussions')->middleware(['auth', 'admin']);

// User role upgrade route
Route::post('/user/become-contributor', function() {
    $user = Auth::user();
    if ($user && $user->role === 'user') {
        $user->role = 'contributor';
        $user->save();
        return response()->json(['success' => true, 'message' => 'You are now a contributor!']);
    }
    return response()->json(['success' => false, 'message' => 'Unable to update role.']);
})->middleware('auth')->name('user.become-contributor');

// Recommendation routes
Route::middleware(['auth'])->prefix('recommendations')->name('recommendations.')->group(function () {
    Route::get('/', [App\Http\Controllers\RecommendationController::class, 'index'])->name('index');
    Route::post('/generate', [App\Http\Controllers\RecommendationController::class, 'generate'])->name('generate');
    // Allow GET for convenience when triggering from a link
    Route::get('/generate', [App\Http\Controllers\RecommendationController::class, 'generate'])->name('generate.get');
    // Front/AJAX endpoints
    Route::post('/generate-ajax', [App\Http\Controllers\RecommendationController::class, 'generateAjax'])->name('generate.ajax');
    Route::get('/list', [App\Http\Controllers\RecommendationController::class, 'listForUser'])->name('list');
    Route::get('/source/{source}', [App\Http\Controllers\RecommendationController::class, 'bySource'])->name('by-source');
    Route::post('/{recommendation}/mark-viewed', [App\Http\Controllers\RecommendationController::class, 'markAsViewed'])->name('mark-viewed');
    Route::get('/unviewed-count', [App\Http\Controllers\RecommendationController::class, 'unviewedCount'])->name('unviewed-count');
    Route::delete('/{recommendation}', [App\Http\Controllers\RecommendationController::class, 'destroy'])->name('destroy');
});

// Front-end Defi routes (accessible to all users)
Route::prefix('defis')->name('front.defis.')->group(function () {
    Route::get('/', [App\Http\Controllers\FrontDefiController::class, 'index'])->name('index');
    Route::get('/{defi}', [App\Http\Controllers\FrontDefiController::class, 'show'])->name('show');
});

// Participation Defi routes (requires authentication)
Route::middleware(['auth'])->prefix('participation-defis')->name('participation-defis.')->group(function () {
    Route::get('/my-participations', [App\Http\Controllers\ParticipationDefiController::class, 'myParticipations'])->name('my-participations');
    Route::get('/defi/{defi}/create', [App\Http\Controllers\ParticipationDefiController::class, 'create'])->name('create');
    Route::post('/defi/{defi}/store', [App\Http\Controllers\ParticipationDefiController::class, 'store'])->name('store');
    Route::get('/{participation}', [App\Http\Controllers\ParticipationDefiController::class, 'show'])->name('show');
    Route::get('/{participation}/modal-content', [App\Http\Controllers\ParticipationDefiController::class, 'modalContent'])->name('modal-content');
    Route::put('/{participation}/update-status', [App\Http\Controllers\ParticipationDefiController::class, 'updateStatus'])->name('update-status');
    Route::delete('/{participation}', [App\Http\Controllers\ParticipationDefiController::class, 'destroy'])->name('destroy');
});

// Test route
Route::get('/test-defi', function() {
    $defi = \App\Models\Defi::with('livres')->first();
    return response()->json(['defi' => $defi]);
});

// AI Quiz generation (Groq)
Route::post('/ai/quiz', [\App\Http\Controllers\QuizController::class, 'generate'])->name('ai.quiz.generate');
Route::middleware(['auth'])->post('/ai/quiz/from-participation/{participation}', [\App\Http\Controllers\QuizController::class, 'generateFromParticipation'])->name('ai.quiz.from-participation');
Route::middleware(['auth'])->post('/ai/quiz/save-score/{participation}', [\App\Http\Controllers\QuizController::class, 'saveScore'])->name('ai.quiz.save-score');

// Favorites routes (Frontend)
Route::middleware(['auth'])->prefix('favoris')->name('favoris.')->group(function () {
    Route::get('/', [App\Http\Controllers\Front\FavoriController::class, 'index'])->name('index');
    Route::post('/toggle/{livre}', [App\Http\Controllers\Front\FavoriController::class, 'toggle'])->name('toggle');
    Route::post('/{livre}', [App\Http\Controllers\Front\FavoriController::class, 'store'])->name('store');
    Route::delete('/{livre}', [App\Http\Controllers\Front\FavoriController::class, 'destroy'])->name('destroy');
    Route::get('/check/{livre}', [App\Http\Controllers\Front\FavoriController::class, 'check'])->name('check');
    Route::get('/count/{livre}', [App\Http\Controllers\Front\FavoriController::class, 'count'])->name('count');
});

// Reading Personality routes
Route::middleware(['auth'])->prefix('reading-personality')->name('reading-personality.')->group(function () {
    Route::get('/', [\App\Http\Controllers\ReadingPersonalityController::class, 'show'])->name('show');
    Route::post('/generate', [\App\Http\Controllers\ReadingPersonalityController::class, 'generate'])->name('generate');
    Route::post('/update', [\App\Http\Controllers\ReadingPersonalityController::class, 'update'])->name('update');
    Route::get('/data', [\App\Http\Controllers\ReadingPersonalityController::class, 'getPersonalityData'])->name('data');
    Route::get('/user/{user}', [\App\Http\Controllers\ReadingPersonalityController::class, 'showUser'])->name('show-user');
});
