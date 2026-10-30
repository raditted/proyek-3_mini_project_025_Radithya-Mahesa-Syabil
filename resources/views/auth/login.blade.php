<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — MBG Pelaporan</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-base-200 flex items-center justify-center">

<div class="card w-full max-w-sm border border-slate-500 bg-base-100">
    <div class="card-body">
        <h1 class="card-title text-2xl justify-center mb-2">MBG Pelaporan</h1>
        <p class="text-center text-sm text-base-content/60 mb-4">Sistem Pelaporan Keamanan Makanan</p>

        @if ($errors->any())
            <div class="alert alert-error mb-4">
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="flex flex-col">
            @csrf

            <label class="form-control mb-6">
                <div class="label"><span class="label-text">Email</span></div>
                <input type="email" name="email" value="{{ old('email') }}" 
                    class="input input-bordered w-full @error('email') input-error @enderror" 
                    placeholder="email@example.com" required autofocus>
            </label>

            <label class="form-control mb-6">
                <div class="label"><span class="label-text">Password</span></div>
                <input type="password" name="password"
                    class="input input-bordered w-full"
                    placeholder="••••••••" required>
            </label>

            <div class="form-control mb-4">
                <label class="label cursor-pointer justify-start gap-3">
                    <input type="checkbox" name="remember" class="checkbox checkbox-sm">
                    <span class="label-text">Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="btn btn-info w-full">Masuk</button>
        </form>
    </div>
</div>

</body>
</html>