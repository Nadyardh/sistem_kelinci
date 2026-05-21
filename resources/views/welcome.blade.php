<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title>Dashboard Kandang Kelinci</title>
    <style>
        :root{
            --pastel-1: #F6F0FF; /* lavender */
            --pastel-2: #E6FBF2; /* mint */
            --pastel-3: #FFF6EA; /* peach */
            --accent: #9DA7FF;
            --card-radius: 14px;
        }
        body{
            background: linear-gradient(135deg, var(--pastel-1), var(--pastel-2));
            min-height:100vh;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
            color: #344054;
            padding-bottom: 40px;
        }
        .app-header{
            background: rgba(255,255,255,0.6);
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 6px 18px rgba(20,20,40,0.06);
        }
        .metric-card{
            border: none;
            border-radius: var(--card-radius);
            box-shadow: 0 8px 30px rgba(13, 38, 59, 0.06);
        }
        .metric-value{
            font-size: 2.25rem;
            font-weight: 700;
        }
        .muted-small{color: #6b7280;font-size:0.9rem}
        .messages-box{height: 260px; overflow:auto; background: rgba(255,255,255,0.7); border-radius:10px; padding:12px}
        .msg-user{background: #fff4f0; padding:8px 12px; border-radius:12px; display:inline-block}
        .msg-ai{background: #f0f9ff; padding:8px 12px; border-radius:12px; display:inline-block}
        .form-floating textarea{min-height:110px}
        @media (max-width:767px){
            .metric-value{font-size:1.6rem}
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="app-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-1">
                    <img src="https://i.ibb.co.com/fGNnxJbC/Pngtree-logo-type-with-cute-bunny-5507152.png" alt="Rabbit Icon" width="100%" height="100%">
                </div>
                <div class="col-md-10">
                    <h2 class="mb-1">Sistem Kandang Kelinci — Dashboard</h2>
                    <p class="mb-0 muted-small">Visualisasi suhu & kelembapan lingkungan. Siap dihubungkan ke cloud dan model AI.</p>
                </div>
                <div class="col-md-1 text-md-end mt-3 mt-md-0">
                    <small class="text-muted">Status koneksi:</small>
                    <div class="d-inline-block ms-2 badge bg-light text-dark border">Local only</div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card metric-card p-3 mb-3" style="background: linear-gradient(180deg,var(--pastel-3), #fff)">
                    <div class="d-flex align-items-center">
                        <div class="me-3 display-6 text-warning"><i class="bi bi-thermometer-sun"></i></div>
                        <div>
                            <div class="muted-small">Suhu Lingkungan</div>
                            <div class="metric-value">{{ isset($suhu) ? $suhu . '°C' : '26°C' }}</div>
                            <div class="muted-small">Sensor terakhir: {{ $last_update ?? '—' }}</div>
                        </div>
                    </div>
                </div>

                <div class="card metric-card p-3 mb-3" style="background: linear-gradient(180deg,var(--pastel-2), #fff)">
                    <div class="d-flex align-items-center">
                        <div class="me-3 display-6 text-info"><i class="bi bi-moisture"></i></div>
                        <div>
                            <div class="muted-small">Kelembapan</div>
                            <div class="metric-value">{{ isset($kelembapan) ? $kelembapan . '%' : '70%' }}</div>
                            <div class="muted-small">Sensor lokasi: {{ $sensor_location ?? 'Kandang 1' }}</div>
                        </div>
                    </div>
                </div>

                <div class="card metric-card p-3" style="background: linear-gradient(180deg,#fff, var(--pastel-1))">
                    <div class="muted-small">Ringkasan Singkat</div>
                    <div class="mt-2"> 
                        <p class="mb-1">Model AI: <strong>{{ $ai_summary ?? 'Belum tersedia' }}</strong></p>
                        <p class="mb-0 muted-small">Rekomendasi cepat berdasarkan ambang batas lokal.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Kesimpulan Model AI</h6>
                        <small class="muted-small">Auto / Manual</small>
                    </div>
                        <div class="mb-3">
                            <p class="" id="ai_conclusion" name="ai_conclusion">{{ $ai_conclusion ?? '' }}</p>
                            <label for="ai_conclusion">Suhu 26°C dan kelembapan 70%, kelinci tidak mengalami heat stress </label>
                        </div>
                </div>

                <div class="card p-3">
                    <h6>Masukan / Saran dari Model AI</h6>
                    <div class="mt-2">
                        <ul class="list-unstyled">
                            @if(!empty($ai_suggestions) && is_array($ai_suggestions))
                                @foreach($ai_suggestions as $s)
                                    <li class="mb-2"><i class="bi bi-lightbulb-fill text-warning"></i> {!! $s !!}</li>
                                @endforeach
                            @else
                                <li class="text-muted">Belum ada saran. Model belum terhubung.</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card p-3 mb-3">
                    <h6 class="mb-2">Interaksi Pengguna (Chat)</h6>
                    <div class="messages-box mb-3" id="messages">
                        <div class="mb-2"><small class="text-muted">01:23</small><div class="mt-1 msg-ai">Halo, apa yang bisa saya bantu?</div></div>
                        <div class="mb-2 text-end"><small class="text-muted">01:24</small><div class="mt-1 msg-user">Berikan saya saran untuk mendinginkan kelinci?</div></div>
                        <div class="mb-2"><small class="text-muted">01:24</small><div class="mt-1 msg-ai">Anda bisa memasang kipas angin di kandang untuk meningkatkan sirkulasi udara.</div></div>
                    </div>

                    <form id="chatForm">
                        <div class="input-group">
                            <input type="text" id="chatInput" class="form-control" placeholder="Tanyakan ke AI atau tinggalkan catatan...">
                            <button class="btn btn-primary" type="submit">Kirim</button>
                        </div>
                    </form>
                </div>

                <div class="card p-3">
                    <h6 class="mb-2">Aksi Cepat</h6>
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-danger">Nyalakan Exhaust Fan</button>
                        <button class="btn btn-outline-success">Aktifkan Mister</button>
                        <button class="btn btn-outline-secondary">Matikan Semua</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
        // Small demo JS to show local chat interactions and autofill AI conclusion
        (function(){
            const chatForm = document.getElementById('chatForm');
            const chatInput = document.getElementById('chatInput');
            const messages = document.getElementById('messages');
            const btnAutofill = document.getElementById('btn-autofill');
            const aiConclusion = document.getElementById('ai_conclusion');

            function appendMessage(content, who){
                const wrapper = document.createElement('div');
                const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                const small = document.createElement('small');
                small.className = 'text-muted';
                small.textContent = time;
                const msg = document.createElement('div');
                msg.className = who === 'user' ? 'msg-user mt-1' : 'msg-ai mt-1';
                msg.innerHTML = content;
                wrapper.appendChild(small);
                if(who === 'user') wrapper.className = 'text-end mb-2'; else wrapper.className = 'mb-2';
                wrapper.appendChild(msg);
                messages.appendChild(wrapper);
                messages.scrollTop = messages.scrollHeight;
            }

            chatForm.addEventListener('submit', function(e){
                e.preventDefault();
                const v = chatInput.value.trim();
                if(!v) return;
                appendMessage(v, 'user');
                chatInput.value = '';
                setTimeout(()=>{
                    appendMessage('Terima kasih. Model sedang menganalisa, saran: turunkan suhu 2°C dan aktifkan mister selama 10 menit.', 'ai');
                }, 800);
            });

            btnAutofill.addEventListener('click', function(){
                aiConclusion.value = 'Suhu terlampau tinggi. Disarankan menyalakan exhaust fan dan meningkatkan ventilasi. Monitor setiap 15 menit.';
            });
        })();
    </script>
</body>
</html>