<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Jost', sans-serif;
            background: linear-gradient(to bottom, #0f0c29, #302b63, #24243e);
        }
        
        .main {
            width: 350px;
            height: 500px;
            background: red;
            overflow: hidden;
            background: url("{{ asset('img/Logo-ABJ.png') }}") no-repeat center/ cover;
            border-radius: 10px;
            box-shadow: 5px 20px 50px #000;
            position: relative; /* Tambahkan properti posisi */
        }

        /* Tambahkan gaya untuk logo */
        .logo {
            position: absolute; /* Letakkan secara absolut */
            top: 20px; /* Atur posisi dari atas */
            left: 50%; /* Geser ke kiri sejauh 50% dari kotak induk */
            transform: translateX(-50%); /* Geser kembali ke kiri sejauh 50% dari lebar logo */
            width: 100px; /* Lebar logo */
            height: auto; /* Tinggi otomatis sesuai aspek rasio */
        }
        
        #chk {
            display: none;
        }
        
        .signup {
            position: relative;
            width:100%;
            height: 100%;
            margin-top: 80px; /* Tambahkan jarak atas */
        }
        
        label {
            color: #fff;
            font-size: 2.3em;
            justify-content: center;
            display: flex;
            margin: 20px 0; /* Ubah margin atas dan bawah */
            font-weight: bold;
            cursor: pointer;
            transition: .5s ease-in-out;
        }
        
        input {
            width: 60%;
            height: 20px;
            background: #e0dede;
            justify-content: center;
            display: flex;
            margin: 10px auto; /* Ubah margin atas dan bawah */
            padding: 10px;
            border: none;
            outline: none;
            border-radius: 5px;
        }
        
        button {
            width: 60%;
            height: 40px;
            margin: 10px auto;
            justify-content: center;
            display: block;
            color: #fff;
            background: #573b8a;
            font-size: 1em;
            font-weight: bold;
            margin-top: 20px;
            outline: none;
            border: none;
            border-radius: 5px;
            transition: .2s ease-in;
            cursor: pointer;
        }
        
        button:hover {
            background: #6d44b8;
        }
        
        .login {
            height: 460px;
            background: #eee;
            border-radius: 60% / 10%;
            transform: translateY(-180px);
            transition: .8s ease-in-out;
        }
        
        .login label {
            color: #573b8a;
            transform: scale(.6);
        }
        
        #chk:checked ~ .login {
            transform: translateY(-500px);
        }
        
        #chk:checked ~ .login label {
            transform: scale(1); 
        }
        
        #chk:checked ~ .signup label {
            transform: scale(.6);
        }

        /* Tambahkan gaya untuk tulisan di bawah logo */
        .app-name {
            color: #fff;
            text-align: center;
            font-size: 0.8em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="main">     
        <img class="logo" src="{{ asset('assets/img/Logo-ABJ.png') }}" alt="Logo Aplikasi"> <!-- Ganti "link_ke_logo_anda.png" dengan URL logo Anda -->
        <div class="app-name">Aplikasi Pengajuan PT Ayumida Berkah Jaya</div>
        <input type="checkbox" id="chk" aria-hidden="true">

        <div class="signup">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <label for="chk" aria-hidden="true">Login</label>
                <input type="email" name="email" placeholder="Email" required="">
                <input type="password" name="password" placeholder="Password" required="">
                <button type="submit">Login</button>
            </form>
        </div>

        <div class="login">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <label for="chk" aria-hidden="true">Signup</label>
                <input type="text" name="nama" placeholder="User nama" required="">
                <input type="email" name="email" placeholder="Email" required="">
                <input type="password" name="password" placeholder="Password" required="">
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required="">
                <button type="submit">Sign up</button>
                
            </form>
        </div>
    </div>

    <!-- Tambahkan tautan ke SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <!-- Script JavaScript untuk menampilkan SweetAlert2 -->
    <script>
        // Ambil pesan sukses dari session flash
        let successMessage = "{{ session('success') }}";
        // Jika ada pesan sukses, tampilkan SweetAlert2
        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!!!',
                text: successMessage
            });
        }
    </script>
</body>
</html>
