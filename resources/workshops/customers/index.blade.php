<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>მომხმარებლები | MotoHub</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #0b1120;
            color: #eef3ff;
        }
        .container { max-width: 1100px; margin: auto; padding: 32px 20px; }
        .top { display: flex; justify-content: space-between; gap: 16px; align-items: center; flex-wrap: wrap; }
        h1 { margin-bottom: 6px; }
        .muted { color: #94a3b8; }
        .panel {
            background: #111b2e;
            border: 1px solid #24334d;
            border-radius: 16px;
            padding: 22px;
            margin-top: 24px;
        }
        .form { display: grid; grid-template-columns: repeat(2, 1fr); gap: 14px; }
        input, textarea {
            width: 100%; padding: 12px; border-radius: 9px;
            border: 1px solid #34445f; background: #0b1120; color: white;
        }
        textarea { min-height: 90px; resize: vertical; }
        .full { grid-column: 1 / -1; }
        button, .btn {
            display: inline-block; border: 0; border-radius: 9px;
            padding: 11px 16px; background: #1687ff; color: white;
            text-decoration: none; font-weight: bold; cursor: pointer;
        }
        .btn-secondary { background: #24334d; }
        .success { background: #123d32; padding: 12px; border-radius: 8px; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; min-width: 600px; }
        th, td { text-align: left; padding: 14px 10px; border-bottom: 1px solid #24334d; }
        th { color: #94a3b8; font-size: 13px; }
        .empty { text-align: center; padding: 28px; color: #94a3b8; }
        @media (max-width: 600px) {
            .form { grid-template-columns: 1fr; }
            .full { grid-column: auto; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="top">
        <div>
            <div class="muted">MOTOHUB / WORKSHOP</div>
            <h1>მომხმარებლები</h1>
            <div class="muted">{{ $partner->name }}</div>
        </div>
        <a class="btn btn-secondary" href="{{ route('workshop.dashboard') }}">← პანელზე დაბრუნება</a>
    </div>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <section class="panel">
        <h2>ახალი მომხმარებელი</h2>
        <p class="muted">შეავსე მომხმარებლის საკონტაქტო ინფორმაცია.</p>

        <form method="POST" action="{{ route('workshop.customers.store') }}" class="form">
            @csrf

            <div>
                <label for="name">სახელი და გვარი *</label>
                <input id="name" name="name" value="{{ old('name') }}" required maxlength="150">
                @error('name') <small>{{ $message }}</small> @enderror
            </div>

            <div>
                <label for="phone">ტელეფონი *</label>
                <input id="phone" name="phone" value="{{ old('phone') }}" required maxlength="30">
                @error('phone') <small>{{ $message }}</small> @enderror
            </div>

            <div>
                <label for="email">ელფოსტა</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}">
                @error('email') <small>{{ $message }}</small> @enderror
            </div>

            <div>
                <label for="address">მისამართი</label>
                <input id="address" name="address" value="{{ old('address') }}">
                @error('address') <small>{{ $message }}</small> @enderror
            </div>

            <div class="full">
                <label for="notes">შენიშვნა</label>
                <textarea id="notes" name="notes">{{ old('notes') }}</textarea>
                @error('notes') <small>{{ $message }}</small> @enderror
            </div>

            <div class="full">
                <button type="submit">+ მომხმარებლის დამატება</button>
            </div>
        </form>
    </section>

    <section class="panel">
        <h2>მომხმარებლების სია</h2>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>სახელი</th>
                    <th>ტელეფონი</th>
                    <th>ელფოსტა</th>
                    <th>მოქმედებები</th>
                    <th>დამატების თარიღი</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($customers as $customer)
                    <tr>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->phone }}</td>
                        <td>{{ $customer->email ?: '—' }}</td>
                        <td>{{ $customer->created_at->format('d.m.Y') }}</td>

<td>
    <a class="btn btn-secondary"
       href="{{ route('workshop.customers.edit', $customer->id) }}">
        რედაქტირება
    </a>

    <form method="POST"
          action="{{ route('workshop.customers.destroy', $customer->id) }}"
          style="display:inline"
          onsubmit="return confirm('ნამდვილად გსურთ მომხმარებლის წაშლა?')">
        @csrf
        @method('DELETE')
        <button type="submit"
                style="background:#dc3545;color:white;border:0;padding:10px;border-radius:8px;cursor:pointer">
            წაშლა
        </button>
    </form>
</td>
                    </tr>
                    <tr>
    <td colspan="5" style="background:#0b1120;padding:18px;">
        <strong>მოტოციკლები</strong>

        @forelse ($customer->motorcycles as $motorcycle)
            <div style="margin:12px 0;padding:12px;background:#111b2e;border-radius:8px;">
                <span>
                    {{ $motorcycle->title ?: trim(($motorcycle->brand ?? '') . ' ' . ($motorcycle->model ?? '')) }}
                    — {{ $motorcycle->year ?? '—' }}
                    <a class="btn"
   style="margin:8px 0;"
   href="{{ route('workshop.customers.motorcycles.service.index', [
       'customer' => $customer->id,
       'motorcycle' => $motorcycle->id
   ]) }}">
    მომსახურების ისტორია
</a>
                </span>

                <form method="POST"
                      action="{{ route('workshop.customers.motorcycles.detach', [$customer->id, $motorcycle->id]) }}"
                      style="display:inline;margin-left:10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:#dc3545;">
                        მიბმის მოხსნა
                    </button>
                </form>
            </div>
        @empty
            <p class="muted">ამ კლიენტზე მოტოციკლი ჯერ მიბმული არ არის.</p>
        @endforelse

        @if ($availableMotorcycles->isNotEmpty())
            <form method="POST"
                  action="{{ route('workshop.customers.motorcycles.attach', $customer->id) }}"
                  style="display:flex;gap:10px;flex-wrap:wrap;margin-top:12px;">
                @csrf

                <select name="motorcycle_id" required
                        style="padding:11px;border-radius:8px;background:#0b1120;color:white;border:1px solid #34445f;min-width:240px;">
                    <option value="">აირჩიე მოტოციკლი</option>

                    @foreach ($availableMotorcycles as $motorcycle)
                        <option value="{{ $motorcycle->id }}">
                            {{ $motorcycle->title ?: trim(($motorcycle->brand ?? '') . ' ' . ($motorcycle->model ?? '')) }}
                            — {{ $motorcycle->year ?? '—' }}
                        </option>
                    @endforeach
                </select>

                <button type="submit">კლიენტზე მიბმა</button>
            </form>
        @else
            <p class="muted">ამ სახელოსნოსთვის თავისუფალი მოტოციკლები არ არის.</p>
        @endif
    </td>
</tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty">ჯერ მომხმარებელი არ დაგიმატებია.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $customers->links() }}
    </section>
</div>
</body>
</html>
