@extends('layout.backoffice')

@section('content')
<div id="content-header" class="mini">
<h1>Dashboard</h1>
</div>

<div id="breadcrumb">
<a href="#" title="Go to Home" class="tip-bottom"><i class="fa fa-home"></i> Home</a>
<a href="#" class="current">Dashboard</a>
</div>
	<div class="row">
        <div class="col-sm-12 col-md-12">
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="row">
                <div class="col-sm-12">
                  <p class="mb15">Howdy <strong>{{ AuthCtrl::user()->bu_real_name }}</strong>,<br> Selamat datang di Halaman Administrator <strong>{{ config('config.basic.site_name') }}</strong>!. </p>
                   <p class="mb15">
                    Anda berada di halaman TERPROTEKSI sistem Pengaturan Konten Website 
                      <strong>{{ config('config.basic.site_name') }}</strong>.<br />
                      <br>
              Sebelum melakukan pengaturan, beberapa hal yang perlu diperhatikan : <br><br />

              1. Simpan dan amankan Username dan Password Anda untuk menghindari hal-hal yang tidak diinginkan.<br>
              2. Siapkan dahulu materi /bahan yang akan digunakan dalam pengelolaan konten yang akan di update.<br>
              3. Siapkan pula foto, video atau material lain yang terkait, untuk memudahkan proses pengupdate-an.<br>
              4. Apabila terdapat pertanyaan tentang pemakaian fitur pada backoffice ini, dapat ditanyakan pada kontak web developer dibawah.<br>
              5. Gunakan Browser dengan versi terbaru untuk mendapatkan compatibilitas fitur-fitur dalam backoffice ini. </p><br />
              <br />
                <p>Web Developer Contact :<br>
                  Tita Aprilianti<br>
                  Email : tita@lingkar9.com<br>
                  Phone : 087886571897</p>
                <br />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
@stop