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

        #testimonials{
            min-height: 90vh;
        }
        
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
                    <img src="{{ asset('images/logo.png') }}" alt="Logo UNIMAL">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="#home">Daftar Mahasiswa</a></li>
                        <li class="nav-item"><a class="nav-link" href="/">Kembali</a></li>
                        {{-- <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                        <li class="nav-item"><a class="nav-link" href="#testimonials">Testimoni</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Kontak</a></li> --}}
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    {{-- --- BAGIAN HERO YANG DIPERBARUI --- --}}
    {{-- <section id="home" class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7 text-center text-lg-start hero-content">
                    <h1>Sistem Informasi Akademik Modern UNIMAL</h1>
                    <p>Platform terintegrasi untuk mengelola aktivitas akademik, mulai dari pengisian KRS, melihat jadwal, hingga memantau hasil studi dengan lebih efisien.</p>
                    <a href="#features" class="btn btn-warning btn-lg cta-button fw-bold">Jelajahi Fitur</a>
                </div>
                <div class="col-lg-5 d-none d-lg-block hero-img">
                    {{-- Gambar mockup aplikasi mobile --}}
                    {{-- <img src="{{ asset('images/logo.png') }}" class="img-fluid" alt="Mockup Aplikasi Mobile">
                </div>
            </div>
        </div>
    {{-- </section> --}}  




    <section id="testimonials" class="py-5">
        <div class="container">
            <h2 class="section-title">Daftar Mahasiswa</h2>
            <div class="row g-4">
                @forelse ($mahasiswa as $mhs)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm testimonial-item">
                            <i class="fas fa-quote-left quote-icon"></i>
                            <div class="card-body d-flex flex-column">
                                {{-- <p class="content flex-grow-1">"{{ $testimo->content }}"</p> --}}
                                <div class="testimonial-author mt-auto">
                                    <img src="https://i.pravatar.cc/100?u={{ $mhs->id }}" alt="Avatar">
                                    <div>
                                        <div class="name">{{ $mhs->nama }}</div>
                                        <div class="role">{{ $mhs->alamat }}</div>
                                        <div class="prodi">{{ $mhs->ProgramStudi->nama_prodi }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @empty
                    <p class="text-center">Jadilah yang pertama memberikan testimoni!</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Bagian Statistik Dinamis --}}
    {{-- <div class="container">
    <h2 class="mb-4">Daftar Mahasiswa</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Program Studi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswa as $mhs)
            <tr>
                <td>{{ $mhs->nim }}</td>
                <td>{{ $mhs->nama }}</td>
                <td>{{ $mhs->prodi->nama ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $mahasiswa->links() }}
</div> --}}

    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; {{ date('Y') }} Sistem Akademik UNIMAL. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    
</body>
</html>

{{-- vggjhgjhjg --}}
