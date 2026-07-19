@php
    $regErrors = $errors->getBag('register');
    $openAuthTab = null;
    if (session('open_auth')) {
        $openAuthTab = session('open_auth');
    } elseif ($regErrors->any() || old('birth_date') || old('address')) {
        $openAuthTab = 'register';
    } elseif ($errors->any()) {
        $openAuthTab = 'login';
    }
@endphp

{{-- ===== POPUP LOGIN / DAFTAR ===== --}}
<div id="popup-auth" class="hidden fixed inset-0 z-[999] flex items-center justify-center px-4 py-8 overflow-y-auto">
    <div class="absolute inset-0 bg-black/50" style="animation: overlayFadeIn 0.22s ease forwards" onclick="tutupPopupAuth()"></div>

    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 z-10 my-auto" style="animation: popupFadeIn 0.28s cubic-bezier(0.34, 1.46, 0.64, 1) forwards">
        <button type="button" onclick="tutupPopupAuth()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        {{-- Tabs --}}
        <div class="flex items-center gap-1 bg-gray-100 rounded-full p-1 mb-6 w-fit mx-auto">
            <button type="button" onclick="gantiTabAuth('login')" id="tab-btn-login"
                    class="px-5 py-1.5 rounded-full text-sm font-semibold transition">
                Masuk
            </button>
            <button type="button" onclick="gantiTabAuth('register')" id="tab-btn-register"
                    class="px-5 py-1.5 rounded-full text-sm font-semibold transition">
                Daftar
            </button>
        </div>

        {{-- ===== TAB: MASUK ===== --}}
        <div id="tab-login">
            <div class="text-center mb-6">
                <div class="w-14 h-14 rounded-full bg-[#2F4538]/10 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-[#2F4538]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="font-display text-xl font-bold text-gray-900">Selamat Datang Kembali!</h2>
                <p class="text-sm text-gray-500 mt-1">Masuk ke akun Gardenia kamu</p>
            </div>

            @if(session('status'))
                <p class="text-sm text-green-600 bg-green-50 rounded-lg px-4 py-2 mb-4 text-center">{{ session('status') }}</p>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="login-email" class="block text-sm font-semibold text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input id="login-email" name="email" type="email" value="{{ old('email') }}" placeholder="Masukkan email Anda"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('email') border-red-400 @enderror" />
                    @error('email')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="login-password" class="block text-sm font-semibold text-gray-700 mb-1">Kata Sandi <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input id="login-password" name="password" type="password" placeholder="Masukkan kata sandi"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2F4538] pr-10 @error('password') border-red-400 @enderror" />
                        <button type="button" onclick="togglePass('login-password')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <input id="login-remember" name="remember" type="checkbox" class="accent-[#2F4538] w-4 h-4" {{ old('remember') ? 'checked' : '' }} />
                        <label for="login-remember" class="text-sm text-gray-600">Ingat saya</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-xs text-[#2F4538] hover:underline">Lupa kata sandi?</a>
                </div>

                <button type="submit" class="w-full bg-[#2F4538] text-white font-bold text-sm py-3.5 rounded-xl hover:bg-[#26392E] transition mt-2">
                    Masuk
                </button>

                <p class="text-center text-sm text-gray-500 mt-4">
                    Belum punya akun?
                    <button type="button" onclick="gantiTabAuth('register')" class="text-[#2F4538] font-semibold hover:underline">Buat Akun</button>
                </p>
            </form>
        </div>

        {{-- ===== TAB: DAFTAR ===== --}}
        <div id="tab-register" class="hidden">
            <div class="text-center mb-6">
                <h2 class="font-display text-xl font-bold text-gray-900">Tinggal Selangkah Lagi!</h2>
                <p class="text-sm text-gray-500 mt-1">Lengkapi datamu dengan benar di bawah ini, ya!</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4 max-h-[65vh] overflow-y-auto pr-1">
                @csrf

                <div>
                    <label for="register-name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input id="register-name" name="name" type="text" value="{{ old('name') }}" placeholder="Masukkan Nama Lengkap Anda Sesuai KTP"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('name', 'register') border-red-400 @enderror" />
                    @error('name', 'register') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="register-birth_date" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input id="register-birth_date" name="birth_date" type="date" value="{{ old('birth_date') }}"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('birth_date', 'register') border-red-400 @enderror" />
                        @error('birth_date', 'register') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="register-phone" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon <span class="text-red-500">*</span></label>
                        <input id="register-phone" name="phone" type="tel" value="{{ old('phone') }}" placeholder="Nomor Telepon Aktif"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('phone', 'register') border-red-400 @enderror" />
                        @error('phone', 'register') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="register-email" class="block text-sm font-semibold text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                    <input id="register-email" name="email" type="email" value="{{ old('email') }}" placeholder="Masukkan Alamat Email Aktif"
                           class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('email', 'register') border-red-400 @enderror" />
                    @error('email', 'register') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="register-address" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea id="register-address" name="address" rows="2" placeholder="Masukkan Alamat Lengkap Anda"
                              class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2F4538] resize-none @error('address', 'register') border-red-400 @enderror">{{ old('address') }}</textarea>
                    @error('address', 'register') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="register-password" class="block text-sm font-semibold text-gray-700 mb-1">Kata Sandi <span class="text-red-500">*</span></label>
                        <input id="register-password" name="password" type="password" placeholder="Minimal 8 Karakter"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2F4538] @error('password', 'register') border-red-400 @enderror" />
                        @error('password', 'register') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="register-password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1">Konfirmasi Sandi <span class="text-red-500">*</span></label>
                        <input id="register-password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi Kata Sandi Anda"
                               class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#2F4538]" />
                    </div>
                </div>

                <button type="submit" class="w-full bg-[#2F4538] text-white font-bold text-sm py-3.5 rounded-xl hover:bg-[#26392E] transition mt-2">
                    Buat Akun
                </button>

                <p class="text-center text-sm text-gray-500 mt-4">
                    Sudah punya akun?
                    <button type="button" onclick="gantiTabAuth('login')" class="text-[#2F4538] font-semibold hover:underline">Masuk</button>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
    function bukaPopupAuth(tab = 'login') {
        document.getElementById('popup-auth').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        gantiTabAuth(tab);
    }

    function tutupPopupAuth() {
        document.getElementById('popup-auth').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function gantiTabAuth(tab) {
        const isLogin = tab === 'login';
        document.getElementById('tab-login').classList.toggle('hidden', !isLogin);
        document.getElementById('tab-register').classList.toggle('hidden', isLogin);

        const btnLogin = document.getElementById('tab-btn-login');
        const btnRegister = document.getElementById('tab-btn-register');
        btnLogin.className = 'px-5 py-1.5 rounded-full text-sm font-semibold transition ' + (isLogin ? 'bg-[#2F4538] text-white' : 'text-gray-600 hover:bg-gray-200');
        btnRegister.className = 'px-5 py-1.5 rounded-full text-sm font-semibold transition ' + (!isLogin ? 'bg-[#2F4538] text-white' : 'text-gray-600 hover:bg-gray-200');
    }

    function togglePass(id) {
        const inp = document.getElementById(id);
        inp.type = inp.type === 'password' ? 'text' : 'password';
    }

    @if($openAuthTab)
        document.addEventListener('DOMContentLoaded', function() {
            bukaPopupAuth('{{ $openAuthTab }}');
        });
    @else
        document.addEventListener('DOMContentLoaded', function() {
            gantiTabAuth('login');
        });
    @endif
</script>
