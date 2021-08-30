@extends('layout')
@section('content')
    @if(request('status')=='diskualifikasi')
    <div class="alert alert-warning">
        Anda telah didiskualifikasi!
    </div>
    @endif
    <h2>Data Diri</h2>
    <hr>
    <p>Silahkan isi data diri Anda:</p>
    <form method="post" action="{{ url('save-personal') }}">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="">NIK</label>
            <input type="text" name="nik" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="">Telp</label>
            <input type="tel" name="phone" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="">Posisi Yang Dilamar</label>
            <select name="position" required class="form-control">
                <option value="">** Pilih Posisi</option>
                <option value="Frontend Engineer">Frontend Engineer</option>
                <option value="Laravel Backend Engineer">Laravel Backend Engineer</option>
                <option value="Android Engineer">Android Engineer</option>
                <option value="IOS Engineer">IOS Engineer</option>
            </select>
        </div>
        <div class="form-group" style="margin-top: 20px">
            <input type="checkbox" checked required name="agree"> Sebelum mengikuti test online ini, Anda telah setuju untuk mengikuti peraturan dan tata cara test online ini.
            <ol>
                <li>Gunakan browser chrome</li>
                <li>Pastikan komputer Anda dilengkapi dengan WebCam</li>
                <li>Silahkan cari tempat yang nyaman dan tenang untuk test online</li>
                <li>Pastikan pencahayaan ruangan Anda terang terutama diarea muka</li>
                <li>Tidak diperkenankan untuk membuka gadget/smartphone/komputer lain untuk mencari jawaban</li>
                <li>Tidak diperkenankan untuk membuka browser/tab lain untuk mencari jawaban</li>
                <li>Tidak diperkenankan untuk meninggalkan form test online selama berlangsung</li>
                <li>Tidak menggunakan cheat/menipu/meminta bantu orang lain untuk menjawab test online</li>
                <li>Kerjakan test online sesuai dengan waktu yang telah ditentukan</li>
            </ol>
            Segala bentuk kecurangan akan langsung kami diskualifikasi
        </div>
        <br>
        <button type="submit" class="btn w-100 btn-primary">Mulai</button>
    </form>
@endsection
