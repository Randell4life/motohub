<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>მომხმარებლის რედაქტირება | MotoHub</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #0b1120; color: #eef3ff; font-family: Arial, sans-serif; }
        .container { max-width: 760px; margin: auto; padding: 32px 20px; }
        .panel { margin-top: 24px; padding: 24px; border: 1px solid #24334d; border-radius: 16px; background: #111b2e; }
        .form { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        label { display: block; margin-bottom: 7px; }
        input, textarea { width: 100%; padding: 12px; border: 1px solid #34445f; border-radius: 9px; background: #0b1120; color: white; }
        textarea { min-height: 100px; }
        .full { grid-column: 1 / -1; }
        button, a { display: inline-block; padding: 11px 16px; border: 0; border-radius: 9px; text-decoration: none; cursor: pointer; font-weight: bold; }
        button { background: #1687ff; color: white; }
        a { background: #24334d; color: white; }
        .error { color: #ff9999; }
        @media(max-width: 600px) { .form { grid-template-columns: 1fr; } .full { grid-column: auto; } }
    </style>
</head>
<body>
<div class="container">
    <a href="{{ route('workshop.customers.index') }}">← უკან დაბრუნება</a>

    <section class="panel">
        <h1>მომხმარებლის რედაქტირება</h1>

        <form method="POST" action="{{ route('workshop.customers.update', $customer->id) }}" class="form">
            @csrf
            @method('PUT')

            <div>
                <label for="name">სახელი და გვარი *</label>
                <input id="name" name="name" value="{{ old('name', $customer->name) }}" required maxlength="150">
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="phone">ტელეფონი *</label>
                <input id="phone" name="phone" value="{{ old('phone', $customer->phone) }}" required maxlength="30">
                @error('phone') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="email">ელფოსტა</label>
                <input id="email" type="email" name="email" value="{{ old('email', $customer->email) }}">
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="address">მისამართი</label>
                <input id="address" name="address" value="{{ old('address', $customer->address) }}">
                @error('address') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="full">
                <label for="notes">შენიშვნა</label>
                <textarea id="notes" name="notes">{{ old('notes', $customer->notes) }}</textarea>
                @error('notes') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="full">
                <button type="submit">ცვლილებების შენახვა</button>
            </div>
        </form>
    </section>
</div>
</body>
</html>
