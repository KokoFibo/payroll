 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
     <!-- Brand Logo -->
     <a href="index3.html" class="brand-link text-center">
         <img src="{{ asset('images/yifang_logo.png') }}" alt="Yifang Logo" style="opacity: .8">
         {{-- class="brand-image img-circle elevation-3" --}}
         {{-- <span class="brand-text font-weight-light">Yifang CME</span> --}}
     </a>

     <!-- Sidebar -->
     <div class="sidebar">
         <!-- Sidebar user panel (optional) -->
         <div class="user-panel mt-3 pb-3 mb-3 d-flex">

             <div class="image">
                 {{-- <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image"> --}}
                 <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random&length=1"
                     class="img-circle elevation-2" alt="User Image">
             </div>
             <div class="info">
                 <a href="#" class="d-block">{{ auth::user()->name }}</a>
             </div>


         </div>

         @include('layouts.menu')
     </div>
     <!-- /.sidebar -->
 </aside>
