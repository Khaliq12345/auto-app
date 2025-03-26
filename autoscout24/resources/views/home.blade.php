@extends('layout')

@section('header')
<!--  header-section start  -->
<header class="header-section">
    <div class="header-top">
      <div class="container">
        <div class="row align-items-center">
          <div class="col-lg-3">
            <ul class="social-links">
              <li><a href="#0"><i class="fa fa-facebook"></i></a></li>
              <li><a href="#0"><i class="fa fa-twitter"></i></a></li>
              <li><a href="#0"><i class="fa fa-linkedin"></i></a></li>
              <li><a href="#0"><i class="fa fa-google-plus"></i></a></li>
            </ul>
          </div>
          <div class="col-lg-6">
            {{-- <ul class="header-info d-flex justify-content-center">
              <li>
                <i class="fa fa-map-marker"></i>
                <p>Medino, NY 10012, USA Mitro Road</p>
              </li>
              <li>
                <i class="fa fa-clock-o"></i>
                <p>Sat - Fri Day 08:00 am - 5.00 pm Sunday Holyday</p>
              </li>
            </ul>  --}}
          </div>
          <div class="col-lg-3">
            <div class="header-action d-flex align-items-center justify-content-end">
              {{-- <div class="lag-select-area">
                <select>
                  <option>ENG</option>
                  <option>RUS</option>
                  <option>BAN</option>
                </select>
              </div> --}}
              <div class="login-reg">
                <a href="#" id="logout-link">Logout</a>

<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>
                <form class="login-form" method="POST" action="{{ route('login_post') }}">
                    @csrf
                    <a href="#0"><i class="fa fa-sign-out"></i> Sign Out</a>
                
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </header>
  <!--  header-section end  -->

@endsection


@section('content')

<section class="inner-page-banner bg_img overlay-3 p-0 m-0" data-background="assets/images/inner-page-bg.jpg">
    <div class="container">
      <div class="row p-5">
        <div class="col-md-12 pt-md-5">
          <h2 class="page-title text-center">Start By Uploading your File!</h2>

          @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

    
       

          <div class="row">
            <div class="col-lg-12">
              <div class="car-search-filter-area ">
                <div class="car-search-filter-form-area justify-content-center" >
                  <form class="car-search-filter-form" action="{{ route('import.excel') }}" method="POST" enctype="multipart/form-data" id="FormExcel">
                    @csrf
                    <div class="row justify-content-between align-items-center">
                      <div class="col-lg-4 col-md-5 col-sm-6">
                        <div class="cart-sort-field">
                          <span class="caption text-white">Allowed Extentions : .xlsx - .xls </span>
                          {{-- <select>
                            <option>Pajero Range</option>
                            <option>Toyota Axio</option>
                            <option>Lancer</option>
                          </select> --}}
                        </div>
                      </div>
                      <div class="col-lg-8 col-md-7 col-sm-6 d-flex text-white">
                      
                            
                        <input type="file" name="fileInput" id="fileInput" accept=".xlsx, .xls" placeholder="Select your file........">
                        <button type="submit" class="d-inline"><i class="fa fa-upload"></i>Import</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>




  <!-- car-search-section start -->


  <section class="car-search-section pb-120">
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <div class="car-search-filter-area">
            <div class="car-search-filter-form-area">
              <form class="car-search-filter-form">
                <div class="row justify-content-between">
                  {{-- <div class="col-lg-4 col-md-5 col-sm-6">
                    <div class="cart-sort-field">
                      <span class="caption">Sort by : </span>
                      <select>
                        <option>Pajero Range</option>
                        <option>Toyota Axio</option>
                        <option>Lancer</option>
                      </select>
                    </div>
                  </div> --}}
                  <div class="col-10 d-flex">
                    <input type="text" name="car_search" id="car_search" placeholder="Search what you want........">
                    <button class="search-submit-btn">Search</button>
                  </div>
                </div>
              </form>
            </div>
            <div class="view-style-toggle-area">
              <button class="view-btn list-btn"><i class="fa fa-bars"></i></button>
              <button class="view-btn grid-btn active"><i class="fa fa-th-large"></i></button>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-70 justify-content-center">

        @if (!isset($datas))
         
        <div class="col-lg-11">
          <div class="car-search-result-area grid--view row mb-none-30">
            <div class="col-md-6 col-12">
              <div class="car-item">
                <div class="thumb bg_img" data-background="assets/images/cars/4.jpg"></div>
                <div class="car-item-body">
                  <div class="content">
                    <h4 class="title">pajero rang</h4>
                    <span class="price">start form $20 per day</span>
                    <p>Aliquam sollicitudin dolores tristiquvel ornare, vitae aenean.</p>
                    <a href="#0" class="cmn-btn">View More</a>
                  </div>
                  <div class="car-item-meta">
                    <ul class="details-list">
                      <li><i class="fa fa-car"></i>model 2014ib</li>
                      <li><i class="fa fa-tachometer"></i>32000 KM</li>
                      <li><i class="fa fa-sliders"></i>auto</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div><!-- car-item end -->
            <div class="col-md-6 col-12">
              <div class="car-item">
                <div class="thumb bg_img" data-background="assets/images/cars/5.jpg"></div>
                <div class="car-item-body">
                  <div class="content">
                    <h4 class="title">mirage range</h4>
                    <span class="price">start form $20 per day</span>
                    <p>Aliquam sollicitudin dolores tristiquvel ornare, vitae aenean.</p>
                    <a href="#0" class="cmn-btn">View More</a>
                  </div>
                  <div class="car-item-meta">
                    <ul class="details-list">
                      <li><i class="fa fa-car"></i>model 2014ib</li>
                      <li><i class="fa fa-tachometer"></i>32000 KM</li>
                      <li><i class="fa fa-sliders"></i>auto</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div><!-- car-item end -->
            <div class="col-md-6 col-12">
              <div class="car-item">
                <div class="thumb bg_img" data-background="assets/images/cars/6.jpg"></div>
                <div class="car-item-body">
                  <div class="content">
                    <h4 class="title">Volkswagen</h4>
                    <span class="price">start form $20 per day</span>
                    <p>Aliquam sollicitudin dolores tristiquvel ornare, vitae aenean.</p>
                    <a href="#0" class="cmn-btn">View More</a>
                  </div>
                  <div class="car-item-meta">
                    <ul class="details-list">
                      <li><i class="fa fa-car"></i>model 2014ib</li>
                      <li><i class="fa fa-tachometer"></i>32000 KM</li>
                      <li><i class="fa fa-sliders"></i>auto</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div><!-- car-item end -->
            <div class="col-md-6 col-12">
              <div class="car-item">
                <div class="thumb bg_img" data-background="assets/images/cars/7.jpg"></div>
                <div class="car-item-body">
                  <div class="content">
                    <h4 class="title">Rools royce</h4>
                    <span class="price">start form $20 per day</span>
                    <p>Aliquam sollicitudin dolores tristiquvel ornare, vitae aenean.</p>
                    <a href="#0" class="cmn-btn">View More</a>
                  </div>
                  <div class="car-item-meta">
                    <ul class="details-list">
                      <li><i class="fa fa-car"></i>model 2014ib</li>
                      <li><i class="fa fa-tachometer"></i>32000 KM</li>
                      <li><i class="fa fa-sliders"></i>auto</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div><!-- car-item end -->
            <div class="col-md-6 col-12">
              <div class="car-item">
                <div class="thumb bg_img" data-background="assets/images/cars/8.jpg"></div>
                <div class="car-item-body">
                  <div class="content">
                    <h4 class="title"> Toyota</h4>
                    <span class="price">start form $20 per day</span>
                    <p>Aliquam sollicitudin dolores tristiquvel ornare, vitae aenean.</p>
                    <a href="#0" class="cmn-btn">View More</a>
                  </div>
                  <div class="car-item-meta">
                    <ul class="details-list">
                      <li><i class="fa fa-car"></i>model 2014ib</li>
                      <li><i class="fa fa-tachometer"></i>32000 KM</li>
                      <li><i class="fa fa-sliders"></i>auto</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div><!-- car-item end -->
            <div class="col-md-6 col-12">
              <div class="car-item">
                <div class="thumb bg_img" data-background="assets/images/cars/9.jpg"></div>
                <div class="car-item-body">
                  <div class="content">
                    <h4 class="title"> Porsche</h4>
                    <span class="price">start form $20 per day</span>
                    <p>Aliquam sollicitudin dolores tristiquvel ornare, vitae aenean.</p>
                    <a href="#0" class="cmn-btn">View More</a>
                  </div>
                  <div class="car-item-meta">
                    <ul class="details-list">
                      <li><i class="fa fa-car"></i>model 2014ib</li>
                      <li><i class="fa fa-tachometer"></i>32000 KM</li>
                      <li><i class="fa fa-sliders"></i>auto</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div><!-- car-item end -->
          </div>
          {{-- {{ $cars->links() }}  --}}
          <nav class="d-pagination" aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
              <li class="page-item active"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">4</a></li>
              <li class="page-item"><a class="page-link" href="#">5</a></li>
            </ul>
          </nav>
        </div>
        {{-- <div class="col-lg-4">
          <aside class="sidebar">
            <div class="widget widget-reservation">
              <h4 class="widget-title">reservation</h4>
              <div class="widget-body">
                <form class="car-search-form">
                  <div class="row">
                    <div class="col-lg-12 form-group">
                      <select>
                        <option value="1" selected>Choose Your Car Type</option>
                        <option value="2">Another option</option>
                        <option value="4">Potato</option>
                      </select>
                    </div>
                    <div class="form-group col-md-12">
                      <i class="fa fa-map-marker"></i>
                      <input class="form-control has-icon" type="text" placeholder="Pickup Location">
                    </div>
                    <div class="form-group col-md-12">
                      <i class="fa fa-map-marker"></i>
                      <input class="form-control has-icon" type="text" placeholder="Drop Off Location">
                    </div>
                    <div class="form-group col-md-12">
                      <i class="fa fa-calendar"></i>
                      <input type='text' class='form-control has-icon datepicker-here' data-language='en' placeholder="Pickup Date">
                    </div>
                    <div class="form-group col-md-12">
                      <i class="fa fa-clock-o"></i>
                      <input type="text" name="timepicker" class="form-control has-icon timepicker" placeholder="Pickup Time">
                    </div>
                    <div class="form-group col-md-12">
                      <i class="fa fa-calendar"></i>
                      <input type='text' class='form-control has-icon datepicker-here' data-language='en' placeholder="Drop Off Date">
                    </div>
                    <div class="form-group col-md-12">
                      <i class="fa fa-clock-o"></i>
                      <input type="text" name="timepicker" class="form-control has-icon timepicker" placeholder="Drop Off Time">
                    </div>
                  </div>
                  <button type="submit" class="cmn-btn">Reservation</button>
                </form>
              </div>
            </div><!-- widget end -->
            <div class="widget widget-price-filter">
              <h4 class="widget-title">price filter</h4>
              <div class="widget-body">
                <div id="slider-range"></div>
                <div class="filter-price-result">
                  <input type="text" id="amount" readonly><span>(Per Day)</span>
                </div>
              </div>
            </div><!-- widget end -->
            <div class="widget widget-testimonial">
              <h4 class="widget-title">testimonial</h4>
              <div class="widget-body">
                <div class="testimonial-slider owl-carousel">
                  <div class="testimonial-item">
                    <div class="testimonial-item--header">
                      <div class="thumb"><img src="assets/images/testimonial/1.jpg" alt="image"></div>
                      <div class="content">
                        <h6 class="name">martin hook</h6>
                        <span class="designation">business man</span>
                      </div>
                    </div>
                    <div class="testimonial-item--body">
                      <p>Tristique consequat, lorem sed vivamus donec eget, nulla pharetra lacinia wisi diamaliquam velit nec.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div><!-- widget end -->
          </aside>
        </div> --}}

           
        @else

        <div class="m-auto py-5 px-2 text-center">
            <i class="fa fa-info-circle " style="font-size: 100px"></i>
            <h2 class="section-title pt-5">No Data Found !</h2>
           
        </div>

      
        @endif

      </div>
    </div>
  </section>
  <!-- car-search-section end -->


  <!-- features-section start -->
  <section class="features-section pb-120">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="section-header text-center">
            <h2 class="section-title"></h2>
            <p> </p>
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-lg-4">
            <div class="contact-item text-center">
              <div class="icon"><i class="fa fa-home"></i></div>
              <div class="content">
                <h4 class="title">office address</h4>
                <p>4920 Northwest 21st Avenue<br/> Medford, MN 55049 <br/>kigkong,USA</p>
              </div>
            </div>
          </div><!-- contact-item end -->
          <div class="col-lg-4">
            <div class="contact-item text-center">
              <div class="icon"><i class="fa fa-phone"></i></div>
              <div class="content">
                <h4 class="title">Phone Number</h4>
                <p>+888-555-333-221 , 096545245<br/> 878454545 , 45784521 <br/>02-3254789645</p>
              </div>
            </div>
          </div><!-- contact-item end -->
          <div class="col-lg-4">
            <div class="contact-item text-center">
              <div class="icon"><i class="fa fa-envelope-o"></i></div>
              <div class="content">
                <h4 class="title">Email Address</h4>
                <p>www.renten450info@gmil.com<br/>www.rentensupport.com<br/>facebook/renten.net</p>
              </div>
            </div>
          </div><!-- contact-item end -->
        </div>
       
      </div>
    </div>
  </section>
  <!-- features-section end -->
  


  <script>
    document.getElementById('logout-link').addEventListener('click', function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du lien (navigation)
        document.getElementById('logout-form').submit(); // Soumet le formulaire caché
    });
</script>

 



    

    <script>
    
    function removepage(id) {
            // alert('Removing..page no : '+id);
            
            let supp = confirm("Voulez-vous vraiment supprimer cette page et tout son contenu ? Cette action est irréversible !")

            if (supp == true) {
                $.ajax({
                type: "GET",
                url: "<?= URL::to('/removepage') ?>",
                dataType: "json",
                data : {
                    'id' : id,
                },
                success: function(data) {
                    // alert('ajout');
                    if (data.statu == 'success') {
                        // 
                        
                        document.querySelectorAll('.formpages > div ').forEach(el => {
                           
                            if (el.getAttribute('id') == id) {
                                el.remove();
                                // break;
                            }
                            // if(parseInt(el.getAttribute('id')) > parseInt(id)) {
                            //     // alert('ok');
                            //     let idact = parseInt(el.getAttribute('id')) - 1;
                            //     // alert(idact);
                            //     el.setAttribute('id', idact);
                            //     el.innerHTML = `
                            //         <p class=" px-4 btn text-center bg-success-subtle border-black" id="`+idact+`" onclick="openpage('`+idact+`');">Page `+idact+` </p> 
                            //         <span class=' btn text-danger fw-bold ' onclick="removepage('`+idact+`')"> X </span>
                            //     `
                            // }
                        });
                        // 
                        openpage(parseInt(id) - 1);
                        // fermer(modalid);
                        // $('#'+modalid).modal('hide'); //show
                    }
                },
                error:function (xhr,status,error){
                    alert(error);
                }
            });
            } else {
                
            }

           
        }

        // Optionnel : Afficher le nom du fichier sélectionné
    // const fileInput = document.getElementById('fileInput');
    // const form = document.getElementById('FormExcel');
    // fileInput.addEventListener('change', function() {
    //   if (fileInput.files.length > 0) {
    //     console.log('Fichier sélectionné :', fileInput.files[0].name);
    //     // Vous pouvez mettre à jour l'interface utilisateur ici pour afficher le nom du fichier
    //     form.submit();
    //   }
    // });

  </script>





@endsection