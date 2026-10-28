<!DOCTYPE html>
<html lang="ka">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ჩემი ნაწილების განცხადებები | MotoHub</title>
    <style>
        *{box-sizing:border-box}body{margin:0;background:#070a12;color:#f4f8ff;font-family:Arial,sans-serif}
        a{color:inherit;text-decoration:none}.wrap{max-width:1150px;margin:auto;padding:32px 20px}
        .top{display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:28px}
        .logo{font-size:25px;font-weight:900;letter-spacing:1px}.logo span{color:#1677ff}
        h1{font-size:clamp(26px,4vw,38px);margin:0 0 8px}.muted{color:#98a8c2}
        .btn{display:inline-block;border:0;border-radius:8px;background:#1677ff;color:white;padding:12px 17px;font-weight:700;cursor:pointer}
        .btn:hover{background:#0865e8}.btn.secondary{background:#152239;border:1px solid #2a3b58}
        .notice{background:#10243b;border:1px solid #24476c;padding:14px;border-radius:10px;margin-bottom:18px}
        .tablebox{overflow-x:auto;background:#0d1422;border:1px solid #243653;border-radius:14px}
        table{width:100%;border-collapse:collapse;min-width:700px}th,td{padding:16px;text-align:left;border-bottom:1px solid #243653}
        th{color:#98a8c2;font-size:12px;text-transform:uppercase}td{font-size:14px}
        .status{display:inline-block;border-radius:30px;padding:6px 10px;background:#243047;font-size:12px}
        .published{background:#123c2d;color:#8bf0bf}.pending{background:#40351b;color:#ffdc80}.rejected{background:#46232a;color:#ff9aa8}
        .actions{display:flex;gap:8px;align-items:center}.actions form{margin:0}
        .link{color:#67adff}.empty{text-align:center;padding:55px 20px}.price{font-weight:800;color:#65a8ff}
        .pagination{padding:18px}.pagination nav{display:flex;justify-content:center;gap:8px;flex-wrap:wrap}
        @media(max-width:600px){.wrap{padding:22px 12px}.top{align-items:flex-start}.btn{padding:11px 13px}}
    </style>
</head>
<body>
<div class="wrap">
    <div class="top">
        <a class="logo" href="{{ url('/') }}">MOTO<span>HUB</span></a>
        <a class="btn secondary" href="{{ url('/') }}">← მთავარ გვერდზე</a>
    </div>

    <div class="top">
        <div>
            <h1>ჩემი განცხადებები</h1>
            <div class="muted">მართე შენი ახალი და მეორადი მოტოციკლის ნაწილები.</div>
        </div>
        <a class="btn" href="{{ route('account.part-listings.create') }}">＋ განცხადების დამატება</a>
    </div>

    @if(session('success'))
        <div class="notice">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="notice">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @if($listings->count())
        <div class="tablebox">
            <table>
                <thead>
                    <tr><th>ნაწილი</th><th>მდგომარეობა</th><th>ფასი</th><th>სტატუსი</th><th>მოქმედებები</th></tr>
                </thead>
                <tbody>
                @foreach($listings as $listing)
                    <tr>
                        <td>
                            <strong>{{ $listing->title }}</strong>
                            <div class="muted" style="margin-top:5px">
                                {{ $listing->brand ?: 'ბრენდი მითითებული არაა' }}
                                @if($listing->compatible_motorcycle)
                                    · {{ $listing->compatible_motorcycle }}
                                @endif
                            </div>
                        </td>
                        <td>{{ $listing->condition === 'new' ? 'ახალი' : 'მეორადი' }}</td>
                        <td class="price">{{ number_format((float)$listing->price, 2) }} ₾</td>
                        <td>
                            @php
                                $statusLabels = [
                                    'pending' => 'შემოწმების პროცესში',
                                    'published' => 'გამოქვეყნებული',
                                    'rejected' => 'უარყოფილი',
                                ];
                            @endphp
                            <span class="status {{ $listing->status }}">
                                {{ $statusLabels[$listing->status] ?? $listing->status }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a class="link" href="{{ route('account.part-listings.edit', $listing) }}">რედაქტირება</a>
                                <form method="POST" action="{{ route('account.part-listings.destroy', $listing) }}" onsubmit="return confirm('ნამდვილად გსურს განცხადების წაშლა?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn secondary" type="submit">წაშლა</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination">{{ $listings->links() }}</div>
        </div>
    @else
        <div class="tablebox empty">
            <h2>ჯერ განცხადებები არ გაქვს</h2>
            <p class="muted">დაამატე მოტოციკლის ნაწილი და განათავსე MotoHub-ზე.</p>
            <a class="btn" href="{{ route('account.part-listings.create') }}">პირველი განცხადების დამატება</a>
        </div>
    @endif
</div>
</body>
</html>
