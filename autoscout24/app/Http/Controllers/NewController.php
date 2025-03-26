<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\directoryExists;

class Formulaire
{
    public $id;
    public $titre;
    public $page;
    public array $question;
    public array $pages;



    public function __construct($id, $titre, $page, $question, $pages)
    {

        $this->id = $id;
        $this->titre = $titre;
        $this->page = $page;
        $this->question = $question;
        $this->pages = $pages;
    }
}

class Question
{

    public $id;
    public $page;
    public $titre;
    public $type;
    public $option;
    public $codec;
    public $codep;



    public function __construct($id, $page, $titre, $type, $option, $codec, $codep)
    {
        $this->id = $id;
        $this->page = $page;
        $this->titre = $titre;
        $this->type = $type;
        $this->option = $option;
        $this->codec = $codec;
        $this->codep = $codep;
    }
}
class Pages
{

    public $id;
    public $titre;
    public $codec;

    public function __construct($id, $titre, $codec)
    {
        $this->id = $id;
        $this->titre = $titre;
        $this->codec = $codec;
    }
}

class Option
{

    public $id;
    public $titre;

    public function __construct($id, $titre,)
    {
        $this->id = $id;
        $this->titre = $titre;
    }
}

class NewController extends Controller
{
    
    public function openlogin()
    {
        return view('login');
    }

    public function radio()
    {
        return view('composants/radio');
    }

    public function store(Request $request)
    {

        // Validation des données du formulaire
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);
        $email = $request->input('email');
        $password = $request->input('password');

        $request = DB::table("Utilisateurs")
            ->where('email', $email)
            ->where('motdepasse', $password)
            ->first();
        if ($request) {
            return view('assistant_creation');
        } else {
            return view('avertissement');
        }
    }


    public function drop()
    {
        return view('composants/droipdown');
    }

    public function label()
    {
        return view('composants/label');
    }

    public function texte()
    {
        return view('composants/zonetexte');
    }


    // 
    // 
    // 

    public function apercu_p($dbname = '')
    {
        // $code1 = "";
        // $code = "";
        $ptcode1 = "";
        $ptcode = "";
        $pages = [];
        $codes = [];

        // trier et aligner selon les index

        foreach (session('newForm')->pages as $p) {

            

            $code = "";

            foreach (session('newForm')->question as $item) {
                if (intval($item->page) == intval($p->id)) {
                    $pages[] = $p->id;
                    // $code .= "<div id='cp$p->id'>";
                    $ptcode .= "<div id='cp$p->id'>";
                    break;
                }
            }
            

            foreach (session('newForm')->question as $q) {
            
                if ($q->page == $p->id ) {

                    if ($p->id == 1) {
                        // $code1 .= $q->codep;
                    } 
                    // else {
                        $code .= $q->codep;
                    // }
                    
                }
                
            }

            if ($p->id == 1) {
                $ptcode1 .= $p->titre;
            } 
            // else {
                $ptcode .= $p->titre;
            // }

            $ptcode .= "</div>";
            // $code .= "</div>";

            $codes[] = $code;
             
        }

        

        return view('apercu_de_question_p', [
            'title' => session('newForm')->titre,
            // 'code1' => $code1,
            'dbname' => $dbname,
            'ptcode1' => $ptcode1,
            'codes' => $codes,
            'ptcode' => $ptcode,
            'pages' => $pages,
            'nbpages' => count($pages),
        ]);
    }

    public function apercu()
    {
        // $code1 = "";
        // $code = "";
        $ptcode1 = "";
        $ptcode = "";
        $pages = [];
        $codes = [];

        // trier et aligner selon les index

        foreach (session('newForm')->pages as $p) {

            

            $code = "";

            foreach (session('newForm')->question as $item) {
                if (intval($item->page) == intval($p->id)) {
                    $pages[] = $p->id;
                    // $code .= "<div id='cp$p->id'>";
                    $ptcode .= "<div id='cp$p->id'>";
                    break;
                }
            }
            

            foreach (session('newForm')->question as $q) {
            
                if ($q->page == $p->id ) {

                    if ($p->id == 1) {
                        // $code1 .= $q->codep;
                    } 
                    // else {
                        $code .= $q->codep;
                    // }
                    
                }
                
            }

            if ($p->id == 1) {
                $ptcode1 .= $p->titre;
            } 
            // else {
                $ptcode .= $p->titre;
            // }

            $ptcode .= "</div>";
            // $code .= "</div>";

            $codes[] = $code;
             
        }

        

        return view('apercu_de_question', [
            'title' => session('newForm')->titre,
            // 'code1' => $code1,
            'ptcode1' => $ptcode1,
            'codes' => $codes,
            'ptcode' => $ptcode,
            'pages' => $pages,
            'nbpages' => count($pages),
        ]);
    }

    // 
    // 
    
    public function ajouter(Request $rq)
    {
        $view = $rq->input('vue');
        $title = $rq->input('title');
        $opt = $rq->input('opt');
        $pos = $rq->input('pos');
        // $idq = $rq->input('id');
        $ftype = $rq->input('type');

        $tmp = session('newForm');

        
            $idq = 0;
        
            foreach ($tmp->question as $tq) {
                if (intval($tq->id) >= $idq ) {
                    $idq = intval($tq->id);
                }
            }
            $idq++;
        

        $fopt = [];
        // $ftype = 1; //0 - label - 1 - input 2 - radio - drop 3 - checkbox

        for ($i=0; $i < count(explode('-&&&&-', $opt)) ; $i++) { 
            $item = explode('-&&&&-', $opt)[$i];

            
        
            $newoption = new Option($i, $item);
            $fopt[] = $newoption;
        }

        // if (!empty($fopt)) {
        //     $ftype = 2;
        // }

        $lodgindatalstx = view($view, [
            'id' => $idq,
            'title' => $title,
            'opt' => $fopt,
            'required' => $rq->input('required'),
        ])->render();

        $lodgindatalstxP = view($view.'p', [
            'id' => $idq,
            'title' => $title,
            'opt' => $fopt,
            'required' => $rq->input('required'),
        ])->render();

        $newquestion = new Question($idq, $tmp->page, $title, $ftype, $fopt, $lodgindatalstx, $lodgindatalstxP);

        $itl = 0;
        foreach ($tmp->question as $q) {
            if($newquestion->page == $q->page){
                $itl++;
            }
        }
        
        
        // if (intval($pos) == count($tmp->question) || count($tmp->question) == 0) {
        //     array_push($tmp->question , $newquestion);
        // } else {
        //     array_splice($tmp->question, intval($pos), 0, [$newquestion]);
        // }

        // problème danger

        if (intval($pos) == $itl || count($tmp->question) == 0) {
            array_push($tmp->question , $newquestion);
        } else {

            $itltt = 0;
            $itl = 0;
            foreach ($tmp->question as $q) {
                
                if($newquestion->page == $q->page){
                    
                    if (intval($pos) == $itl) {
                       
                        array_splice($tmp->question, $itltt, 0, [$newquestion]);
                    }
                    $itl++;
                }
                $itltt++;
            }
            
        }
        
        session()->put('newForm', $tmp);

        $code = "";
        foreach ($tmp->question as $q) {
            if($newquestion->page == $q->page){
                $code = $code.$q->codec;
            }
        }
        
        // include(resource_path('views/includes/globals.php'));
        // $new_form->question[] = 'Baba';
        // array_push( $new_form->question, 'Baba');
        // global $tmp ; 
        // $tmp = app('newForm');
        // $tmp = config('globals.newForm');
        // array_push($tmp , 'Baba');
        // app()->bind('newForm', function () {
        //     return  $GLOBALS['tmp'];
        // });
        // $this->atob($tmp, $tmp);
        // app()->instance('newForm', $tmp);
        // config(['globals.newForm' => [$tmp]]);
        // Config::set('globals.newForm', $tmp);
        // Config::write('globals', [
        //     'newForm' => $tmp,
        // ]);
        // app()->bind('newForm', function($tmp = $tmp){
        //     return $tmp;
        // });
        // $new_form->question[] = $newquestion;
        // $rj = json_decode($rq->getContent());

       
        
        // $new = ['new'];
        // if(Cache::has('newForm')){
        //     $tmp = Cache::get('newForm');
        //     $tmp[] = $new;
        //     Cache::put('newForm', $tmp);
            
        // } else {
        //     Cache::forever('newForm', $new);
        // }
        
        // $view = 'composants.zonetexte';
        
        return response()->json([
            // 'taille' => count($new_form->question),
            // 'taille' => Cache::get('newForm'),
            // 'taille' => $new_form->question,
            // 'taille' => app('newForm'),
            // 'taille' => config('globals.newForm'),
            'taille' => $newquestion->type,
            // 'taille' => count(session('newForm')->question),
            'statu' => 'success',
            'billetviewlst' => $code
        ]);
    }

    public function updater(Request $rq)
    {
        //  return response()->json([
        //     // 'taille' => $tmp->question[0]->code,
        //     'taille' => session('newForm')->question,
        //     'statu' => 'success',
        //     // 'billetviewlst' => $code,
        //     // 'div' => '#ajoutdiv > #qno1',
        //     // 'div' => '#ajoutdiv > #qno'.$idq,
        // ]);
        $view = $rq->input('vue');
        $title = $rq->input('title');
        $opt = $rq->input('opt');
        $idq = $rq->input('id');
        $pos = $rq->input('pos');
        $ftype = $rq->input('type');

       

        $tmp = session('newForm');

        $fopt = [];
        // $ftype = 1; //1 - rucu 2 - rucm 3 - rmcm
        

        for ($i=0; $i < count(explode('-&&&&-', $opt)) ; $i++) { 
            $item = explode('-&&&&-', $opt)[$i];
        
            $newoption = new Option($i, $item);
            $fopt[] = $newoption;
        }

        // if (!empty($fopt)) {
        //     $ftype = 2;
        // }

        $lodgindatalstx = view($view, [
            'id' => $idq,
            'title' => $title,
            'opt' => $fopt,
            'required' => $rq->input('required'),
        ])->render();

        $lodgindatalstxP = view($view.'p', [
            'id' => $idq,
            'title' => $title,
            'opt' => $fopt,
            'required' => $rq->input('required'),
        ])->render();

        $newquestion = new Question($idq, $tmp->page, $title, $ftype, $fopt, $lodgindatalstx, $lodgindatalstxP);

        foreach ($tmp->question as &$key) {
            if (intval($key->id) == intval($idq)) {
                $ind = array_search($key, $tmp->question);
                if ($ind !== false) {
                    unset($tmp->question[$ind]);
                } else {
                    # code...
                }
                break;
            }
        }

        $itl = 0;
        foreach ($tmp->question as $q) {
            if($newquestion->page == $q->page){
                $itl++;
            }
        }
        
        
        // if (intval($pos) == count($tmp->question) || count($tmp->question) == 0) {
        //     array_push($tmp->question , $newquestion);
        // } else {
        //     array_splice($tmp->question, intval($pos), 0, [$newquestion]);
        // }
        if (intval($pos) == $itl || count($tmp->question) == 0) {
            array_push($tmp->question , $newquestion);
        }  else {
            $itltt = 0;
            $itl = 0;
            foreach ($tmp->question as $q) {
                
                if($newquestion->page == $q->page){
                    
                    if (intval($pos) == $itl) {
                       
                        array_splice($tmp->question, $itltt, 0, [$newquestion]);
                    }
                    $itl++;
                }
                $itltt++;
            }
            
        }


        session()->put('newForm', $tmp);

        $code = "";
        foreach ($tmp->question as $q) {
            if($newquestion->page == $q->page){
                $code = $code.$q->codec;
            }
        }

        
        return response()->json([
            // 'taille' => $tmp->question[0]->code,
            'taille' => count(session('newForm')->question),
            'statu' => 'success',
            'billetviewlst' => $code,
            // 'div' => '#ajoutdiv > #qno1',
            // 'div' => '#ajoutdiv > #qno'.$idq,
        ]);
    }

    public function remover(Request $rq)
    {
        $idq = $rq->input('id');

        $tmp = session('newForm');

        $page = 0;

        foreach ($tmp->question as &$key) {
            if (intval($key->id) == intval($idq)) {
                $ind = array_search($key, $tmp->question);
                if ($ind !== false) {
                    $page = $key->page;
                    unset($tmp->question[$ind]);
                } else {
                    # code...
                }
                // 
                break;
            } 
            // elseif (intval($key->id) > intval($idq)) {
            //     $key->id--;
            // }
        }

        session()->put('newForm', $tmp);

        $code = "";
        foreach ($tmp->question as $q) {
            if($page == $q->page){
                $code = $code.$q->codec;
            }
        }
        

        
        return response()->json([
            // 'taille' => $tmp->question[0]->code,
            'taille' => count(session('newForm')->question),
            'statu' => 'success',
            'billetviewlst' => $code,
            // 'div' => '#ajoutdiv > #qno1',
        ]);
    }

    // 
    // 


    public function ajoutpager(Request $rq)
    {
        $idp = $rq->input('id');
        // $title = $rq->input('title');
        $pos = $rq->input('pos');
        // $codep = $rq->input('codep');

        $tmp = session('newForm');
        // $pos = count($tmp->pages);

        $ok = false;
        foreach ($tmp->question as &$q) {
            if (intval($q->page) == (intval($idp) - 1) ) {
                $ok = true;
                break;
            } 
        }
        // foreach ($tmp->pages as &$key) {
        //     // if(empty($key->titre)){
        //     //     $ok = false;
        //     //     break;
        //     // }
        //     foreach ($tmp->question as &$q) {
        //         if (intval($q->page) == $key->id) {
        //             $ok = true;
        //             break;
        //         } else {
        //             $ok = false;
        //         }
        //     }
        // }

        if ($ok == false) {
            return response()->json([
                // 'taille' => $tmp->question[0]->code,
                'taille' => 'false',
                'statu' => 'success',
                // 'billetviewlst' => $code,
                // 'div' => '#ajoutdiv > #qno1',
            ]);
        } else {
            // session('newForm')->page = intval($idp);
        
            $newpage = new Pages(intval($idp), "", "");
            // $newpage = new Pages(intval($idp), "", $codep);
            // $newpage = new Pages($idp, $title);
    
            if (intval($pos) == count($tmp->pages) || count($tmp->pages) == 0) {
                array_push($tmp->pages , $newpage);
            } else {
                array_splice($tmp->pages, intval($pos), 0, [$newpage]);
            }
            
            session()->put('newForm', $tmp);
    
            // $code = "";
            // foreach ($tmp->pages as $q) {
            //    
            //         $code = $code.$q->codec;
            //     
            // }
    
            
            return response()->json([
                // 'taille' => $tmp->question[0]->code,
                'taille' => 'true',
                'statu' => 'success',
                // 'billetviewlst' => $code,
                // 'div' => '#ajoutdiv > #qno1',
            ]);
        }



       
    }

    public function openpager(Request $rq)
    {
        $idp = $rq->input('id');

        if (session('newForm')->page == $idp) {
            # code...
            return response()->json([
                // 'taille' => $tmp->question[0]->code,
                'taille' => 'false',
                'statu' => 'success',
                // 'billetviewlst' => $lodgindatalstx,
                // 'div' => '#ajoutdiv > #qno1',
            ]);
        } else {
            # code...
            session('newForm')->page = intval($idp);
            // 
            $code = "";
            foreach (session('newForm')->question as $q) {
                
                if ($q->page == intval($idp)) {
                    # code...
                    $code = $code.$q->codec;
                }
            }
            //
            $titre = ""; 
            foreach (session('newForm')->pages as $p) {
                
                if (intval($p->id) == intval($idp)) {
                    $titre = $p->titre;
                }
            }

            
            return response()->json([
                'titre' => $titre,
                'taille' => 'true',
                'statu' => 'success',
                'billetviewlst' => $code,
                'div' => count(session('newForm')->pages),
            ]);
        }

        
    }

    public function removepager(Request $rq)
    {
        $idp = $rq->input('id');
        // 
        // session('newForm')->page = intval($idp) - 1;
        // 
        $tmp = session('newForm');

        foreach ($tmp->question as &$key) {
            if (intval($key->page) == intval($idp)) {
                $ind = array_search($key, $tmp->question);
                if ($ind !== false) {
                    unset($tmp->question[$ind]);
                } else {
                    # code...
                }
                // 
                // break;
            } 
            // elseif (intval($key->page) > intval($idp)) {
            //     $key->page = intval($key->page) - 1;
            // }
        }
        // supp page
        foreach ($tmp->pages as &$key) {
            if (intval($key->id) == intval($idp)) {
                $ind = array_search($key, $tmp->pages);
                if ($ind !== false) {
                    unset($tmp->pages[$ind]);
                } else {
                    # code...
                }
                // 
                break;
            } 
            // elseif (intval($key->id) > intval($idp)) {
            //     $key->id = intval($key->id) - 1;
            // }
        }

        session()->put('newForm', $tmp);

        
        return response()->json([
            // 'taille' => $tmp->question[0]->code,
            'taille' => count(session('newForm')->question),
            'statu' => 'success',
            // 'billetviewlst' => $lodgindatalstx,
            // 'div' => '#ajoutdiv > #qno1',
        ]);
    }

    public function settitler(Request $rq)
    {
        $title = $rq->input('title');
        // 
        // 
        $tmp = session('newForm');

        // 
        // for ($i=0; $i < count($tmp->pages); $i++) { 
        //     if ($tmp->page == $tmp->pages[$i]->id) {
        //         # code...
        //     }
        // }
        foreach ($tmp->pages as &$key) {
            if (intval($key->id) == $tmp->page) {
                
                $key->titre = $title ;
                
                break;
            } 
            // elseif (intval($key->id) > intval($idp)) {
            //     $key->id = intval($key->id) - 1;
            // }
        }

        session()->put('newForm', $tmp);

        
        return response()->json([
            // 'taille' => $tmp->question[0]->code,
            'taille' => count(session('newForm')->question),
            'statu' => 'success',
            // 'billetviewlst' => $lodgindatalstx,
            // 'div' => '#ajoutdiv > #qno1',
        ]);
    }

    public function pageuper(Request $rq)
    {
        $idp = $rq->input('id');
        // $title = $rq->input('title');
        $pos = $rq->input('pos');

        $tmp = session('newForm');

        $newpage = $tmp->pages[intval($pos) + 1];
        

        unset($tmp->pages[intval($pos) + 1]);

      
            // if (intval($pos) == count($tmp->pages) || count($tmp->pages) == 0) {
            //     array_push($tmp->pages , $newpage);
            // } else {
                array_splice($tmp->pages, intval($pos), 0, [$newpage]);
            // }
            
            session()->put('newForm', $tmp);
            
            return response()->json([
                // 'taille' => $tmp->question[0]->code,
                'taille' => 'true',
                'statu' => 'success',
                // 'billetviewlst' => $code,
                // 'div' => '#ajoutdiv > #qno1',
            ]);
         
    }

    public function pagedowner(Request $rq)
    {
        $idp = $rq->input('id');
        // $title = $rq->input('title');
        $pos = $rq->input('pos');

        $tmp = session('newForm');

        $newpage = $tmp->pages[intval($pos) - 1];
        

        unset($tmp->pages[intval($pos) - 1]);

      
            if (intval($pos) == count($tmp->pages) || count($tmp->pages) == 0) {
                array_push($tmp->pages , $newpage);
            } else {
                array_splice($tmp->pages, intval($pos), 0, [$newpage]);
            }
            
            session()->put('newForm', $tmp);
            
            return response()->json([
                // 'taille' => $tmp->question[0]->code,
                'taille' => 'true',
                'statu' => 'success',
                // 'billetviewlst' => $code,
                // 'div' => '#ajoutdiv > #qno1',
            ]);
         
    }

    public function finisher(Request $rq)
    {
        $idpl = $rq->input('idl');
        $idpf = $rq->input('idf'); 
        $codepages = $rq->input('codepages'); 

        session('newForm')->pages[0]->codec = $codepages;

        $tmp = session('newForm');

        $okf = false;
        
        foreach ($tmp->question as &$q) {
            if (intval($q->page) == (intval($idpf)) ) {
                $okf = true;
                break;
            } 
        }

        $okl = false;

        foreach ($tmp->question as &$q) {
            if (intval($q->page) == (intval($idpl)) ) {
                $okl = true;
                break;
            } 
        }

        if (count($tmp->pages) == 1 && $okf == false) {
            // 1 seule page et vide
            return response()->json([
                'taille' => '0',
                'statu' => 'success',
            ]);
        } elseif($okl == false) {
            // Dernière page ajoutée vide
            return response()->json([
                'taille' => '1',
                'statu' => 'success',
            ]);
        
        } else {
            // peut finir
            //

            if (session('newForm')->id != '') {
                // existe déjà

                $idu = session('idUser');
                $idf = session('newForm')->id;
                $dbname = "form_".$idu."_".$idf;
                // 
                // quand le formulaire était déjà terminé et quil modifie en veut enregistrer ...?
                if (session('etat') == '1') {
                    # code...
                } else {
                    // créer la base du form
                    $res = $this->createformDB();
                    // 
                    // 
                    $res2 = $this->updateJSON(1, $dbname);
                    // 
                    $this->updateDB(1);
                    // 
                    return response()->json([
                        // 'taille' => $res2,
                        'taille' => '2',
                        'statu' => 'success',
                    ]);
                }
                
                # code...
            } else {
                session('newForm')->id = (int)(microtime(true) *1000); 
                // 
                // 
                // créer la base du form
                $res = $this->createformDB();
                // 
                // 
                // créer le json, le php et limage bon
                // 
                $res2 = $this->createformJSON(1, $res[0]);
                // 
                // prendre user id, créer id form et enregistrer table form bon
                $this->saveDB(1, $res2[0], $res2[1]);
                
                //  $this->dashboard();
                return response()->json([
                    // 'taille' => $res2,
                    'taille' => '2',
                    'statu' => 'success',
                ]);
            }

            
        }   
    }

    public function finishfermerer(Request $rq)
    {
        
            // peut finir
            //
            $codepages = $rq->input('codepages'); 

            session('newForm')->pages[0]->codec = $codepages;

            $idu = session('idUser');
            $idf = session('newForm')->id;
            $dbname = "form_".$idu."_".$idf;

            if (session('newForm')->id != '') {
                // existe déja 

                // quand le formulaire était déjà terminé et quil modifie en veut enregistrerfemer ...?
                if (session('etat') == '1') {
                    # code...
                } else {
                    $res2 = $this->updateJSON(0, $dbname);

                    // 
                    $this->updateDB(0);
                    // 
                    return response()->json([
                        // 'taille' => $res2,
                        'taille' => '2',
                        'statu' => 'success',
                    ]);
                }

                
                
                
            } else {
                // nexiste pas encore
                
                session('newForm')->id = (int)(microtime(true) *1000); 
                
                // 
                // 
                // créer la base du form
                // $res = $this->createformDB();
                // 
                // 
                // créer le json, le php et limage bon
                // 
                $res2 = $this->createformJSON(0, $dbname);
                // 
                // prendre user id, créer id form et enregistrer table form bon
                $this->saveDB(0, $res2[0], $res2[1]);
                
                //  $this->dashboard();
                return response()->json([
                    // 'taille' => $res2,
                    'taille' => '2',
                    'statu' => 'success',
                ]);
            }

            
        // }  
    }

    public function saveDB($done, $url, $img)
    {
        $idu = session('idUser');
        $idf = session('newForm')->id;
        $tmp = session('newForm')->titre;

        // $rq = DB::insert("INSERT INTO `forms`(`iduser`, `idform`, `titre`, `url`, `etat`, `img`) VALUES (?, ?, ?, ?, ?,  ?)", ["$idu", "234", "$tmp", "$url", "$done", "$img"]);     
        $rq = DB::insert("INSERT INTO surveygenesislogin.forms (`iduser`, `idform`, `titre`, `url`, `etat`, `img`) VALUES (?, ?, ?, ?, ?,  ?)", ["$idu", "$idf", "$tmp", "$url", "$done", "$img"]);     
    }

    public function updateDB($etat)
    {
        $idu = session('idUser');
        $idf = session('newForm')->id;
        $tmp = session('newForm')->titre;
   
        $rq = DB::insert("UPDATE surveygenesislogin.forms SET `etat` = ?, `titre` = ?  WHERE `iduser` = ? AND `idform` = ?", ["$etat", "$tmp", "$idu", "$idf"]);     
    }

    public function createformDB()
    {
        $idu = session('idUser');
        $idf = session('newForm')->id;
        $dbname = "form_".$idu."_".$idf;

        $tmp = session('newForm');

        
        // $rq = "CREATE DATABASE $dbname";  
        $rq = DB::insert(" CREATE DATABASE $dbname ", []);  
        $rq2txt = "";

        foreach ($tmp->pages as $p) {
            $cols = "";
            $ct = 1;
            for ($i=0; $i < count($tmp->question) ; $i++) { 
                $q = $tmp->question[$i];
                if (intval($q->page) == intval($p->id) && intval($q->type) != 0) {
                    $cols .= "q".($ct)." text DEFAULT NULL,";
                    $ct++;
                } 
            }

            if (!empty($cols)) {
                $tname = $dbname.".p".$p->id;

                $rq2txt .= "USE $dbname;DROP TABLE IF EXISTS $tname;CREATE TABLE IF NOT EXISTS $tname (id int NOT NULL AUTO_INCREMENT,$cols PRIMARY KEY (id))";
    
                $rq2txt1 = "USE $dbname;";
                $rq2txt2 = "DROP TABLE IF EXISTS $tname;";
                $rq2txt3 = "CREATE TABLE IF NOT EXISTS $tname (id int NOT NULL AUTO_INCREMENT,$cols PRIMARY KEY (id))";
    
    
                
    
               $rq2 = DB::insert($rq2txt1, []);
               $rq2 = DB::insert($rq2txt2, []);
               $rq2 = DB::insert($rq2txt3, []);
            }

            
            
        }

        return [$dbname, count($tmp->pages), $rq, $rq2txt];
        

        
    }

    public function createformJSON($done, $dbname)
    {
        $idu = session('idUser');
        $idf = session('newForm')->id;
        $path = resource_path()."\\data\\user".$idu."\\".$done."_form_".$idf;

        // $content = $this->apercu($dbname)->render();
        $content = $this->apercu_p($dbname)->render();

        $dest1 = $path."\\file.json";
        $dest2 = $path."\\file.blade.php";
        $dest22 = $path."\\file.html";
        $dest3 = $path."\\file.png";

        if (!file_exists($dest1)) {
            mkdir($path, 0777, true);
            file_put_contents($dest1, json_encode(session('newForm')));
            file_put_contents($dest2, $content);
            file_put_contents($dest22, $content);
        }   

        $c1 = resource_path()."\\wkhtmltox\\bin\\wkhtmltoimage ";
        $c2 = $dest22;
        $c3 = $dest3;

        $com = $c1." --height 500 --width 350 ".$c2." ".$c3;

        exec($com, $output, $code );

        return [$dest2, $dest3];

        
    }

    public function updateJSON($fin, $dbname)
    {
        $etat = session('etat');
        $idu = session('idUser');
        $idf = session('newForm')->id;
        $path = resource_path()."\\data\\user".$idu."\\".$etat."_form_".$idf;

        // $content = $this->apercu($dbname)->render();
        $content = $this->apercu_p($dbname)->render();

        $dest1 = $path."\\file.json";
        $dest2 = $path."\\file.blade.php";
        $dest22 = $path."\\file.html";
        $dest3 = $path."\\file.png";

        // if (!file_exists($dest1)) {
            // mkdir($path, 0777, true);
            file_put_contents($dest1, json_encode(session('newForm')));
            file_put_contents($dest2, $content);
            file_put_contents($dest22, $content);
        // }   

        $c1 = resource_path()."\\wkhtmltox\\bin\\wkhtmltoimage ";
        $c2 = $dest22;
        $c3 = $dest3;

        $com = $c1." --height 500 --width 350 ".$c2." ".$c3;

        exec($com, $output, $code );

        if ($fin == 1) {
            $newpath = resource_path()."\\data\\user".$idu."\\1_form_".$idf;
            rename($path, $newpath);
        }

        return [$dest2, $dest3];

        
    }

    public function saveanser(Request $rq)
    {
        $dbname = $rq->input('dbname');
        $opt = $rq->input('res');

        $pages = [];

        foreach (explode('-&&&&-', $opt) as $item) {
            $pages[] = $item;
        }


        for ($i=0; $i < count($pages); $i++) { 
            $p = $pages[$i];
            
            $cols = "";
            $values = "";
            
            for ($j=0; $j < count(explode('-&&val&&-', $p)); $j++) { 
                $q = explode('-&&val&&-', $p)[$j];

                $cols .= " q".($j+1);

                $values .= " \"".$q."\"";

                if ($j != count(explode('-&&val&&-', $p))-1) {
                    $cols .= ",";
                    $values .= ",";
                }
            }

            $rq = DB::insert("INSERT INTO $dbname.p".($i+1)." ($cols)
             VALUES ($values)", []); 
        }

        
        return response()->json([
            // 'taille' => "INSERT INTO $dbname.p".($i+1)." ($cols) VALUES ($values)",
            'taille' => "$rq",
            'statu' => 'success',
        ]);
    }

    // 
    // 

    public function avertz()
    {
        return view('avertissement');
    }

    public function newpwd()
    {
        return view('newpassword');
    }

    public function inscrire(Request $request)
    {
        $request->validate([
            'pseudo' => 'required' | 'string',
            'email' => 'required' | 'email',
            'password' => ['required', 'min:8'],
        ]);

        $pseudo = $request->input('pseudo');
        $email = $request->input('email');
        $password = $request->input('password');


        $requete = DB::insert('INSERT INTO Utilisateurs(pseudo,email,motdepasse) VALUES(?,?,?)', ["$pseudo", "$email", "$password"]);
        if ($requete) {
            //return redirect('/inscription')->with('status', 'Vous avez été bel et bien enregister');
            return view('assistant_creation');
        } else {
            return back()->withErrors([
                'email' => 'Email invalide ou mot de passe invalid',
                'password' => 'Votre mot de passe doit contenir au moins 8 caractères'
            ]);
        }
    }

    public function dashboard()
    {
        $idu = 11; // $request->input('idu');

        session()->put('idUser', $idu);

        

        $rq = DB::select('select * from surveygenesislogin.forms where iduser = ? order by date desc', ["$idu"]);

        $rq1 = [];
        $rq0 = [];

        foreach ($rq as $item) {
            if ($item->etat == '1') {
                $rq1[] = $item;
            } elseif($item->etat == '0') {
                $rq0[] = $item;
            } else {}
        }
        
        return view('tableau_de_bord', [
            'rq1'=> $rq1,
            'rq2'=> $rq0,
        ]);
    }

    public function creation(Request $request, $mode)
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

        
    }

    public function updateform(Request $request, $folder, $etat)
    {
        session()->put('etat', $etat);
        // 
        $idu = session('idUser');
        $path = resource_path()."\\data\\user".$idu."\\".$folder;

    // stripcslashes($path,"");
    // str_replace()

        $dest1 = $path."\\file.json";

        $datas = json_decode(file_get_contents($dest1));

        session()->put('newForm', $datas);
        // session()->put('newForm', new Formulaire($datas->id, $datas->titre, $datas->page, [], [new Pages(1, "", "")]));

        return view('new_formulaire', [
            'mode'=> 1,
            'title'=> $datas->titre, 
        ]);
    }

    public function deleteformer(Request $rq)
    {
        $folder = $rq->input('folder');
        $formid = $rq->input('formid');
        // 
        $idu = session('idUser');
        $path = resource_path()."\\data\\user".$idu."\\".$folder;
        // 

        if (is_dir($path)) {
             // delete all files
            foreach (array_diff(scandir($path), array('.', '..')) as $key) {
                if (is_file("$path/$key")) {
                    unlink("$path/$key");
                }
            }
            // delete dir
            
                rmdir($path);
          
            // 
        }
       

        // delete form db
        $dbname = "form_".$idu."_".$formid;
        if ( str_starts_with($folder, '1_form')) {
            $rq1 = DB::insert("DROP DATABASE $dbname;", []);
        }
        

        // updatedb
        $rq2 = DB::insert("UPDATE surveygenesislogin.forms SET `etat` = ? WHERE `iduser` = ? AND `idform` = ?", ["3",  "$idu", "$formid"]); 

        return response()->json([
            'statu' => 'success',
            // 'res' =>  str_starts_with($folder, '1_form'),
            'res' =>  $rq1 && $rq2,
        ]);
    }

    public function result(Request $request, $folder)
    {
        
        $idu = session('idUser');
        $path = resource_path()."\\data\\user".$idu."\\".$folder;

    // stripcslashes($path,"");
    // str_replace()

        $dest1 = $path."\\file.json";

        $datas = json_decode(file_get_contents($dest1));

        $res = [];

        $tablename = "form_".$idu."_".$datas->id;

        for ($i=1; $i <= count($datas->pages); $i++) { 
            $res[] = DB::select("select * from $tablename.p$i ", []);
        }

       

        return view('resultats_sondage', [
            'datas'=> $datas,
            'url'=> $path."\\file.blade.php",
            'res' => $res
        ]);
    }

}
