<!DOCTYPE html>
<html lang="ka">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>3D Moto Garage | MotoHub</title>
<style>
:root{color-scheme:dark;--bg:#070b13;--panel:#0e1726;--line:#20334e;--blue:#2585ff;--muted:#93a6c0;--white:#f4f8ff}
*{box-sizing:border-box}body{margin:0;background:radial-gradient(ellipse at 50% -15%,#17345b 0,transparent 48%),var(--bg);color:var(--white);font:14px Inter,Segoe UI,Arial,sans-serif}a{color:inherit;text-decoration:none}.shell{max-width:1500px;margin:auto;padding:22px}.top{display:flex;justify-content:space-between;align-items:center;gap:15px;margin-bottom:20px}.brand{font-size:22px;font-weight:900;letter-spacing:-1px}.brand span{color:#55a6ff}.top a,.btn{border:1px solid var(--line);border-radius:10px;padding:10px 14px;background:#111e31;color:#eaf3ff}.intro{margin:22px 0}.eyebrow{color:#63aaff;font-size:11px;font-weight:800;letter-spacing:2px}.intro h1{font-size:clamp(28px,4vw,44px);margin:8px 0}.intro p{color:var(--muted);max-width:720px;line-height:1.65}.layout{display:grid;grid-template-columns:minmax(0,1fr) 320px;gap:16px}.viewer,.panel{background:linear-gradient(145deg,#111d2e,#0a111e);border:1px solid var(--line);border-radius:18px;overflow:hidden}.viewer-head{display:flex;justify-content:space-between;align-items:center;padding:16px 18px;border-bottom:1px solid var(--line)}.viewer-head strong{font-size:16px}.viewer-head small{color:var(--muted)}#stage{height:min(66vh,650px);min-height:390px;position:relative;background:radial-gradient(ellipse at 50% 55%,#1a2a40 0,#0b1422 55%,#080e18 100%)}#stage canvas{display:block;width:100%;height:100%}.hint{position:absolute;bottom:14px;left:50%;transform:translateX(-50%);background:#07101dcc;border:1px solid #304766;border-radius:30px;padding:8px 13px;color:#b8c9df;font-size:11px;white-space:nowrap;pointer-events:none}.viewer-foot{padding:12px 16px;display:flex;justify-content:space-between;gap:10px;flex-wrap:wrap;color:var(--muted);font-size:12px}.panel{padding:18px}.panel h2{margin:0 0 5px;font-size:18px}.muted{color:var(--muted);line-height:1.5}.field{margin:17px 0}.field label{display:block;font-size:12px;color:#b7c8dc;font-weight:700;margin-bottom:8px}.field select,.field input{width:100%;background:#081321;border:1px solid #29405e;color:white;border-radius:9px;padding:11px}.swatches{display:flex;gap:9px;flex-wrap:wrap}.swatch{width:34px;height:34px;border-radius:50%;border:2px solid #526984;cursor:pointer}.swatch.active{outline:2px solid #62adff;outline-offset:3px}.option{width:100%;display:flex;justify-content:space-between;gap:10px;text-align:left;margin:7px 0;padding:11px 12px;border:1px solid #263b57;border-radius:10px;background:#0a1422;color:#eaf3ff;cursor:pointer}.option.active{border-color:#318eff;background:#102747}.option small{color:#91a6c0}.actions{display:flex;gap:8px;margin-top:18px}.actions button{flex:1;cursor:pointer}.primary{background:#1677ff;border-color:#1677ff}.notice{font-size:11px;color:#9bb0ca;background:#0a1422;border:1px solid #1c3049;border-radius:10px;padding:11px;line-height:1.55;margin-top:15px}.status{font-size:11px;color:#65d6ae;min-height:17px;margin-top:9px}.spec{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:14px}.spec div{background:#0a1422;border:1px solid #1d3048;border-radius:9px;padding:10px}.spec small{display:block;color:#8298b4}.spec strong{display:block;margin-top:3px}@media(max-width:900px){.layout{grid-template-columns:1fr}#stage{height:55vh;min-height:340px}.panel{order:2}}@media(max-width:520px){.shell{padding:12px}.top{align-items:flex-start}.viewer-head{align-items:flex-start;flex-direction:column;gap:5px}#stage{min-height:300px;height:48vh}.hint{font-size:10px}}
</style>
</head>
<body>
<div class="shell">
<header class="top"><a class="brand" href="{{ route('home') }}">Moto<span>Hub</span> / GARAGE</a><a href="{{ route('account.index') }}">← პირად კაბინეტში</a></header>
<section class="intro"><div class="eyebrow">INTERACTIVE 3D CONFIGURATOR · BETA</div><h1>შენი მოტო. შენი სტილი.</h1><p>დაატრიალე მოტოციკლი 360°-ით, შეცვალე ვიზუალური დეტალები და შეინახე შენი კონფიგურაცია. ეს არის პირველი სატესტო 3D პროტოტიპი Yamaha YZF‑R1 2016-ისთვის.</p></section>
<div class="layout">
<section class="viewer"><div class="viewer-head"><strong>Yamaha YZF‑R1 · 2016</strong><small>3D PREVIEW / REAL-TIME</small></div><div id="stage"><div class="hint">დააჭირე და გადაათრიე დასატრიალებლად · Scroll — Zoom</div></div><div class="viewer-foot"><span>360° ხედვა · მაუსი / სენსორული ეკრანი</span><span id="renderStatus">3D სცენის ჩატვირთვა…</span></div></section>
<aside class="panel"><h2>კონფიგურაცია</h2><div class="muted">ცვლილებები მყისიერად აისახება 3D მოდელზე.</div>
<div class="field"><label>კორპუსის ფერი</label><div class="swatches"><button class="swatch active" data-color="#1768d1" style="background:#1768d1" aria-label="ლურჯი"></button><button class="swatch" data-color="#d9dce2" style="background:#d9dce2" aria-label="თეთრი"></button><button class="swatch" data-color="#11151d" style="background:#11151d" aria-label="შავი"></button><button class="swatch" data-color="#b51f2b" style="background:#b51f2b" aria-label="წითელი"></button><button class="swatch" data-color="#d3a62e" style="background:#d3a62e" aria-label="ოქროსფერი"></button></div></div>
<div class="field"><label>მაყუჩი / გამონაბოლქვი</label><button class="option active" data-exhaust="stock"><span>ქარხნული</span><small>OEM</small></button><button class="option" data-exhaust="short"><span>Short GP</span><small>მოკლე სპორტული</small></button><button class="option" data-exhaust="carbon"><span>Carbon race</span><small>კარბონის ვიზუალი</small></button></div>
<div class="field"><label>დისკების ფერი</label><select id="wheelColor"><option value="#171d27">შავი</option><option value="#c6ccd5">ვერცხლისფერი</option><option value="#d0a12b">ოქროსფერი</option><option value="#1768d1">ლურჯი</option></select></div>
<div class="field"><label>საქარე მინა</label><select id="screen"><option value="clear">გამჭვირვალე</option><option value="smoke">მუქი Smoke</option></select></div>
<div class="actions"><button class="btn primary" id="save">შენახვა</button><button class="btn" id="reset">განულება</button></div><div class="status" id="saveStatus" aria-live="polite"></div>
<div class="spec"><div><small>მოდელი</small><strong>YZF‑R1</strong></div><div><small>წელი</small><strong>2016</strong></div><div><small>ძრავი</small><strong>998 cc</strong></div><div><small>სიმძლავრე</small><strong>200 PS</strong></div></div>
<div class="notice">შენიშვნა: ამ სატესტო ვერსიაში გამოყენებულია პროგრამულად აგებული 3D პროტოტიპი და არა Yamaha-ს ქარხნული CAD/mesh მოდელი. ვიზუალური ნაწილები დემონსტრაციულია და არ წარმოადგენს ზუსტ ზომებს ან რეალურ თავსებადობას. შენახვა ამ ეტაპზე ხდება ამ ბრაუზერის მოწყობილობაზე.</div>
</aside></div></div>
<script type="importmap">{"imports":{"three":"https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.module.js","three/addons/":"https://cdn.jsdelivr.net/npm/three@0.160.0/examples/jsm/"}}</script>
<script type="module">
import * as THREE from 'three';
import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
const stage=document.querySelector('#stage'),status=document.querySelector('#renderStatus');
const scene=new THREE.Scene();scene.background=new THREE.Color('#0b1422');
const camera=new THREE.PerspectiveCamera(38,stage.clientWidth/stage.clientHeight,.1,100);camera.position.set(5.5,3.1,7.4);
const renderer=new THREE.WebGLRenderer({antialias:true});renderer.setPixelRatio(Math.min(devicePixelRatio,2));renderer.setSize(stage.clientWidth,stage.clientHeight);renderer.outputColorSpace=THREE.SRGBColorSpace;renderer.toneMapping=THREE.ACESFilmicToneMapping;renderer.toneMappingExposure=1.15;stage.prepend(renderer.domElement);
const controls=new OrbitControls(camera,renderer.domElement);controls.target.set(0,1.05,0);controls.enableDamping=true;controls.minDistance=4;controls.maxDistance=12;controls.maxPolarAngle=Math.PI*.49;
scene.add(new THREE.HemisphereLight(0xc9e2ff,0x182235,2));
const key=new THREE.DirectionalLight(0xffffff,3.2);key.position.set(4,7,5);scene.add(key);const rim=new THREE.DirectionalLight(0x368cff,2.5);rim.position.set(-5,3,-4);scene.add(rim);
const mat=(color,metalness=.25,roughness=.42)=>new THREE.MeshStandardMaterial({color,metalness,roughness});
const blue=mat('#1768d1',.55,.27),black=mat('#101722',.35,.42),rubber=mat('#090b10',.05,.85),metal=mat('#9da9b8',.85,.25),glass= new THREE.MeshPhysicalMaterial({color:'#9bc9e9',metalness:.15,roughness:.12,transparent:true,opacity:.42,transmission:.25});
const bike=new THREE.Group();scene.add(bike);
function mesh(g,m,x,y,z,rx=0,ry=0,rz=0,parent=bike){const o=new THREE.Mesh(g,m);o.position.set(x,y,z);o.rotation.set(rx,ry,rz);parent.add(o);return o}
function box(x,y,z,sx,sy,sz,m,parent=bike,rx=0,ry=0,rz=0){return mesh(new THREE.BoxGeometry(sx,sy,sz),m,x,y,z,rx,ry,rz,parent)}
function cyl(r1,r2,len,m,x,y,z,rx=0,ry=0,rz=0,parent=bike){return mesh(new THREE.CylinderGeometry(r1,r2,len,24),m,x,y,z,rx,ry,rz,parent)}
// wheels and brakes; motorcycle longitudinal axis is X
const wheels=[];
for(const x of [-1.35,1.25]){
 const wg=new THREE.Group();wg.position.set(x,.72,0);bike.add(wg);wheels.push(wg);
 mesh(new THREE.TorusGeometry(.57,.105,14,48),rubber,0,0,0,0,0,0,wg);
 mesh(new THREE.TorusGeometry(.405,.035,10,40),metal,0,0,0,0,0,0,wg);
 cyl(.13,.13,.12,metal,0,0,0,Math.PI/2,0,0,wg);
 for(let i=0;i<5;i++){let a=i*Math.PI*2/5;box(Math.cos(a)*.27,Math.sin(a)*.27,0,.42,.035,.07,metal,wg,0,0,a)}
 mesh(new THREE.TorusGeometry(.28,.018,8,32),mat('#b5bac4',.8,.3),0,0,.07,0,0,0,wg);
}
const frame=mat('#4d5969',.75,.38);
box(-.05,1.02,0,1.65,.18,.22,frame);box(.25,1.18,0,.8,.16,.2,black);
box(-.42,1.37,0,.8,.42,.72,blue, bike,0,0,-.1);
box(.47,1.38,0,.75,.38,.66,blue,bike,0,0,.12);
box(-.02,1.61,0,.52,.1,.5,black);
box(.55,1.65,0,.45,.1,.44,black, bike,0,0,-.1);
box(-.7,1.17,0,.4,.12,.34,black);
box(.92,1.12,0,.5,.1,.28,black);
cyl(.08,.08,.78,metal,-.83,1.48,0,Math.PI/2);
cyl(.045,.045,.65,black,-.83,1.49,0,Math.PI/2);
box(-.98,1.58,0,.22,.06,.52,black);
const tail=mesh(new THREE.ConeGeometry(.3,.65,5),blue,.95,1.52,0,0,0,-Math.PI/2);tail.scale.set(1,.7,.8);
box(.12,1.78,0,.58,.08,.33,black);
const tank=mesh(new THREE.SphereGeometry(1,24,16),blue,-.2,1.62,0);tank.scale.set(.58,.28,.38);
const wind=mesh(new THREE.BoxGeometry(.28,.25,.5),glass,-.62,1.86,0,0,0,-.3);
const forkMat=mat('#aeb8c6',.85,.25);
for(const z of [-.24,.24]){cyl(.045,.045,.85,forkMat,-1.05,1.12,z,0,0,-.2);cyl(.04,.04,.72,forkMat,1.05,1.12,z,0,0,-.15)}
// handlebars, mirrors and headlight
box(-.75,1.75,0,.32,.045,.65,metal);
for(const z of [-.42,.42]){box(-.7,1.95,z,.16,.04,.12,black,bike,0,0,-.35);cyl(.018,.018,.24,metal,-.72,1.88,z,0,0,.35)}
box(-1.03,1.54,0,.12,.12,.44,mat('#d9efff',.2,.12));
box(-1.12,1.48,0,.06,.05,.32,mat('#a8d7ff',.1,.1));
// engine and exhaust
const engine=box(.12,.98,0,.68,.48,.48,mat('#66717e',.7,.48));
const pipes=new THREE.Group();bike.add(pipes);
for(const z of [-.19,.19]){cyl(.045,.06,.55,metal,.62,.93,z,0,0,Math.PI/2);cyl(.055,.07,.42,metal,.9,.86,z,0,0,Math.PI/2)}
const exhaust=new THREE.Group();bike.add(exhaust);
let muffler;
function makeExhaust(kind){while(exhaust.children.length)exhaust.remove(exhaust.children[0]);let length=kind==='short'?.42:.72, radius=kind==='carbon'?.105:.09;const em=kind==='carbon'?mat('#171d27',.45,.3):metal;muffler=mesh(new THREE.CylinderGeometry(radius*.8,radius,length,24),em,.92,.92,.34,0,0,Math.PI/2,exhaust);mesh(new THREE.CylinderGeometry(radius*.72,radius*.72,.025,24),black, .92+length/2,.92,.34,0,0,Math.PI/2,exhaust);mesh(new THREE.CylinderGeometry(radius*.6,radius*.6,.03,24),metal,.92-length/2,.92,.34,0,0,Math.PI/2,exhaust);if(kind==='carbon')mesh(new THREE.CylinderGeometry(radius*1.04,radius*1.04,length*.7,24,1,true),mat('#333b47',.25,.65),.92,.92,.34,0,0,Math.PI/2,exhaust)}
makeExhaust('stock');
const ground=mesh(new THREE.CircleGeometry(5,64),mat('#101a29',.2,.9),0,.02,0,-Math.PI/2);ground.position.y=.02;
const grid=new THREE.GridHelper(12,24,0x29415e,0x1b2a3d);grid.position.y=.025;scene.add(grid);
function setColor(c){blue.color.set(c)}
document.querySelectorAll('[data-color]').forEach(b=>b.addEventListener('click',()=>{document.querySelectorAll('[data-color]').forEach(x=>x.classList.remove('active'));b.classList.add('active');setColor(b.dataset.color)}));
document.querySelectorAll('[data-exhaust]').forEach(b=>b.addEventListener('click',()=>{document.querySelectorAll('[data-exhaust]').forEach(x=>x.classList.remove('active'));b.classList.add('active');makeExhaust(b.dataset.exhaust)}));
document.querySelector('#wheelColor').addEventListener('change',e=>{const c=mat(e.target.value,.72,.28);wheels.forEach(w=>w.children[1].material=c);wheels.forEach(w=>w.children[3].children.forEach(s=>s.material=c))});
document.querySelector('#screen').addEventListener('change',e=>{glass.color.set(e.target.value==='smoke'?'#26384b':'#9bc9e9');glass.opacity=e.target.value==='smoke'?.72:.42});
const defaults={color:'#1768d1',exhaust:'stock',wheel:'#171d27',screen:'clear'};
function apply(data){setColor(data.color||defaults.color);document.querySelectorAll('[data-color]').forEach(x=>x.classList.toggle('active',x.dataset.color===(data.color||defaults.color)));makeExhaust(data.exhaust||'stock');document.querySelectorAll('[data-exhaust]').forEach(x=>x.classList.toggle('active',x.dataset.exhaust===(data.exhaust||'stock')));document.querySelector('#wheelColor').value=data.wheel||defaults.wheel;document.querySelector('#wheelColor').dispatchEvent(new Event('change'));document.querySelector('#screen').value=data.screen||'clear';document.querySelector('#screen').dispatchEvent(new Event('change'))}
function current(){return {color:document.querySelector('[data-color].active')?.dataset.color||defaults.color,exhaust:document.querySelector('[data-exhaust].active')?.dataset.exhaust||'stock',wheel:document.querySelector('#wheelColor').value,screen:document.querySelector('#screen').value}}
try{const saved=JSON.parse(localStorage.getItem('motohub-r1-2016-config')||'null');if(saved)apply(saved)}catch(e){}
document.querySelector('#save').addEventListener('click',()=>{localStorage.setItem('motohub-r1-2016-config',JSON.stringify(current()));document.querySelector('#saveStatus').textContent='კონფიგურაცია შენახულია ამ მოწყობილობაზე.'});
document.querySelector('#reset').addEventListener('click',()=>{apply(defaults);localStorage.removeItem('motohub-r1-2016-config');document.querySelector('#saveStatus').textContent='საწყისი პარამეტრები აღდგენილია.'});
function resize(){const w=stage.clientWidth,h=stage.clientHeight;camera.aspect=w/h;camera.updateProjectionMatrix();renderer.setSize(w,h)}new ResizeObserver(resize).observe(stage);
function animate(){requestAnimationFrame(animate);controls.update();renderer.render(scene,camera)}animate();status.textContent='3D სცენა მზად არის';
</script>
</body></html>