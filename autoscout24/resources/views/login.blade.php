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
            </ul> --}}
          </div>
          <div class="col-lg-3">
            <div class="header-action d-flex align-items-center justify-content-end">
              
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </header>
  <!--  header-section end  -->

@endsection


@section('content')



  <!-- login-section start -->
  <section class="login-section pb-5 bg_img overlay-3" data-background="assets/images/inner-page-bg.jpg">
  
    <div class="container pt-3 pt-md-5 ">
      <div class="row">
        <div class="col-md-12">
          <h2 class="page-title text-center text-white pb-3 pb-md-5 ">Welcome !</h2>
        </div>
        <div class="col-lg-8 m-auto">
          <div class="login-block text-center">
            <div class="login-block-inner">
              <h3 class="title">login your account </h3>

              @if ($errors->any())
        <div class="pb-3">
          <div class="alert alert-danger">
            <ul>
              @foreach ($errors->all() as $error)
                  <li class="text-danger fw-bold">{{ $error }}</li>
              @endforeach
          </ul>
        </div>
           
        </div>
    @endif
              <form class="login-form" method="POST" action="{{ route('login_post') }}">
               
                @csrf
                <div class="frm-group">
                  <input type="email" name="email" id="email" placeholder="Your Email">
                </div>
                <div class="frm-group">
                  <input type="password" name="password" id="password" placeholder="Your Password">
                </div>
                <div class="frm-group">
                  <input type="submit" value="login">
                </div>

                {{-- <div class="frm-group text-center">
                  <span class="or">or</span>
                </div>

                <div class="frm-group text-center">
                  <a href="#0" class="facebook">facebook</a>
                  <a href="#0" class="google">google plus</a>
                </div> --}}


              </form>
              {{-- <p class="text-center"><a href="#0">Haven't your any account in here?</a><a href="#0">Forget password?</a></p> --}}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- login-section end -->

  <!-- features-section start -->
  <section class="features-section pt-120 pb-120">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-6">
          <div class="section-header text-center">
            <h2 class="section-title">our awsome features</h2>
            {{-- <p> Augue urna molestie mi adipiscing vulputate pede sedmassa  Praesquam massa, sodales velit turpis in tellu.</p> --}}
          </div>
        </div>
      </div>
      <div class="row mb-none-30">
        <div class="col-lg-4 col-sm-6">
          <div class="icon-item text-center">
            <div class="icon"><i class="fa fa-user"></i></div>
            <div class="content">
              <h4 class="title">User Friendly </h4>
              {{-- <p>Tristique ac felis ultr egestelend sed metus eloo dui, et vestulum rutrum nisl tempus </p> --}}
            </div>
          </div>
        </div><!-- icon-item end -->
        <div class="col-lg-4 col-sm-6">
          <div class="icon-item text-center">
            <div class="icon"><i class="fa fa-rocket"></i></div>
            <div class="content">
              <h4 class="title">Fast Analysis</h4>
              {{-- <p>Tristique ac felis ultr egestelend sed metus eloo dui, et vestulum rutrum nisl tempus </p> --}}
            </div>
          </div>
        </div><!-- icon-item end -->
        <div class="col-lg-4 col-sm-6">
          <div class="icon-item text-center">
            <div class="icon"><i class="fa fa-volume-control-phone"></i></div>
            <div class="content">
              <h4 class="title">customer support</h4>
              {{-- <p>Tristique ac felis ultr egestelend sed metus eloo dui, et vestulum rutrum nisl tempus </p> --}}
            </div>
          </div>
        </div><!-- icon-item end -->
      </div>
    </div>
  </section>
  <!-- features-section end -->

  



@endsection