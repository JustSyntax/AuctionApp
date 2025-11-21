<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Drive Auction</title>

{{-- 1. GANTI CDN DENGAN FILE LOKAL --}}
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/montserrat.css') }}" rel="stylesheet">

<style>
html { scroll-behavior: smooth; }
body {
    margin: 0; padding: 0; background: #000; color: white; font-family: 'Montserrat', sans-serif;
    scrollbar-width: none; -ms-overflow-style: none; overflow-x: hidden;
}
html::-webkit-scrollbar, body::-webkit-scrollbar { display: none; }

/* HERO */
.hero {
    position: relative; width: 100%; height: 100vh;
    background: #000; display: flex; justify-content: center; align-items: center; text-align: center;
}
.hero-text {
    position: absolute; top: 25%; z-index: 2; font-size: 85px; font-weight: 700; letter-spacing: 10px; opacity: 0.35;
}
.top-nav {
    position: absolute; top: 40px; left: 0; right: 0; width: 100%; padding: 0 70px;
    display: flex; justify-content: space-between; z-index: 3; font-size: 16px; letter-spacing: 3px;
}
.login-btn { color: white; transition: .3s; text-decoration: none; }
.login-btn:hover { opacity: .6; }

.car-container {
    position: absolute; bottom: 0; width: 100%; text-align: center; z-index: 2;
}
.car-img {
    width: 50%; margin-bottom: -40px;
    filter: brightness(0.55) drop-shadow(0px 20px 40px rgba(0,0,0,0.95));
}
.road-light {
    position: absolute; bottom: 0; width: 100%; height: 140px;
    background: radial-gradient(circle, rgba(255,255,255,0.22) 0%, rgba(0,0,0,0) 70%); z-index: 1;
}

/* LOGIN / REGISTER */
#login { height: 100vh; background: #0f0f0f; display: flex; justify-content: center; align-items: center; }
.auth-card {
    width: 80%; max-width: 1100px; background: white; color: black; border-radius: 20px;
    overflow: hidden; display: flex; box-shadow: 0 0 40px rgba(255,255,255,0.04);
}
.auth-left {
    width: 45%; padding: 50px;
    background: linear-gradient(160deg, #0d0d0d, #1c1f2e 60%, #2a0a15);
    color: white; display: flex; flex-direction: column; justify-content: center;
}
.auth-left h1 { font-weight: 700; font-size: 42px; letter-spacing: 4px; }
.auth-left p { margin-top: 15px; opacity: .75; font-size: 15px; font-weight: 400; line-height: 1.6; }

.auth-right { width: 55%; padding: 50px; }

h3 { font-weight: 600; margin-bottom: 20px; }
label { font-weight: 500; }

.toggle-link { color: #2a53ff; font-weight: 600; text-decoration: none; }
.toggle-link:hover { text-decoration: underline; }

.btn-dark { margin-top: 12px; background: #111; border: none; padding: 10px 0; font-size: 16px; font-weight: 600; }
.input-group-text { cursor: pointer; }

.text-error {
    color: red; font-size: 13px; display: none; margin-top: -8px; margin-bottom: 8px;
}

/* Hilangkan panah di input number */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
  -webkit-appearance: none; margin: 0; 
}
</style>
</head>

<body>
<div class="top-nav">
    <div>DRIVE AUCTION</div>
    <a href="#login" class="login-btn">LOGIN</a>
</div>

<section class="hero">
    <div class="hero-text">DRIVE AUCTION</div>
    <div class="car-container">
        <img src="{{ asset('assets/image/bcar.png') }}" class="car-img" alt="car" onerror="this.style.display='none'"> 
        {{-- Pastikan gambar bcar.png ada di public/assets/image/ atau ganti path-nya --}}
    </div>
    <div class="road-light"></div>
</section>

<section id="login">
<div class="auth-card">
    <div class="auth-left">
        <h1>DRIVE AUCTION</h1>
        <p>
            Platform lelang mobil terpercaya — cepat, aman, dan transparan.<br>
            Bergabung sekarang dan mulai pengalaman baru membeli mobil dengan cara modern.
        </p>
    </div>

    <div class="auth-right">

        <!-- LOGIN FORM -->
        <div id="formLogin">
            <h3>Login</h3>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" style="font-size: 14px;">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form id="loginForm" method="POST" action="{{ route('login.post') }}">
                @csrf
                <label>Username / NIK</label>
                <input type="text" name="username" class="form-control mb-1" id="loginUsername" value="{{ old('username') }}" required>
                <div class="text-error" id="loginUsernameError">
                    @if(session('error') === 'Username / NIK tidak valid')
                        Username / NIK tidak valid
                    @elseif(session('error') && !old('username'))
                        Username / NIK tidak boleh kosong
                    @endif
                </div>

                <label>Password</label>
                <div class="input-group mb-1">
                    <input type="password" name="password" class="form-control" id="loginPassword" required>
                    <span class="input-group-text" onclick="togglePassword('loginPassword')">
                        <i class="bi bi-eye" id="loginEye"></i>
                    </span>
                </div>
                <div class="text-error" id="loginPasswordError">
                    @if(session('error') === 'Password salah')
                        Password salah
                    @elseif(session('error') && !old('password'))
                        Password tidak boleh kosong
                    @endif
                </div>

                <button type="submit" class="btn btn-dark w-100">Login</button>

                <p class="mt-3 text-center">
                    Don't have an account?
                    <a href="javascript:void(0)" class="toggle-link" onclick="showRegister(event)">Sign up</a>
                </p>
            </form>
        </div>

        <!-- REGISTER FORM -->
        <div id="formRegister" style="display:none;">
            <h3>Register</h3>

            <form id="registerForm" method="POST" action="{{ route('register.post') }}">
                @csrf

                <label>NIK</label>
                <input type="number" name="nik" class="form-control mb-1" id="nik" value="{{ old('nik') }}" 
                       oninput="if(this.value.length > 16) this.value = this.value.slice(0, 16);"
                       onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                       required>
                <div class="text-error" id="nikError" style="display: {{ $errors->has('nik') ? 'block' : 'none' }}">
                    @error('nik') {{ $message }} @else NIK harus 16 digit @enderror
                </div>

                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control mb-1" id="nama" value="{{ old('nama_lengkap') }}" required>
                <div class="text-error" id="namaError">Nama tidak boleh kosong</div>

                <label>Username</label>
                <input type="text" name="username" class="form-control mb-1" id="username" value="{{ old('username') }}" required>
                <div class="text-error" id="usernameError" style="display: {{ $errors->has('username') ? 'block' : 'none' }}">
                    @error('username') {{ $message }} @else Username tidak boleh kosong @enderror
                </div>

                <label>Password</label>
                <div class="input-group mb-1">
                    <input type="password" name="password" class="form-control" id="registerPassword" required>
                    <span class="input-group-text" onclick="togglePassword('registerPassword')">
                        <i class="bi bi-eye" id="registerEye"></i>
                    </span>
                </div>
                <div class="text-error" id="registerPasswordError" style="display: {{ $errors->has('password') ? 'block' : 'none' }}">
                    @error('password') {{ $message }} @else Password minimal 8 karakter @enderror
                </div>

                <label>Confirm Password</label>
                <div class="input-group mb-1">
                    <input type="password" name="password_confirmation" class="form-control" id="confirmPassword" required>
                    <span class="input-group-text" onclick="togglePassword('confirmPassword')">
                        <i class="bi bi-eye" id="confirmEye"></i>
                    </span>
                </div>
                <div class="text-error" id="confirmPasswordError">Password tidak cocok</div>

                <label>No Telp</label>
                <input type="number" name="telp" class="form-control mb-1" id="telp" value="{{ old('telp') }}"
                       oninput="if(this.value.length > 13) this.value = this.value.slice(0, 13);"
                       onkeypress="return event.charCode >= 48 && event.charCode <= 57"
                       required>
                <div class="text-error" id="telpError">No Telp tidak boleh kosong</div>

                <button type="submit" class="btn btn-dark w-100">Register</button>

                <p class="mt-3 text-center">
                    Already have an account?
                    <a href="javascript:void(0)" class="toggle-link" onclick="showLogin(event)">Login</a>
                </p>
            </form>
        </div>

    </div>
</div>
</section>

<script>
function resetForms() {
    const errors = document.querySelectorAll('.text-error');
    errors.forEach(function(el) {
        el.style.display = 'none';
    });

    document.getElementById('loginPassword').type = "password";
    document.getElementById('registerPassword').type = "password";
    document.getElementById('confirmPassword').type = "password";
    
    document.querySelectorAll('.bi-eye-slash').forEach(icon => {
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    });
}

function showRegister(event) {
    if(event) event.preventDefault();
    resetForms();
    document.getElementById("formLogin").style.display = "none";
    document.getElementById("formRegister").style.display = "block";
}

function showLogin(event) {
    if(event) event.preventDefault();
    resetForms();
    document.getElementById("formLogin").style.display = "block";
    document.getElementById("formRegister").style.display = "none";
}

function togglePassword(id) {
    const field = document.getElementById(id);
    const icon = document.querySelector(`#${id} ~ .input-group-text i`);
    if(field.type === "password") {
        field.type = "text";
        icon.classList.replace('bi-eye','bi-eye-slash');
    } else {
        field.type = "password";
        icon.classList.replace('bi-eye-slash','bi-eye');
    }
}

// REGISTER VALIDATION
document.getElementById('registerForm').addEventListener('submit', function(e){
    let valid = true;
    const nik = document.getElementById('nik');
    const nama = document.getElementById('nama');
    const username = document.getElementById('username');
    const password = document.getElementById('registerPassword');
    const confirm = document.getElementById('confirmPassword');
    const telp = document.getElementById('telp');

    document.getElementById('nikError').style.display = 'none';
    
    if(nik.value.trim().length !== 16) {
        const nikErr = document.getElementById('nikError');
        if(nikErr.textContent.trim() === '') nikErr.textContent = "NIK harus 16 digit";
        else if(!nikErr.textContent.includes('terdaftar')) nikErr.textContent = "NIK harus 16 digit"; 
        
        nikErr.style.display = 'block';
        valid = false;
    } 
    if(!nama.value.trim()) {
        document.getElementById('namaError').style.display = 'block'; valid = false;
    }
    if(!username.value.trim()) {
        document.getElementById('usernameError').textContent = "Username tidak boleh kosong";
        document.getElementById('usernameError').style.display = 'block'; valid = false;
    }
    if(password.value.length < 8) {
        document.getElementById('registerPasswordError').textContent = "Password minimal 8 karakter";
        document.getElementById('registerPasswordError').style.display = 'block'; valid = false;
    }
    if(password.value !== confirm.value) {
        document.getElementById('confirmPasswordError').style.display = 'block'; valid = false;
    }
    if(!telp.value.trim()) {
        document.getElementById('telpError').style.display = 'block'; valid = false;
    }

    if(!valid) e.preventDefault();
});

document.getElementById('loginForm').addEventListener('submit', function(e){
    let valid = true;
    const username = document.getElementById('loginUsername');
    const password = document.getElementById('loginPassword');

    if(!username.value.trim()) {
        document.getElementById('loginUsernameError').style.display = 'block'; valid = false;
    } else {
        document.getElementById('loginUsernameError').style.display = 'none';
    }

    if(!password.value) {
        document.getElementById('loginPasswordError').style.display = 'block'; valid = false;
    } else {
        document.getElementById('loginPasswordError').style.display = 'none';
    }

    if(!valid) e.preventDefault();
});

@if(session('login_error'))
    document.addEventListener('DOMContentLoaded', function(){
        const err = "{{ session('login_error') }}";
        
        if(err === 'username') {
            document.getElementById('loginUsernameError').textContent = "Username / NIK tidak valid";
            document.getElementById('loginUsernameError').style.display = 'block';
        }
        if(err === 'password') {
            document.getElementById('loginPasswordError').textContent = "Password salah";
            document.getElementById('loginPasswordError').style.display = 'block';
        }
        if(err === 'blocked') {
            document.getElementById('loginUsernameError').textContent = "Akun Anda telah diblokir. Hubungi Admin.";
            document.getElementById('loginUsernameError').style.display = 'block';
            document.getElementById('loginUsername').classList.add('is-invalid');
        }

        document.getElementById("formLogin").style.display = "block";
        document.getElementById("formRegister").style.display = "none";
        document.getElementById('login').scrollIntoView({ behavior: 'smooth' });
    });
@endif

@if($errors->any())
    document.addEventListener('DOMContentLoaded', function(){
        document.getElementById("formLogin").style.display = "none";
        document.getElementById("formRegister").style.display = "block";
        document.getElementById('login').scrollIntoView({ behavior: 'smooth' });
    });
@endif

@if(session('success'))
    document.addEventListener('DOMContentLoaded', function(){
        document.getElementById('loginForm').reset();
        document.getElementById('registerForm').reset();
        document.getElementById("formLogin").style.display = "block";
        document.getElementById("formRegister").style.display = "none";
        document.getElementById('login').scrollIntoView({ behavior: 'smooth' });
    });
@endif
</script>

{{-- 2. GANTI JS CDN DENGAN FILE LOKAL --}}
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

</body>
</html>