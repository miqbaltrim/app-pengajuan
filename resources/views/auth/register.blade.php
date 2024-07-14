<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <style>
        /* CSS styles seperti sebelumnya */
    </style>
</head>
<body>
    <div class="main">     
        <input type="checkbox" id="chk" aria-hidden="true">

        <div class="login">
            <form method="POST" action="{{ route('login') }}" id="login-form">
                @csrf
                <label for="chk" aria-hidden="true">Login</label>
                <input type="email" name="email" placeholder="Email" required="">
                <input type="password" name="password" placeholder="Password" required="">
                <button type="submit">Login</button>
            </form>
        </div>

        <div class="signup">
            <form method="POST" action="{{ route('register') }}" id="signup-form">
                @csrf
                <label for="chk" aria-hidden="true">Sign up</label>
                <input type="text" name="nama" placeholder="User name" required="">
                <input type="text" name="Role" placeholder="Role" required="">
                <input type="email" name="email" placeholder="Email" required="">
                <input type="password" name="password" placeholder="Password" required="">
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required="">
                <button type="submit">Sign up</button>
            </form>
        </div>

        
    </div>

    <!-- Script JavaScript untuk menampilkan popup -->
    <script>
        // Ambil pesan sukses dari session flash
        let successMessage = "{{ session('success') }}";
        // Jika ada pesan sukses, tampilkan popup
        if (successMessage) {
            alert(successMessage);
        }

        // Tambahkan event listener untuk form pendaftaran
        document.getElementById("signup-form").addEventListener("submit", function(event) {
            // Tunda pengiriman formulir agar popup berhasil terlihat
            event.preventDefault();
            // Tampilkan popup berhasil
            alert("Berhasil!!!. Silakan Register untuk melanjutkan.");
            // Redirect ke halaman login setelah menutup popup
            window.location.href = "{{ route('register') }}";
        });
        document.getElementById("login-form").addEventListener("submit", function(event) {
            // Tunda pengiriman formulir agar popup berhasil terlihat
            event.preventDefault();
            // Tampilkan popup berhasil
            alert("Berhasil!!!. Silakan login untuk melanjutkan.");
            // Redirect ke halaman login setelah menutup popup
            window.location.href = "{{ route('login') }}";
        });
    </script>
</body>
</html>
