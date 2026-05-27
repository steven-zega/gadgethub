<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | GadgetHub</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #0f172a;
        }

        .glass {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.08);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-5xl grid md:grid-cols-2 rounded-3xl overflow-hidden shadow-2xl">

        <!-- LEFT -->
        <div class="hidden md:flex flex-col justify-center bg-gradient-to-br from-cyan-500 to-blue-700 p-12 text-white">
            <h1 class="text-5xl font-bold leading-tight">
                GadgetHub
            </h1>

            <p class="mt-6 text-lg text-cyan-100">
                Welcome back. Login to continue your gadget shopping experience.
            </p>

            <div class="mt-10 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 bg-white rounded-full"></div>
                    <p>Latest Technology Products</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 bg-white rounded-full"></div>
                    <p>Modern Clean Shopping Experience</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 bg-white rounded-full"></div>
                    <p>Fast & Secure Transactions</p>
                </div>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="glass p-10 md:p-14 text-white">

            <div class="mb-10">
                <h2 class="text-4xl font-bold">
                    Login Account
                </h2>

                <p class="text-slate-400 mt-2">
                    Sign in to access your GadgetHub account.
                </p>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-red-500/20 border border-red-500 text-red-200">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="/login" class="space-y-6">
                @csrf

                <div>
                    <label class="block mb-2 text-sm text-slate-300">
                        Email Address
                    </label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        class="w-full px-5 py-4 rounded-2xl bg-slate-900 border border-slate-700 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                    >
                </div>

                <div>
                    <label class="block mb-2 text-sm text-slate-300">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        class="w-full px-5 py-4 rounded-2xl bg-slate-900 border border-slate-700 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                    >
                </div>

                <button
                    type="submit"
                    class="w-full py-4 rounded-2xl bg-cyan-400 hover:bg-cyan-300 text-slate-900 font-bold transition duration-300"
                >
                    Login
                </button>
            </form>

            <p class="mt-8 text-center text-slate-400">
                Don't have an account?
                <a href="/register" class="text-cyan-400 hover:underline">
                    Register
                </a>
            </p>

        </div>
    </div>

</body>
</html>