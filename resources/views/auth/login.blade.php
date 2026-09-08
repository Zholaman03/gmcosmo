<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Вход</title>
	<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center px-4 py-12">
	<main class="w-full max-w-md">
		<section class="bg-white rounded-2xl shadow-xl p-8 sm:p-10">
			<div class="text-center mb-8">
				<h1 class="text-3xl font-bold text-slate-900">Вход в аккаунт</h1>
				<p class="mt-2 text-sm text-slate-500">Введите данные для входа</p>
			</div>

			<form method="POST" action="{{ route('login') }}" class="space-y-6">
				@csrf

				<div>
					<label for="email" class="block text-sm font-medium text-slate-700">Email</label>
					<input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
						class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 @error('email') border-red-500 @enderror"
						placeholder="you@example.com">
					@error('email')
						<p class="mt-2 text-sm text-red-600">{{ $message }}</p>
					@enderror
				</div>

				<div>
					<div class="flex items-center justify-between">
						<label for="password" class="block text-sm font-medium text-slate-700">Пароль</label>
						@if (Route::has('password.request'))
							<a href="{{ route('password.request') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500">Забыли пароль?</a>
						@endif
					</div>
					<input id="password" name="password" type="password" required
						class="mt-2 block w-full rounded-lg border border-slate-300 px-4 py-3 text-slate-900 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 @error('password') border-red-500 @enderror"
						placeholder="Введите пароль">
					@error('password')
						<p class="mt-2 text-sm text-red-600">{{ $message }}</p>
					@enderror
				</div>

				<label class="flex items-center gap-2 text-sm text-slate-600">
					<input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
					Запомнить меня
				</label>

				<button type="submit" class="w-full rounded-lg bg-indigo-600 px-4 py-3 font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
					Войти
				</button>
			</form>

			@if (Route::has('register'))
				<p class="mt-8 text-center text-sm text-slate-600">
					Нет аккаунта?
					<a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Зарегистрироваться</a>
				</p>
			@endif
		</section>
	</main>
</body>
</html>
