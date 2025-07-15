<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> {{-- Penting untuk keamanan form --}}
    <title>Sistem Akademik UNIMAL</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        :root {
            --color-primary: #00712D;
            --color-secondary-bg: #FFFBE6;
            --color-accent: #FF9100;
            --text-on-dark: #FFFFFF;
        }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .navbar-brand img { height: 40px; }
        
        /* --- BAGIAN HERO YANG DIPERBARUI --- */
        .hero { 
            background: linear-gradient(to right, rgba(0, 113, 45, 0.9), rgba(0, 90, 36, 0.8)), url('{{ asset('images/gedung-unimal.jpeg') }}') no-repeat center center/cover;
            
            color: var(--text-on-dark); 
            padding: 120px 0; 
            min-height: 90vh;
            display: flex;
            align-items: center;
        }
        .hero-content h1 {
            font-size: 3.2em;
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
            animation: fadeInUp 1s ease-out;
        }
        .hero-content p {
            font-size: 1.2em;
            max-width: 600px;
            margin: 20px 0 30px;
            animation: fadeInUp 1s ease-out 0.3s;
            animation-fill-mode: backwards;
        }
        .hero-content .btn {
            animation: fadeInUp 1s ease-out 0.6s;
            animation-fill-mode: backwards;
            padding: 12px 30px;
            font-size: 1.1em;
            border-radius: 50px;
        }
        .hero-img {
            animation: fadeInRight 1.2s ease-out;
            text-align: center;
        }
        .hero-img img {
            max-width: 80%;
            height: auto;
        }

        /* Animasi */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(50px); }
            to { opacity: 1; transform: translateX(0); }
        }
        /* ------------------------------------ */

        .section-title { text-align: center; font-size: 2.5em; color: var(--color-primary); margin-bottom: 50px; position: relative; font-weight: 700; }
        .section-title::after { content: ''; width: 80px; height: 4px; background-color: var(--color-accent); position: absolute; bottom: -15px; left: 50%; transform: translateX(-50%); }
        /* --- BAGIAN TESTIMONI YANG DIPERBARUI --- */
        .testimonial-item {
            background: var(--text-on-dark);
            padding: 30px;
            border-radius: 15px;
            position: relative;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .testimonial-item:hover {
            transform: translateY(-10px); 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .testimonial-item .quote-icon {
            font-size: 3em;
            color: var(--color-accent);
            opacity: 0.2;
            position: absolute;
            top: 15px;
            left: 20px;
        }
        .testimonial-item p.content {
            font-style: italic;
            color: #555;
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }
        .testimonial-author {
            display: flex;
            align-items: center;
            margin-top: 20px;
        }
        .testimonial-author img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            object-fit: cover;
        }
        .testimonial-author .name {
            font-weight: 600;
            color: var(--color-primary);
        }
        .testimonial-author .role {
            font-size: 0.9em;
            color: #777;
        }
        
        .stat-item i { font-size: 3em; }
        .bg-primary { background-color: var(--color-primary) !important; }
        .btn-warning { background-color: var(--color-accent) !important; border-color: var(--color-accent) !important; color: var(--text-on-dark) !important; }
        .btn-warning:hover { background-color: #E68200 !important; border-color: #E68200 !important; }
        .form-response { display: none; margin-top: 15px; }
    </style>
</head>
<body>

    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <img src="images/logo.png" alt="Logo UNIMAL">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="#home">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link" href="#stats">Statistik</a></li>
                        <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                        <li class="nav-item"><a class="nav-link" href="#testimonials">Testimoni</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    {{-- --- BAGIAN HERO YANG DIPERBARUI --- --}}
    <section id="home" class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 text-center text-lg-start hero-content">
                    <h1>Sistem Informasi Akademik Modern UNIMAL</h1>
                    <p>Platform terintegrasi untuk mengelola aktivitas akademik, mulai dari pengisian KRS, melihat jadwal, hingga memantau hasil studi dengan lebih efisien.</p>
                    <a href="#features" class="btn btn-warning btn-lg cta-button fw-bold">Jelajahi Fitur</a>
                </div>
                <div class="col-lg-5 d-none d-lg-block hero-img">
                    {{-- Gambar mockup aplikasi mobile --}}
                    <img src="images/logo.png" class="img-fluid" alt="Mockup Aplikasi Mobile">
                </div>
            </div>
        </div>
    </section>

    {{-- Bagian Statistik Dinamis --}}
    <section id="stats" class="py-5">
        <div class="container">
            <h2 class="section-title">Statistik</h2>
            <div class="row g-4 text-center gap-3">
                <a href="{{route('statistik.mahasiswa')}}" class="text-decoration-none text-dark col-md-3 card h-100 shadow-sm stat-item p-3 ">
                <div class="col-md-4">
                    <div class="">
                        <i class="fas fa-users text-success mb-3"></i>
                        <h3>{{ $stats['mahasiswa'] }}+</h3>
                        <p class="text-muted">Mahasiswa Aktif</p>
                    </div>
                </div>
                </a>
                <a href="{{route('statistik.dosen')}}" class="text-decoration-none text-dark col-md-3 card h-100 shadow-sm stat-item p-3">
                <div class="col-md-4">
                    <div class="">
                        <i class="fas fa-user-tie text-success mb-3"></i>
                        <h3>{{ $stats['dosen'] }}+</h3>
                        <p class="text-muted">Dosen Pengajar</p>
                    </div>
                </div>
                </a>
                <a href="{{route('statistik.prodi')}}" class="text-decoration-none text-dark col-md-3 card h-100 shadow-sm stat-item p-3">
                <div class="col-md-4">
                    <div class="">
                        <i class="fas fa-graduation-cap text-success mb-3"></i>
                        <h3>{{ $pengumuman }}+</h3>
                        <p class="text-muted">Program Studi</p>
                    </div>
                </div>
                </a>
                <a href="{{route('statistik.pengumuman')}}" class="text-decoration-none text-dark col-md-3 card h-100 shadow-sm stat-item p-3">
                <div class="col-md-4">
                    <div class="">
                        <i class="fas fa-graduation-cap text-success mb-3"></i>
                        <h3>{{ $stats['prodi'] }}+</h3>
                        <p class="text-muted">Pengumuman</p>
                    </div>
                </div>
                </a>
            </div>
        </div>
    </section>

    {{-- Bagian Fitur Dinamis --}}
    <section id="features" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title">Fitur Unggulan</h2>
            <div class="row g-4">
                @forelse ($features as $feature)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 text-center shadow-sm feature-item p-4">
                            <i class="{{ $feature->icon }} fa-2x text-success mb-3"></i>
                            <h3 class="fs-5">{{ $feature->title }}</h3>
                            <p class="text-muted">{{ $feature->description }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center">Fitur akan segera ditambahkan.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Bagian Testimoni Dinamis --}}
    <section id="testimonials" class="py-5">
        <div class="container">
            <h2 class="section-title">Testimoni</h2>
            <div class="row g-4">
                @forelse ($testimonials as $testimonial)
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm testimonial-item">
                            <i class="fas fa-quote-left quote-icon"></i>
                            <div class="card-body d-flex flex-column">
                                <p class="content flex-grow-1">"{{ $testimonial->content }}"</p>
                                <div class="testimonial-author mt-auto">
                                    <img src="https://i.pravatar.cc/100?u={{ $testimonial->id }}" alt="Avatar">
                                    <div>
                                        <div class="name">{{ $testimonial->name }}</div>
                                        <div class="role">{{ $testimonial->role }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    <p class="text-center">Jadilah yang pertama memberikan testimoni!</p>
                @endforelse
            </div>
            <hr class="my-5">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h3 class="text-center mb-4">Bagikan Pengalaman Anda</h3>

                    {{-- Menampilkan pesan sukses testimoni --}}
                    @if (session('testimonial_status'))
                        <div class="alert alert-success">
                            {{ session('testimonial_status') }}
                        </div>
                    @endif

                    {{-- Form untuk menambah testimoni --}}
                    <form action="{{ route('landing.testimonial') }}" method="POST">
                        @csrf {{-- Token Keamanan Laravel --}}
                        <div class="mb-3">
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nama Anda" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control @error('role') is-invalid @enderror" name="role" placeholder="Peran Anda (cth: Mahasiswa Sistem Informasi)" value="{{ old('role') }}" required>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control @error('content') is-invalid @enderror" name="content" rows="4" placeholder="Tulis testimoni Anda di sini..." required>{{ old('content') }}</textarea>
                            @error('content') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-warning">Kirim Testimoni</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- Bagian Kontak Dinamis --}}
    <section id="contact" class="py-5 bg-light">
        <div class="container">
            <h2 class="section-title">Hubungi Kami</h2>
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    {{-- Menampilkan pesan sukses kontak --}}
                    @if (session('contact_status'))
                        <div class="alert alert-success">
                            {{ session('contact_status') }}
                        </div>
                    @endif

                    <form action="{{ route('landing.contact') }}" method="POST">
                        @csrf {{-- Token Keamanan Laravel --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Nama" value="{{ old('name') }}" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" placeholder="Email" value="{{ old('email') }}" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" name="subject" placeholder="Subjek" value="{{ old('subject') }}">
                        </div>
                        <div class="mb-3">
                            <textarea class="form-control @error('message') is-invalid @enderror" name="message" rows="5" placeholder="Pesan Anda" required>{{ old('message') }}</textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="text-center"><button type="submit" class="btn btn-primary">Kirim Pesan</button></div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} Sistem Akademik UNIMAL. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    
</body>
</html>
