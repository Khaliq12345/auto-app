<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('login');
})->name('login');

Route::post('login', [LoginController::class, 'login'])->name('login_post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('dashboard', function () {
    return view('home');
})->name('dashboard');

Route::get('cars', function () {
    return view('cars_details');
})->name('cars');

Route::get('assistant_creation/{mode}', function (Request $request, $mode)
{
    // include(resource_path('views/includes/globals.php'));
    // $new_form = new Formulaire("", "", 1, []);
    // Cache::forever('newForm', ['Bj']);
    $formtitle = $request->input('formtitle');

    if ($mode == 0) {
        // session()->put('newForm', new Formulaire("", $formtitle, 1, [], [new Pages(1, "", "<div  id=\"1\">
        // <p class=\"px-4 btn text-center bg-success-subtle border-black\" id=\"1\" onclick=\"openpage('1');\">Page 1 </p> 
        // </div>")]));
        session()->put('newForm', new Formulaire("", $formtitle, 1, [], [new Pages(1, "", "")]));
        
        return view('assistant_creation', [
            'mode'=> 0,
        ]);

    } else {
        $tmp = session('newForm');

        $codepages = $tmp->pages[0]->codec;
        $code = "";
        $nbq = 0;
        foreach ($tmp->question as $q) {
            if($tmp->page == $q->page){
                $code = $code.$q->codec;
                $nbq++;
            }
        }
        $titre = "";
        foreach ($tmp->pages as $p) {
            if($tmp->page == $p->id){
                $titre = $p->titre;
                break;
            }
        }

        return view('assistant_creation', [
            'mode'=> 1,
            'codepages'=> $codepages,
            'nbq'=> $nbq,
            'codeq'=> $code,
            'titre'=> $titre,
            'pageact'=> $tmp->page,
            'pagetot'=> count($tmp->pages),
        ]);
    }

    
});


// 

Route::get('/export-excel', [ExcelController::class, 'exportExcel'])->name('export.excel');
Route::post('/import-excel', [ExcelController::class, 'importExcel'])->name('import.excel');
Route::get('/import-form', [ExcelController::class, 'showImportForm'])->name('import.form');



