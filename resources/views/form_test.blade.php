@extends('layout')
@section('content')
    <div style="margin-top: 250px">
    <h2>Test Online</h2>
    <hr>
    <div class="alert alert-info">
        <strong>Timer: </strong>
        <div id="timer" style="font-size: 20px"></div>
    </div>
    <div id="alert-waiting-webcam" class="alert alert-info">
        <strong>Mohon tunggu</strong> sedang membuka webcam...
    </div>
    <div id="question-wrapper" style="display: none">
        <div class="alert alert-warning">
            Mohon untuk fokus pada formulir ini. Ikuti peraturan dan tata carai sebagai berikut :
            <ol>
                <li>Lepaskan kaca mata sesaat apabila tidak dapat discan, sampai muncul kotak hijau</li>
                <li>Dilarang merefresh ulang halaman ini</li>
                <li>Tidak diperkenankan untuk membuka gadget/smartphone/komputer lain untuk mencari jawaban</li>
                <li>Tidak diperkenankan untuk membuka browser/tab lain untuk mencari jawaban</li>
                <li>Tidak diperkenankan untuk meninggalkan form test online selama berlangsung</li>
                <li>Tidak menggunakan cheat/menipu/meminta bantu orang lain untuk menjawab test online</li>
                <li>Kerjakan test online sesuai dengan waktu yang telah ditentukan</li>
            </ol>
            Segala bentuk pelanggaran akan langsung diskualifikasi.
        </div>
        <p>Silahkan jawab sesuai dengan apa yang Anda ketahui</p>

        <form id="form-test" method="post" action="{{ url('finish-test') }}">
            {!! csrf_field() !!}
            <input type="hidden" name="id" value="{{ $report_test->id }}">
            <ol>
                @foreach($questions as $question)
                    <li>
                        <p><strong>{{ $question->question }}</strong></p>
                        <input type="hidden" name="questions_id[]" value="{{ $question->id }}">
                        <input type="hidden" name="questions_question[]" value="{{$question->question}}">
                        <textarea name="answer[{{$question->id}}]" class="form-control" placeholder="Ketik jawaban disini" required rows="3"></textarea>
                    </li>
                @endforeach
            </ol>

            <br>
            <button class="btn btn-success w-100" onclick="finish()" type="button">Selesai</button>
        </form>
    </div>
    </div>
    @push("bottom")
        <script src="{{ asset('asset/webgazer.min.js') }}" type="text/javascript"></script>

        <script>
            jQuery.fn.center = function () {
                this.css("position","absolute");
                this.css("top", "20px");
                this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                    $(window).scrollLeft()) + "px");
                return this;
            }

            let isFinish = false;
            function finish() {
                isFinish = true;
                $("#form-test").submit();
            }
            $(function() {
                let nik = "{{ $report_test->nik }}";
                $(window).blur(function() {
                    if(isFinish === false) {
                        $.post("{{url('api/abort-test')}}", {nik: nik});
                        alert("Anda keluar dari fokus browser!");
                        location.href = '{{ url('?status=diskualifikasi') }}';
                    }
                });
            })

            let timeout = null;
            let xPred = null;
            let yPred = null;
            let eyeCatch = false;
            let eyeLost = false;
            let preDiskual = false;

            var targetObj = {};
            var targetProxy = new Proxy(targetObj, {
                set: function (target, key, value) {
                    if(key === 'eyeLost' && value === true) {
                        faceNotDetected();
                    }
                    console.log(`${key} set to ${value}`);
                    target[key] = value;
                    return true;
                }
            });

            function sleep(ms) {
                return new Promise(resolve => setTimeout(resolve, ms));
            }

            async function faceNotDetected()
            {
                webgazer.pause();
                isFinish = true;
                $("#question-wrapper").remove();
                await alert("Wajah tidak terdeteksi!");
                await $.post("{{ url('api/abort-test') }}");
                location.href = '{{ url('/?status=diskualifikasi') }}';
                // await sleep(1500);
            }

            async function main() {
                await webgazer.setGazeListener(function(data, elapsedTime) {
                    if (data == null) {
                        return;
                    }
                    xPred = data.x; //these x coordinates are relative to the viewport
                    yPred = data.y; //these y coordinates are relative to the viewport
                    eyeCatch = true;
                }).begin();

                let check = setInterval(async ()=> {
                    if(eyeCatch) {
                        let inside = $("#webgazerFaceFeedbackBox").attr('style');
                        if(inside && inside.includes("solid green") && xPred && yPred) {
                            console.log("Face inside");
                            $("#alert-waiting-webcam").hide();
                            $("#question-wrapper").show();
                            clearTimeout(timeout);
                            preDiskual = false;
                        } else {
                            $("#question-wrapper").hide();
                            $("#alert-waiting-webcam").html("Mohon fokus pada formulir, dalam 3 detik sistem akan terkunci...").show();
                            if(preDiskual === false) {
                                preDiskual = true;
                                timeout = setTimeout(function() {
                                    targetProxy.eyeLost = true;
                                    clearInterval(check);
                                }, 3000);
                            }
                        }
                    }
                },500);

                let check2 = setInterval(async ()=>{
                    $("#webgazerVideoContainer").center();
                }, 100);
            }

            main();


            // Count down timer
            // Mengatur waktu akhir perhitungan mundur
            let countDownDate = new Date(new Date().setHours(new Date().getHours() + 1));

            // Memperbarui hitungan mundur setiap 1 detik
            var x = setInterval(function() {

                // Untuk mendapatkan tanggal dan waktu hari ini
                var now = new Date().getTime();

                // Temukan jarak antara sekarang dan tanggal hitung mundur
                var distance = countDownDate - now;

                // Perhitungan waktu untuk hari, jam, menit dan detik
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                // Keluarkan hasil dalam elemen dengan id = "demo"
                document.getElementById("timer").innerHTML = days + "d " + hours + "h "
                    + minutes + "m " + seconds + "s ";

                // Jika hitungan mundur selesai, tulis beberapa teks
                if (distance < 0) {
                    clearInterval(x);
                    isFinish = true;
                    $.post("{{url('api/abort-test')}}");
                    alert("Anda keluar dari fokus browser!");
                    location.href = '{{ url('?status=diskualifikasi') }}';
                    document.getElementById("timer").innerHTML = "EXPIRED";
                }
            }, 1000);

            function disableF5(e) { if ((e.which || e.keyCode) == 116 || (e.which || e.keyCode) == 82) e.preventDefault(); };

            $(document).ready(function(){
                $(document).on("keydown", disableF5);
            });
        </script>
    @endpush
@endsection
