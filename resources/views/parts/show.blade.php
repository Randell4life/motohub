<!DOCTYPE html>
<html lang="ka"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>{{ $item['title'] }} | MotoHub</title>
<style>
*{box-sizing:border-box}body{margin:0;background:#070a12;color:#f4f8ff;font-family:Arial,"Noto Sans Georgian",sans-serif}a{color:inherit;text-decoration:none}.wrap{width:min(1100px,calc(100% - 36px));margin:auto}.nav{padding:23px 0;border-bottom:1px solid #243653;font-weight:900}.nav span{color:#42a5ff}.panel{display:grid;grid-template-columns:1.05fr 1fr;gap:35px;margin:42px 0 70px;padding:25px;background:#0d1422;border:1px solid #243653;border-radius:16px}.photo{min-height:360px;background:#111d30;border-radius:12px;display:grid;place-items:center;overflow:hidden}.photo img{width:100%;height:100%;max-height:520px;object-fit:contain}.no-photo{color:#8298b8;letter-spacing:3px}.eyebrow{color:#42a5ff;font-size:11px;letter-spacing:2px;font-weight:800}.content h1{font-size:clamp(26px,4vw,42px);line-height:1.2;margin:12px 0}.price{font-size:32px;font-weight:900;margin:17px 0}.muted{color:#98a8c2;line-height:1.8;font-size:13px}.specs{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin:22px 0}.spec{padding:13px;background:#101b2c;border:1px solid #243653;border-radius:8px}.spec small{display:block;color:#98a8c2;margin-bottom:6px;font-size:10px}.spec strong{font-size:13px}.seller{padding:16px;border:1px solid #2c4262;background:#101b2c;border-radius:9px;margin-top:22px}.btn{display:inline-flex;margin-top:15px;padding:13px 17px;background:#1677ff;border-radius:7px;font-weight:800}.back{display:inline-block;color:#42a5ff;margin-top:22px}@media(max-width:720px){.panel{grid-template-columns:1fr;padding:14px;margin-top:22px}.photo{min-height:240px}.specs{grid-template-columns:1fr 1fr}}
</style></head><body><div class="wrap"><div class="nav"><a href="{{ route('parts.index') }}">MOTO<span>HUB</span> / PARTS</a></div>
<main class="panel"><div class="photo">@if($item['image_url'])<img src="{{ $item['image_url'] }}" alt="{{ $item['title'] }}">@else<span class="no-photo">MOTOHUB / PARTS</span>@endif</div>
<section class="content"><div class="eyebrow">{{ $item['source']==='store' ? 'MOTOHUB STORE' : 'COMMUNITY MARKETPLACE' }}</div><h1>{{ $item['title'] }}</h1><div class="price">₾{{ number_format((float)$item['price'],2) }}</div>
<p class="muted">{{ $description ?: 'გამყიდველს დამატებითი აღწერა არ მიუთითებია.' }}</p>
<div class="specs">
<div class="spec"><small>ნაწილის ბრენდი</small><strong>{{ $item['brand'] ?: 'არ არის მითითებული' }}</strong></div>
<div class="spec"><small>კატეგორია</small><strong>{{ $item['category'] ?: 'სხვა' }}</strong></div>
<div class="spec"><small>ნაწილის ტიპი</small><strong>{{ ['oem'=>'ორიგინალი (OEM)','aftermarket'=>'Aftermarket','used'=>'მეორადი'][$item['part_type'] ?? ''] ?? 'არ არის მითითებული' }}</strong></div>
<div class="spec"><small>მდგომარეობა</small><strong>{{ ($item['condition'] ?? 'new')==='new' ? 'ახალი' : 'მეორადი' }}</strong></div>
<div class="spec"><small>ნაწილის კოდი</small><strong>{{ $item['part_number'] ?: '—' }}</strong></div>
<div class="spec"><small>მარაგი</small><strong>{{ $item['stock'] === null ? 'გამყიდველთან დასაზუსტებელია' : ($item['stock']>0 ? $item['stock'].' ცალი' : 'შეკვეთით / ამოწურული') }}</strong></div>
<div class="spec"><small>მოტოციკლი</small><strong>{{ trim(($item['motorcycle_brand'] ?? '').' '.($item['motorcycle_model'] ?? '')) ?: ($item['compatible_motorcycle'] ?: 'არ არის მითითებული') }}</strong></div>
<div class="spec"><small>წელი / მოცულობა</small><strong>{{ $item['motorcycle_year'] ?: '—' }} / {{ $item['engine_cc'] ? $item['engine_cc'].' cc' : '—' }}</strong></div>
</div>
<div class="seller"><strong>გამყიდველი: {{ $sellerName }}</strong>@if($sellerPhone)<p class="muted">ტელეფონი: {{ $sellerPhone }}</p><a class="btn" href="tel:{{ preg_replace('/[^0-9+]/','',$sellerPhone) }}">დარეკვა გამყიდველთან</a>@else<p class="muted">საკონტაქტო ნომერი მითითებული არ არის. დაუკავშირდი პლატფორმის მეშვეობით ან მოითხოვე დამატებითი ინფორმაცია.</p>@endif</div>
@auth
<form method="POST" action="{{ route('favorites.parts.toggle',[$item['source'],$item['id']]) }}" style="margin-top:16px">@csrf<button class="btn" type="submit">♡ ფავორიტებში შენახვა</button></form>
@else
<a class="btn" href="{{ route('login') }}">შესვლა ფავორიტებში შესანახად</a>
@endauth
<a class="back" href="{{ route('parts.index') }}">← ყველა ნაწილზე დაბრუნება</a></section></main></div></body></html>