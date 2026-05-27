<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | GadgetHub</title>

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
        <div class="hidden md:flex flex-col justify-center bg-gradient-to-br from-blue-600 to-cyan-400 p-12 text-white">
            <h1 class="text-5xl font-bold leading-tight">
                GadgetHub
            </h1>

            <p class="mt-6 text-lg text-blue-100">
                Belanja gadget modern dengan pengalaman e-commerce yang cepat, simpel, dan elegan.
            </p>

            <div class="mt-10 space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 bg-white rounded-full"></div>
                    <p>Smartphone & Accessories</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 bg-white rounded-full"></div>
                    <p>Gaming Gear Premium</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 bg-white rounded-full"></div>
                    <p>Fast Delivery & Secure Payment</p>
                </div>
            </div>
        </div>

        <!-- RIGHT -->
        <div class="glass p-10 md:p-14 text-white">

            <div class="mb-10">
                <h2 class="text-4xl font-bold">
                    Create Account
                </h2>

                <p class="text-slate-400 mt-2">
                    Join GadgetHub and start shopping today.
                </p>
            </div>

            <form method="POST" action="/register" class="space-y-6">
                @csrf

                <div>
                    <label class="block mb-2 text-sm text-slate-300">
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter your name"
                        class="w-full px-5 py-4 rounded-2xl bg-slate-900 border border-slate-700 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                    >
                </div>

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
                        placeholder="Create password"
                        class="w-full px-5 py-4 rounded-2xl bg-slate-900 border border-slate-700 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                    >
                </div>

                <!-- ROLE -->
                <div>
                    <label class="block mb-2 text-sm text-slate-300">
                        Role
                    </label>

                    <select
                        name="role"
                        class="w-full px-5 py-4 rounded-2xl bg-slate-900 border border-slate-700 focus:outline-none focus:ring-2 focus:ring-cyan-400 text-white"
                    >
                        <option value="">Select Role</option>
                        <option value="customer">Customer</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <button
                    type="submit"
                    class="w-full py-4 rounded-2xl bg-cyan-400 hover:bg-cyan-300 text-slate-900 font-bold transition duration-300"
                >
                    Register
                </button>
            </form>

            <p class="mt-8 text-center text-slate-400">
                Already have an account?
                <a href="/login" class="text-cyan-400 hover:underline">
                    Login
                </a>
            </p>

        </div>
    </div>

</body>
</html>