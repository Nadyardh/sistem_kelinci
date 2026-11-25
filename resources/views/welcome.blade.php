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
            height: 100%;
        }
        .section-heading{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:1rem;
            margin-bottom:1rem;
        }
        .section-kicker{
            color:#7c82a3;
            font-size:.72rem;
            font-weight:700;
            letter-spacing:.12em;
            margin:0 0 .25rem;
            text-transform:uppercase;
        }
        .section-title{
            color:#344054;
            font-size:1.25rem;
            font-weight:700;
            margin:0;
        }
        .section-badge{
            align-items:center;
            background:rgba(255,255,255,.72);
            border:1px solid rgba(148,163,184,.18);
            border-radius:999px;
            color:#667085;
            display:inline-flex;
            font-size:.8rem;
            gap:.45rem;
            padding:.5rem .75rem;
            white-space:nowrap;
        }
        .data-panel{
            border:1px solid rgba(148,163,184,.18);
            border-radius:18px;
            box-shadow:0 8px 24px rgba(35,47,82,.06);
            height:100%;
            padding:1.15rem;
            transition:transform .18s ease, box-shadow .18s ease;
        }
        .data-panel:hover{
            box-shadow:0 12px 28px rgba(35,47,82,.1);
            transform:translateY(-2px);
        }
        .data-panel--peach{background:linear-gradient(145deg,#fff0e8,#fffaf7)}
        .data-panel--mint{background:linear-gradient(145deg,#e7faf2,#fbfffd)}
        .data-panel--lavender{background:linear-gradient(145deg,#f1edff,#fcfbff)}
        .data-panel--blue{background:linear-gradient(145deg,#eaf5ff,#fbfdff)}
        .data-panel--rose{background:linear-gradient(145deg,#fff0f3,#fffafb)}
        .data-panel-header{
            align-items:center;
            display:flex;
            gap:.85rem;
            margin-bottom:1rem;
        }
        .data-icon{
            align-items:center;
            background:rgba(255,255,255,.78);
            border:1px solid rgba(255,255,255,.9);
            border-radius:14px;
            display:flex;
            flex:0 0 46px;
            height:46px;
            justify-content:center;
            width:46px;
        }
        .data-icon--peach{color:#d97745}
        .data-icon--mint{color:#289875}
        .data-icon--lavender{color:#7664c8}
        .data-icon--blue{color:#4386c3}
        .data-icon--rose{color:#c75d7a}
        .data-label{
            color:#667085;
            font-size:.88rem;
            font-weight:600;
            margin-bottom:.2rem;
        }
        .data-value{
            color:#344054;
            font-size:1.85rem;
            font-weight:700;
            letter-spacing:-.04em;
            line-height:1.15;
            overflow-wrap:anywhere;
        }
        .data-value--compact{font-size:1.25rem;letter-spacing:-.02em}
        .data-helper{color:#7b8498;font-size:.78rem;margin-top:.4rem}
        .data-status{
            background:rgba(118,100,200,.12);
            border-radius:999px;
            color:#6655b2;
            display:inline-block;
            font-size:.75rem;
            font-weight:700;
            margin-top:.55rem;
            padding:.35rem .65rem;
        }
        .source-panel{
            border:1px solid rgba(148,163,184,.18);
            border-radius:18px;
            height:100%;
            padding:1.15rem;
        }
        .source-panel--indoor{background:linear-gradient(145deg,#fff4ed,#fffdfb)}
        .source-panel--outdoor{background:linear-gradient(145deg,#eef5ff,#fcfdff)}
        .comparison-panel{
            background:linear-gradient(145deg,#f3efff,#fff);
            border:1px solid rgba(148,163,184,.18);
            border-radius:18px;
            height:100%;
            padding:1rem;
        }
        .comparison-item + .comparison-item{
            border-top:1px solid rgba(148,163,184,.2);
            margin-top:.85rem;
            padding-top:.85rem;
        }
        .comparison-label{color:#667085;font-size:.78rem;font-weight:600;margin-bottom:.3rem}
        .comparison-result{color:#344054;font-size:.88rem;font-weight:700;line-height:1.35;overflow-wrap:anywhere}
        .latest-data-card{
            background:rgba(255,255,255,.62);
            border:1px solid rgba(148,163,184,.18);
            border-radius:22px;
            box-shadow:0 10px 28px rgba(35,47,82,.07);
            padding:1.25rem;
        }
        .latest-data-header{
            align-items:center;
            display:flex;
            gap:1rem;
            justify-content:space-between;
            margin-bottom:1.1rem;
        }
        .latest-time-card{
            align-items:center;
            background:linear-gradient(145deg,#f1edff,#fcfbff);
            border:1px solid rgba(148,163,184,.18);
            border-radius:16px;
            display:flex;
            gap:.75rem;
            justify-content:flex-end;
            max-width:320px;
            padding:.7rem 1rem;
        }
        .latest-time-details{text-align:right}
        .latest-time-value{color:#344054;font-size:.95rem;font-weight:700}
        .source-values{
            display:grid;
            gap:.65rem;
            grid-template-columns:repeat(2,minmax(0,1fr));
        }
        .source-value{
            background:rgba(255,255,255,.74);
            border:1px solid rgba(255,255,255,.9);
            border-radius:13px;
            min-width:0;
            padding:.75rem;
        }
        .source-value-label{color:#7b8498;display:block;font-size:.76rem;margin-bottom:.2rem}
        .source-value strong{color:#344054;font-size:1.05rem;overflow-wrap:anywhere}
        .source-value--wide{grid-column:1/-1}
        .metric-value{
            font-size: 2.25rem;
            font-weight: 700;
        }
        .muted-small{color: #6b7280;font-size:0.9rem}
        .messages-box{height:260px;overflow-y:auto;overscroll-behavior-y:contain;background:rgba(255,255,255,0.7);border-radius:10px;padding:12px}
        .msg-user{background: #fff4f0; padding:8px 12px; border-radius:12px; display:inline-block}
        .msg-ai{background: #f0f9ff; padding:8px 12px; border-radius:12px; display:inline-block}
        .msg-ai p:last-child,.msg-ai ul:last-child,.msg-ai ol:last-child{margin-bottom:0}
        .msg-ai h3,.msg-ai h4{font-size:1rem;font-weight:700;margin:0 0 .5rem}
        .msg-ai ul,.msg-ai ol{padding-left:1.25rem;margin-bottom:.6rem}
        .msg-ai li+li{margin-top:.2rem}
        .msg-ai code{background:rgba(255,255,255,.7);padding:.1rem .25rem;border-radius:4px}
        .form-floating textarea{min-height:110px}
        .status-column{
            display: flex;
            flex-direction: column;
        }
        .status-column .recommendation-card{
            flex: 1;
        }
        .chat-card{
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .chat-card .messages-box{
            flex: 0 1 420px;
            height: 420px;
            max-height: 55vh;
            min-height: 260px;
        }
        .chat-scroll-latest{
            position:absolute;
            z-index:1;
            bottom:1.75rem;
            left:50%;
            transform:translateX(-50%);
            border-radius:999px;
            box-shadow:0 3px 12px rgba(13,38,59,.18);
        }
        .chat-scroll-latest[hidden]{display:none}
        @media (max-width:991px){
            .metric-value{font-size:1.6rem}
            .section-heading{align-items:flex-start;flex-direction:column}
            .latest-data-header{align-items:stretch;flex-direction:column}
            .latest-time-card{align-self:flex-end;max-width:100%}
        }
        @media (max-width:767px){
            .data-panel{padding:1rem}
            .source-panel{padding:1rem}
            .data-value{font-size:1.6rem}
            .latest-time-card{align-self:stretch}
        }
    </style>
</head>
<body>
    <div class="container my-5">

        {{-- //Ini Header --}}
        <div class="app-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-1">
                    <img src="https://i.ibb.co.com/SXR6jNw2/Logo-Web.jpg" alt="Rabbit Icon" width="100%" height="100%">
                </div>
                <div class="col-md-8">
                    <h2 class="mb-1">Sistem Kandang Kelinci — Dashboard</h2>
                    <p class="mb-0 muted-small">Visualisasi suhu & kelembapan lingkungan. Siap dihubungkan ke cloud dan model AI.</p>
                </div>
                <div class="col-md-3 text-md-end mt-3 mt-md-0">
                    {{-- <small class="text-muted">Tanggal & Waktu:</small>
                    <div id="realtimeClock" class="">Memuat...</div> --}}
                </div>
            </div>
        </div>

        {{-- <section class="mb-4" aria-labelledby="oldDataHeading">
            <div class="section-heading">
                <div>
                    <p class="section-kicker">Pemantauan Kandang</p>
                    <h3 class="section-title" id="oldDataHeading">Informasi Data Lama</h3>
                </div>
                <span class="section-badge"><i class="bi bi-broadcast-pin"></i> Data sensor kandang</span>
            </div>
            <div class="row g-3 align-items-stretch">
            <div class="col-lg-3">
                <article class="data-panel data-panel--lavender">
                    <div class="data-panel-header">
                        <div class="data-icon data-icon--lavender"><i class="bi bi-clock-history fs-4"></i></div>
                        <div>
                            <div class="data-label">Tanggal & waktu</div>
                            <div class="data-value data-value--compact" id="realtimeClock">Memuat...</div>
                            <div class="data-helper">Waktu lokal dashboard</div>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-lg-3">
                <article class="data-panel data-panel--peach">
                    <div class="data-panel-header">
                        <div class="data-icon data-icon--peach"><i class="bi bi-thermometer-half fs-4"></i></div>
                        <div>
                            <div class="data-label">Suhu kandang</div>
                            <div class="data-value">{{ number_format($suhu, 1, ',', '.') }}°C</div>
                            <div class="data-helper">Sensor {{ $last_update }}</div>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-lg-3">
                <article class="data-panel data-panel--mint">
                    <div class="data-panel-header">
                        <div class="data-icon data-icon--mint"><i class="bi bi-moisture fs-4"></i></div>
                        <div>
                            <div class="data-label">Kelembapan kandang</div>
                            <div class="data-value">{{ number_format($kelembapan, 1, ',', '.') }}%</div>
                            <div class="data-helper">Sensor {{ $last_update }}</div>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-lg-3">
                <article class="data-panel data-panel--blue">
                    <div class="data-panel-header">
                        <div class="data-icon data-icon--blue"><i class="bi bi-clipboard-data fs-4"></i></div>
                        <div>
                            <div class="data-label">THI kandang</div>
                            <div class="data-value">{{ $thi !== null ? number_format($thi, 2, ',', '.') : 'N/A' }}</div>
                            <span class="data-status">{{ $status }}</span>
                        </div>
                    </div>
                </article>
            </div>
        </div>
        </section> --}}


        {{-- Ini data Outdoor --}}
        {{-- <div class="row mb-4 g-4 align-items-stretch">
            @if($outdoor_error)
                <div class="col-12">
                    <div class="alert alert-warning mb-0" role="alert">{{ $outdoor_error }}</div>
                </div>
            @endif
            <div class="col-lg-3">
                <div class="card metric-card p-3" style="background: linear-gradient(180deg,var(--pastel-3), #ffc2c2)">
                    <div class="d-flex align-items-center">
                        <div class="me-3 mt-4 display-6 text-danger"><i class="bi bi-clock"></i></div>
                        <div>
                            <div class=""><h5>Update Cuaca</h5></div>
                            <h5 class="mt-3">{{ $outdoor?->created_at?->format('d M Y H:i') ?? '—' }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card metric-card p-3" style="background: linear-gradient(180deg,var(--pastel-3), #fff)">
                    <div class="d-flex align-items-center">
                        <div class="me-3 display-6 text-warning"><i class="bi bi-thermometer-sun"></i></div>
                        <div>
                            <div class=""><h5>Suhu Lingkungan</h5></div>
                            <div class="metric-value">{{ $outdoor ? number_format($outdoor->suhu, 1, ',', '.') : '—' }}{{ $outdoor ? '°C' : '' }}</div>
                            @if($selisih_suhu === null)
                                <div class="muted-small">Perbandingan sensor belum tersedia</div>
                            @elseif($selisih_suhu > 0)
                                <div class="muted-small">Kandang lebih panas {{ number_format($selisih_suhu, 1, ',', '.') }}°C</div>
                            @elseif($selisih_suhu < 0)
                                <div class="muted-small">Luar lebih panas {{ number_format(abs($selisih_suhu), 1, ',', '.') }}°C</div>
                            @else
                                <div class="muted-small">Suhu kandang dan luar serupa</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card metric-card p-3" style="background: linear-gradient(180deg,var(--pastel-2), #fff)">
                    <div class="d-flex align-items-center">
                        <div class="me-3 display-6 text-info"><i class="bi bi-moisture"></i></div>
                        <div>
                            <div class=""><h5>Kelembapan Lingkungan</h5></div>
                            <div class="metric-value">{{ $outdoor ? number_format($outdoor->kelembapan, 1, ',', '.') : '—' }}{{ $outdoor ? '%' : '' }}</div>
                            <div class="muted-small">OpenWeather: {{ $outdoor?->created_at?->diffForHumans() ?? 'belum tersedia' }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card metric-card p-3" style="background: linear-gradient(180deg,var(--pastel-1), #c1e3ff)">
                    <div class="d-flex align-items-center">
                        <div class="me-3 display-6 text-info"><i class="bi bi-clipboard-data"></i></div>
                        <div>
                            <div class=""><h4>Kondisi Cuaca</h4></div>
                            <div class="metric-value">{{ $outdoor?->status_cuaca ?? '—' }}</div>
                            <div class="muted-small">Data dari OpenWeather</div>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}



        <section class="latest-data-card mb-4" aria-labelledby="latestDataHeading">
                    <div class="latest-data-header">
                <div>
                    <p class="section-kicker">Ringkasan pemantauan</p>
                    <h3 class="section-title" id="latestDataHeading">Informasi Data Terbaru</h3>
                </div>
                        <div class="latest-time-card">
                            <div class="data-icon data-icon--lavender"><i class="bi bi-calendar2-week fs-4"></i></div>
                            <div class="latest-time-details">
                                <div class="data-label">Tanggal & waktu</div>
                                <div class="latest-time-value">{{ now()->translatedFormat('l, d M Y') }}</div>
                                <div class="data-helper">{{ now()->format('H:i:s') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 align-items-stretch">
                        <div class="col-lg-5">
                            <article class="source-panel source-panel--indoor">
                                <div class="data-panel-header">
                                    <div class="data-icon data-icon--peach"><i class="bi bi-house-heart fs-4"></i></div>
                                    <div>
                                        <div class="data-label">Pembacaan sensor</div>
                                        <h4 class="section-title">Data Kandang</h4>
                            </div>
                        </div>
                                <div class="source-values">
                                    <div class="source-value">
                                        <span class="source-value-label">Suhu</span>
                                        <strong>{{ number_format($suhu, 1, ',', '.') }}°C</strong>
                                    </div>
                                    <div class="source-value">
                                        <span class="source-value-label">Kelembapan</span>
                                        <strong>{{ number_format($kelembapan, 1, ',', '.') }}%</strong>
                                    </div>
                                    <div class="source-value">
                                        <span class="source-value-label">THI</span>
                                        <strong>{{ $thi !== null ? number_format($thi, 2, ',', '.') : 'N/A' }}</strong>
                                    </div>
                                    <div class="source-value">
                                        <span class="source-value-label">Status</span>
                                        <strong>{{ $status }}</strong>
                                    </div>
                                </div>
                                <div class="data-helper mt-3">Pembaruan sensor: {{ $last_update }}</div>
                            </article>
                        </div>

                        <div class="col-lg-5">
                            <article class="source-panel source-panel--outdoor">
                                <div class="data-panel-header">
                                    <div class="data-icon data-icon--blue"><i class="bi bi-cloud-sun fs-4"></i></div>
                                    <div>
                                        <div class="data-label">OpenWeather</div>
                                        <h4 class="section-title">Data Cuaca</h4>
                                    </div>
                                </div>
                                <div class="source-values">
                                    <div class="source-value">
                                        <span class="source-value-label">Suhu luar</span>
                                        <strong>{{ $outdoor ? number_format($outdoor->suhu, 1, ',', '.').'°C' : '—' }}</strong>
                                    </div>
                                    <div class="source-value">
                                        <span class="source-value-label">Kelembapan</span>
                                        <strong>{{ $outdoor ? number_format($outdoor->kelembapan, 1, ',', '.').'%' : '—' }}</strong>
                                    </div>
                                    <div class="source-value source-value--wide">
                                        <span class="source-value-label">Kondisi cuaca</span>
                                        <strong>{{ $outdoor?->status_cuaca ?? 'Belum tersedia' }}</strong>
                                    </div>
                                </div>
                                @if($outdoor_error)
                                    <div class="alert alert-warning mt-3 mb-0" role="alert">{{ $outdoor_error }}</div>
                                @endif
                                <div class="data-helper mt-3">Pembaruan cuaca: {{ $outdoor?->created_at?->diffForHumans() ?? 'belum tersedia' }}</div>
                            </article>
                        </div>
                                                <div class="col-lg-2">
                            <article class="comparison-panel">
                                <h4 class="section-title mb-3">Perbandingan</h4>
                                <div class="comparison-item">
                                    <div class="comparison-label"><i class="bi bi-thermometer-half me-1"></i>Suhu</div>
                                    <div class="comparison-result">
                                        @if($selisih_suhu === null)
                                            Belum tersedia
                                        @elseif($selisih_suhu > 0)
                                            Kandang lebih panas {{ number_format($selisih_suhu, 1, ',', '.') }}°C
                                        @elseif($selisih_suhu < 0)
                                            Luar lebih panas {{ number_format(abs($selisih_suhu), 1, ',', '.') }}°C
                                        @else
                                            Suhu sama
                                        @endif
                                    </div>
                                </div>
                                <div class="comparison-item">
                                    <div class="comparison-label"><i class="bi bi-moisture me-1"></i>Kelembapan</div>
                                    <div class="comparison-result">
                                        @if($selisih_kelembapan === null)
                                            Belum tersedia
                                        @elseif($selisih_kelembapan > 0)
                                            Kandang lebih lembap {{ number_format($selisih_kelembapan, 1, ',', '.') }}%
                                        @elseif($selisih_kelembapan < 0)
                                            Luar lebih lembap {{ number_format(abs($selisih_kelembapan), 1, ',', '.') }}%
                                        @else
                                            Kelembapan sama
                                        @endif
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
        </section>


        {{-- Ini status kelinci --}}
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6 status-column">
                <div class="card p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Status Kelinci</h6>
                        <small class="muted-small">Real-time</small>
                    </div>
                    <div class="mb-3">
                        @if($status === 'Normal')
                            <div class="alert alert-success" role="alert">
                                Suhu {{ $suhu }}°C dan kelembapan {{ $kelembapan }}%, kondisi kelinci normal tanpa heat stress.
                            </div>
                        @elseif($status === 'Mild Stress')
                            <div class="alert alert-warning" role="alert">
                                Suhu {{ $suhu }}°C dan kelembapan {{ $kelembapan }}%, kelinci mengalami mild stress. Tingkatkan sirkulasi udara.
                            </div>
                        @elseif($status === 'Moderate Stress')
                            <div class="alert alert-danger" role="alert">
                                Suhu {{ $suhu }}°C dan kelembapan {{ $kelembapan }}%, kelinci mengalami moderate stress. Segera aktifkan pendinginan!
                            </div>
                        @else
                            <div class="alert alert-danger" role="alert">
                                PERHATIAN: Suhu {{ $suhu }}°C dan kelembapan {{ $kelembapan }}%, kelinci dalam kondisi severe stress. Ambil tindakan darurat sekarang!
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card p-3 recommendation-card">
                    <h6>Rekomendasi Sistem</h6>
                    <div class="mt-2">
                        <ul class="list-unstyled">
                            @if($status === 'Normal')
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> Kondisi optimal, pertahankan ventilasi saat ini</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-success"></i> Monitor setiap jam untuk deteksi dini perubahan</li>
                            @elseif($status === 'Mild Stress')
                                <li class="mb-2"><i class="bi bi-lightbulb-fill text-warning"></i> Tingkatkan sirkulasi udara dengan membuka ventilasi lebih lebar</li>
                                <li class="mb-2"><i class="bi bi-lightbulb-fill text-warning"></i> Monitor suhu setiap 30 menit</li>
                                <li class="mb-2"><i class="bi bi-lightbulb-fill text-warning"></i> Sediakan air minum dalam jumlah cukup</li>
                            @elseif($status === 'Moderate Stress')
                                <li class="mb-2"><i class="bi bi-exclamation-triangle-fill text-danger"></i> Nyalakan exhaust fan segera!</li>
                                <li class="mb-2"><i class="bi bi-exclamation-triangle-fill text-danger"></i> Aktifkan sistem misting untuk mendinginkan</li>
                                <li class="mb-2"><i class="bi bi-exclamation-triangle-fill text-danger"></i> Turunkan suhu setidaknya 3°C dalam 15 menit</li>
                            @else
                                <li class="mb-2"><i class="bi bi-exclamation-octagon-fill text-danger"></i> DARURAT: Nyalakan semua sistem pendingin!</li>
                                <li class="mb-2"><i class="bi bi-exclamation-octagon-fill text-danger"></i> Beri es atau air dingin untuk minuman</li>
                                <li class="mb-2"><i class="bi bi-exclamation-octagon-fill text-danger"></i> Hubungi dokter hewan jika tidak membaik dalam 30 menit</li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

            <div class="card p-3 chat-card">
                        <div class="d-flex justify-content-between mb-2">
                            <h6 class="mb-0">Chat dengan AI</h6>

                            <button
                                class="btn btn-sm btn-primary"
                                id="askAI">
                                <i class="bi bi-stars"></i>
                                Saran AI
                            </button>
                        </div>
                        <div class="position-relative mb-3">
                            <div class="messages-box" id="messages" role="log" aria-label="Riwayat chat"></div>
                            <button class="btn btn-light btn-sm chat-scroll-latest" id="scrollToLatest" type="button" aria-label="Lompat ke pesan terbaru" aria-controls="messages" hidden>
                                <i class="bi bi-arrow-down" aria-hidden="true"></i>
                                Pesan terbaru
                            </button>
                        </div>
                        <form id="chatForm">
                            <div class="input-group">
                                <input type="text" id="chatInput" class="form-control" placeholder="Tanyakan tentang perawatan kelinci..." maxlength="4000" required>
                                <button class="btn btn-primary" type="submit">Kirim</button>
                            </div>
                        </form>
                    </div>
        </div>

    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
        // Keep the dashboard clock and restore this browser tab's active chat.
        (function(){
            const chatForm = document.getElementById('chatForm');
            const chatInput = document.getElementById('chatInput');
            const messages = document.getElementById('messages');
            const scrollToLatestButton = document.getElementById('scrollToLatest');
            const clock = document.getElementById('realtimeClock');
            const askAI = document.getElementById('askAI');
            const sendButton = chatForm.querySelector('button[type="submit"]');
            let conversationId = sessionStorage.getItem('rabbitChatConversationId');
            const csrfToken = '{{ csrf_token() }}';

            function formatDateTime(date) {
                return date.toLocaleString('id-ID', {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
            }

            function refreshClock() {
                if(clock) {
                    clock.textContent = formatDateTime(new Date());
                }
            }

            function isNearLatestMessage() {
                return messages.scrollHeight - messages.scrollTop - messages.clientHeight < 64;
            }

            function updateScrollToLatestButton() {
                scrollToLatestButton.hidden = messages.scrollHeight <= messages.clientHeight || isNearLatestMessage();
            }

            function scrollToLatest(behavior = 'auto') {
                messages.scrollTo({
                    top: messages.scrollHeight,
                    behavior: behavior
                });
                scrollToLatestButton.hidden = true;
            }

            messages.addEventListener('scroll', updateScrollToLatestButton);
            scrollToLatestButton.addEventListener('click', () => scrollToLatest('smooth'));


            askAI.addEventListener('click', async function () {
                askAI.disabled = true;
                const pending = appendMessage(
                    'Mohon tunggu, AI sedang menganalisa kondisi kandang...',
                    'ai'
                );
                try {
                    const response = await fetch(
                        '/ai-recommendation',
                        {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        }
                    );

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(data.error || data.message || 'Gagal mengambil rekomendasi dari AI');
                    }

                    // Prefer HTML-rendered recommendation from backend; fallback to plain text
                    const recHtml = data.recommendation_html || data.recommendation || '';

                    let message = '<strong>Rekomendasi AI:</strong><br>' + recHtml;

                    if (data.sensor_data) {
                        message += '<br><br><small class="text-muted">Data: ' +
                                   data.sensor_data.temperature + '°C, ' +
                                   data.sensor_data.humidity + '% — ' +
                                   data.sensor_data.updated_at + '</small>';
                    }

                    appendMessage(message, 'ai', true);

                } catch(error) {
                    appendMessage(
                        'Gagal: ' + error.message,
                        'ai'
                    );
                    console.error('AI Error:', error);
                } finally {
                    pending.remove();
                    askAI.disabled = false;
                }
            });

            function appendMessage(content, who, allowHtml = false){
                const shouldFollow = who === 'user' || isNearLatestMessage();
                const wrapper = document.createElement('div');
                const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                const small = document.createElement('small');
                small.className = 'text-muted';
                small.textContent = time;
                const msg = document.createElement('div');
                msg.className = who === 'user' ? 'msg-user mt-1' : 'msg-ai mt-1';
                if (allowHtml) {
                    msg.innerHTML = content;
                } else if (who === 'ai') {
                    renderMarkdown(msg, content);
                } else {
                    msg.textContent = content;
                }
                wrapper.appendChild(small);
                wrapper.className = who === 'user' ? 'text-end mb-2' : 'mb-2';
                wrapper.appendChild(msg);
                messages.appendChild(wrapper);
                if (shouldFollow) {
                    scrollToLatest();
                } else {
                    updateScrollToLatestButton();
                }
                return wrapper;
            }

            function renderMarkdown(container, markdown) {
                const lines = String(markdown).replace(/\r\n?/g, '\n').split('\n');
                let paragraphLines = [];
                let listStack = [];

                function flushParagraph() {
                    if (paragraphLines.length === 0) return;
                    const paragraph = document.createElement('p');
                    appendInlineMarkdown(paragraph, paragraphLines.join(' '));
                    container.appendChild(paragraph);
                    paragraphLines = [];
                }

                function flushList() {
                    listStack = [];
                }

                function getList(indent, type) {
                    if (listStack.length && indent > listStack[listStack.length - 1].indent) {
                        const parentItem = listStack[listStack.length - 1].lastItem;
                        const element = document.createElement(type);
                        parentItem.appendChild(element);
                        const list = { indent, type, element, lastItem: null };
                        listStack.push(list);
                        return list;
                    }

                    while (listStack.length && listStack[listStack.length - 1].indent > indent) {
                        listStack.pop();
                    }

                    const current = listStack[listStack.length - 1];
                    if (current && current.indent === indent && current.type === type) {
                        return current;
                    }

                    const parent = current && current.indent === indent
                        ? current.element.parentNode
                        : (current ? current.lastItem : container);
                    if (current && current.indent === indent) {
                        listStack.pop();
                    }

                    const element = document.createElement(type);
                    parent.appendChild(element);
                    const list = { indent, type, element, lastItem: null };
                    listStack.push(list);
                    return list;
                }

                lines.forEach((line) => {
                    if (!line.trim()) {
                        flushParagraph();
                        flushList();
                        return;
                    }

                    const heading = line.match(/^\s{0,3}(#{1,3})\s+(.+)$/);
                    if (heading) {
                        flushParagraph();
                        flushList();
                        const element = document.createElement(heading[1].length === 1 ? 'h3' : 'h4');
                        appendInlineMarkdown(element, heading[2]);
                        container.appendChild(element);
                        return;
                    }

                    const item = line.match(/^(\s{0,8})([-*+]|\d+[.)])\s+(.+)$/);
                    if (item) {
                        flushParagraph();
                        const indent = item[1].replace(/\t/g, '    ').length;
                        const type = /^\d/.test(item[2]) ? 'ol' : 'ul';
                        const list = getList(indent, type);
                        const listItem = document.createElement('li');
                        appendInlineMarkdown(listItem, item[3]);
                        list.element.appendChild(listItem);
                        list.lastItem = listItem;
                        return;
                    }

                    flushList();
                    paragraphLines.push(line.trim());
                });

                flushParagraph();
            }

            function appendInlineMarkdown(container, text) {
                const tokens = /(\*\*[^*]+\*\*|__[^_]+__|`[^`]+`|\*[^*]+\*|_[^_]+_)/g;
                let lastIndex = 0;
                let match;

                while ((match = tokens.exec(text)) !== null) {
                    container.appendChild(document.createTextNode(text.slice(lastIndex, match.index)));

                    const token = match[0];
                    const isBold = token.startsWith('**') || token.startsWith('__');
                    const isCode = token.startsWith('`');
                    const tag = isBold ? 'strong' : (isCode ? 'code' : 'em');
                    const markerLength = isBold ? 2 : 1;
                    const element = document.createElement(tag);
                    element.textContent = token.slice(markerLength, -markerLength);
                    container.appendChild(element);
                    lastIndex = match.index + token.length;
                }

                container.appendChild(document.createTextNode(text.slice(lastIndex)));
            }

            async function restoreConversation() {
                if (!conversationId) return;

                try {
                    const response = await fetch('/ai-chat/' + encodeURIComponent(conversationId), {
                        headers: { 'Accept': 'application/json' }
                    });
                    if (response.status === 404) {
                        sessionStorage.removeItem('rabbitChatConversationId');
                        conversationId = null;
                        return;
                    }
                    if (!response.ok) throw new Error('Gagal memuat riwayat percakapan.');
                    const data = await response.json();
                    data.messages.forEach((message) => appendMessage(message.content, message.role === 'user' ? 'user' : 'ai'));
                } catch (error) {
                    appendMessage(error.message, 'ai');
                    console.error('Chat history error:', error);
                }
            }

            chatForm.addEventListener('submit', async function(e){
                e.preventDefault();
                const v = chatInput.value.trim();
                if(!v) return;
                appendMessage(v, 'user');
                chatInput.value = '';
                sendButton.disabled = true;
                chatInput.disabled = true;
                const pending = appendMessage('AI sedang menyiapkan jawaban...', 'ai');

                try {
                    const response = await fetch('/ai-chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            message: v,
                            conversation_id: conversationId
                        })
                    });
                    const data = await response.json();
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Gagal mengirim pesan ke AI.');
                    }

                    conversationId = data.conversation_id;
                    sessionStorage.setItem('rabbitChatConversationId', conversationId);
                    appendMessage(data.reply, 'ai');
                } catch(error) {
                    appendMessage(error.message, 'ai');
                    console.error('Chat error:', error);
                } finally {
                    pending.remove();
                    sendButton.disabled = false;
                    chatInput.disabled = false;
                    chatInput.focus();
                }
            });

            restoreConversation();
            refreshClock();
            setInterval(refreshClock, 1000);
        })();
    </script>
</body>
</html>
