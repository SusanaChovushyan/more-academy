<!DOCTYPE html>
<html lang="hy" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Մուտք — More Academy Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-ink">
    <div class="grid min-h-screen lg:grid-cols-2">
        {{-- Brand panel --}}
        <div class="relative hidden overflow-hidden lg:block">
            <x-ui.code-texture class="p-8" />
            <div class="relative flex h-full flex-col justify-between p-12 text-cream">
                <a href="{{ route('home') }}" class="inline-block font-mono text-lg font-bold transition hover:text-brand-yellow">[MORE ACADEMY]</a>
                <p class="type-display text-5xl">Learn more.<br><span class="text-brand-yellow">Create more.</span></p>
                <x-ui.line-art name="head" class="h-48 w-full" />
            </div>
        </div>

        {{-- Form --}}
        <main class="flex items-center justify-center bg-cream px-4 py-12 sm:px-8">
            <div class="w-full max-w-md">
                <a href="{{ route('home') }}" class="inline-block font-mono text-lg font-bold text-ink transition hover:text-brand-yellow lg:hidden">[MORE ACADEMY]</a>
                <h1 class="mt-8 text-3xl font-bold tracking-tight lg:mt-0">Մուտք կառավարման վահանակ</h1>
                <p class="mt-2 text-muted">Մուտքագրեք ձեր ադմինիստրատորի տվյալները։</p>

                <form method="POST" action="{{ route('admin.login.store') }}" class="mt-8 space-y-5" x-data="submitOnce" x-on:submit="submit($event)">
                    @csrf

                    <x-ui.field variant="admin" name="email" type="email" label="Էլ․ փոստ" required autofocus autocomplete="username" />
                    <x-ui.field variant="admin" name="password" type="password" label="Գաղտնաբառ" required autocomplete="current-password" />

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="remember" value="1" class="size-4 accent-ink" @checked(old('remember'))>
                        Հիշել ինձ
                    </label>

                    <button type="submit" x-bind:disabled="submitting"
                            class="w-full rounded-lg bg-ink px-4 py-3 font-mono text-sm font-bold uppercase tracking-wide text-cream transition hover:bg-ink-soft focus-visible:ring-4 focus-visible:ring-brand-yellow disabled:opacity-60">
                        Մուտք
                    </button>
                </form>

                <a href="{{ route('home') }}" class="mt-8 inline-block text-sm text-muted hover:text-ink">← Վերադառնալ կայքին</a>
            </div>
        </main>
    </div>
</body>
</html>
