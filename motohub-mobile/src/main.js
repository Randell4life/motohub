import './style.css'

const app = document.querySelector('#app')
const DEFAULT_API_BASE = 'http://10.0.2.2:8000'
const API_KEY = 'motohub.apiBase'
const money = (value) => value == null || value === '' ? 'ფასი შეთანხმებით' : new Intl.NumberFormat('ka-GE', { maximumFractionDigits: 0 }).format(Number(value)) + ' ₾'
const escapeHtml = (value = '') => String(value).replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' })[c])

function getApiBase() {
  return (localStorage.getItem(API_KEY) || DEFAULT_API_BASE).replace(/\/$/, '')
}

async function api(path, options = {}) {
  const response = await fetch(getApiBase() + path, {
    headers: { Accept: 'application/json', ...(options.body ? { 'Content-Type': 'application/json' } : {}) },
    ...options,
  })
  const data = await response.json().catch(() => ({}))
  if (!response.ok) throw new Error(data.message || 'მოთხოვნა ვერ შესრულდა. გადაამოწმე სერვერი.')
  return data
}

const state = { page: 'home', loading: false, error: '', motorcycles: [], rentals: [], parts: [] }

function shell(content) {
  app.innerHTML = `
    <header class="topbar">
      <a class="brand" href="#" data-page="home">MOTO<span>HUB</span></a>
      <span class="top-label">MOBILE · BETA</span>
    </header>
    <main>${content}</main>
    <nav class="bottom-nav" aria-label="მთავარი ნავიგაცია">
      <button data-page="home" class="${state.page === 'home' ? 'active' : ''}"><span>⌂</span>მთავარი</button>
      <button data-page="motorcycles" class="${state.page === 'motorcycles' ? 'active' : ''}"><span>🏍</span>მოტოები</button>
      <button data-page="rentals" class="${state.page === 'rentals' ? 'active' : ''}"><span>↗</span>ქირაობა</button>
      <button data-page="parts" class="${state.page === 'parts' ? 'active' : ''}"><span>⚙</span>ნაწილები</button>
      <button data-page="more" class="${state.page === 'more' ? 'active' : ''}"><span>☰</span>მეტი</button>
    </nav>`
  app.querySelectorAll('[data-page]').forEach(el => el.addEventListener('click', (event) => {
    event.preventDefault()
    navigate(el.dataset.page)
  }))
}

function errorBlock(message) {
  return message ? `<div class="error-box">${escapeHtml(message)}<button data-retry>ხელახლა ცდა</button></div>` : ''
}

function loadingBlock() { return '<div class="loading"><span></span>იტვირთება…</div>' }

function card(item, kind) {
  const image = item.image_url
    ? `<img src="${escapeHtml(item.image_url)}" alt="" loading="lazy" onerror="this.style.display='none'">`
    : '<div class="placeholder-art">MOTO<span>HUB</span></div>'
  const price = kind === 'rentals' ? money(item.daily_rate) + ' / დღე' : money(item.price)
  const meta = kind === 'motorcycles'
    ? [item.year, item.engine_cc ? item.engine_cc + ' cc' : '', item.location].filter(Boolean).join(' · ')
    : kind === 'rentals'
      ? [item.year, item.engine_cc ? item.engine_cc + ' cc' : '', item.location].filter(Boolean).join(' · ')
      : [item.brand, item.condition === 'used' ? 'მეორადი' : item.condition === 'new' ? 'ახალი' : '', item.compatible_motorcycle].filter(Boolean).join(' · ')
  const href = kind === 'motorcycles' ? '/motorcycles/' + encodeURIComponent(item.slug) : kind === 'rentals' ? '/rentals/' + encodeURIComponent(item.slug) : '/parts'
  return `<article class="listing-card">
    <div class="listing-image">${image}${item.is_featured ? '<span class="featured">რჩეული</span>' : ''}</div>
    <div class="listing-content">
      <h3>${escapeHtml(item.title || [item.brand, item.model].filter(Boolean).join(' ') || 'MotoHub განცხადება')}</h3>
      <p class="listing-meta">${escapeHtml(meta || 'დეტალები განცხადებაში')}</p>
      <div class="listing-bottom"><strong>${escapeHtml(price)}</strong><button class="small-btn" data-open-url="${escapeHtml(href)}">ნახვა ↗</button></div>
    </div>
  </article>`
}

function homePage() {
  return `
    <section class="hero">
      <p class="eyebrow">MOTORCYCLE LIFE · GEORGIA</p>
      <h1>შენი მოტო სამყარო<br><span>ერთ აპლიკაციაში.</span></h1>
      <p class="hero-copy">შეარჩიე მოტოციკლი, იქირავე სკუტერი, მოძებნე ნაწილი ან იპოვე სერვისი.</p>
      <div class="hero-actions">
        <button class="primary-btn" data-page="motorcycles">მოტოციკლების ნახვა</button>
        <button class="secondary-btn" data-page="parts">ნაწილის მოძებნა</button>
      </div>
      <div class="hero-stats"><div><strong>01</strong><span>მოტოები</span></div><div><strong>02</strong><span>გაქირავება</span></div><div><strong>03</strong><span>სერვისი</span></div></div>
    </section>
    <section class="section">
      <div class="section-heading"><div><p class="eyebrow">DISCOVER</p><h2>ყველაფერი მოტოზე</h2></div></div>
      <div class="feature-grid">
        <button class="feature-card" data-page="motorcycles"><span>🏍</span><b>მოტოციკლები</b><small>განცხადებები და ფასები</small></button>
        <button class="feature-card" data-page="rentals"><span>↗</span><b>გაქირავება</b><small>სკუტერები და ჯავშნები</small></button>
        <button class="feature-card" data-page="parts"><span>⚙</span><b>ნაწილები</b><small>კატალოგი და მოთხოვნა</small></button>
        <button class="feature-card" data-page="more"><span>⌁</span><b>სერვისი და სხვა</b><small>სახელოსნოები, ტრანსპორტი</small></button>
      </div>
    </section>
    <section class="section"><div class="section-heading"><div><p class="eyebrow">LATEST</p><h2>ახალი მოტოციკლები</h2></div><button class="text-btn" data-page="motorcycles">ყველა ↗</button></div><div id="home-motos" class="listing-grid">${loadingBlock()}</div></section>
    ${errorBlock(state.error)}`
}

function catalogPage(kind) {
  const title = kind === 'motorcycles' ? 'მოტოციკლები' : kind === 'rentals' ? 'გაქირავება' : 'ნაწილების კატალოგი'
  const desc = kind === 'motorcycles' ? 'იპოვე შენთვის სასურველი მოტოციკლი.' : kind === 'rentals' ? 'შეარჩიე სკუტერი და ნახე გაქირავების ფასი.' : 'მოძებნე სათადარიგო ნაწილები.'
  const data = state[kind]
  return `<section class="page-heading"><p class="eyebrow">MOTOHUB CATALOG</p><h1>${title}</h1><p>${desc}</p></section>
    ${kind === 'parts' ? '<section class="section request-shortcut"><button class="primary-btn" data-page="request">ვერ იპოვე ნაწილი? მოითხოვე ↗</button></section>' : ''}
    <section class="section"><div class="listing-grid" id="catalog-list">${state.loading ? loadingBlock() : data.length ? data.map(item => card(item, kind)).join('') : '<div class="empty-state">ჯერ განცხადებები არ არის გამოქვეყნებული.</div>'}</div>${errorBlock(state.error)}</section>`
}

function requestPage() {
  return `<section class="page-heading"><p class="eyebrow">PART REQUEST</p><h1>მოითხოვე <span>ნაწილი.</span></h1><p>შეავსე ფორმა და მოთხოვნა MotoHub-ის სისტემაში გაიგზავნება.</p></section>
    <section class="section"><form id="parts-form" class="form-card">
      <label for="part_name">ნაწილის დასახელება *</label><input id="part_name" name="part_name" required maxlength="255" placeholder="მაგ. Yamaha R1-ის ფარი">
      <div class="form-row"><div><label for="motorcycle_brand">მარკა</label><input id="motorcycle_brand" name="motorcycle_brand" placeholder="Yamaha"></div><div><label for="motorcycle_model">მოდელი</label><input id="motorcycle_model" name="motorcycle_model" placeholder="R1"></div></div>
      <label for="motorcycle_year">გამოშვების წელი</label><input id="motorcycle_year" name="motorcycle_year" type="number" min="1900" max="2100" placeholder="2016">
      <label for="customer_name">შენი სახელი *</label><input id="customer_name" name="customer_name" required maxlength="255" placeholder="სახელი">
      <label for="phone">ტელეფონი *</label><input id="phone" name="phone" required maxlength="50" type="tel" placeholder="+995 5XX XX XX XX">
      <label for="email">ელფოსტა</label><input id="email" name="email" type="email" placeholder="name@example.com">
      <label for="details">დამატებითი ინფორმაცია</label><textarea id="details" name="details" rows="4" maxlength="5000" placeholder="ფერი, მდგომარეობა ან სხვა დეტალი"></textarea>
      <button class="primary-btn" type="submit">მოთხოვნის გაგზავნა ↗</button><p id="request-status" class="form-note" role="status"></p>
    </form></section>`
}

function morePage() {
  const base = getApiBase().replace(/\/api$/, '')
  const links = [
    ['სერვისცენტრები', 'იპოვე სერვისი და ჩაეწერე', '/service-centers'],
    ['ტრანსპორტირება', 'მოტოციკლის გადაზიდვის მოთხოვნა', '/transport'],
    ['პირადი კაბინეტი', 'შენი განცხადებები და ჯავშნები', '/account'],
    ['შეტყობინებები', 'მიმოწერა MotoHub-ზე', '/messages'],
    ['შესვლა / რეგისტრაცია', 'შედი ან შექმენი ანგარიში', '/login'],
  ]
  return `<section class="page-heading"><p class="eyebrow">MORE FROM MOTOHUB</p><h1>მეტი <span>შესაძლებლობა.</span></h1><p>სერვისები, პირადი კაბინეტი და ანგარიშის ფუნქციები.</p></section>
    <section class="section link-list">${links.map(([title, desc, path]) => `<button class="link-row" data-open-url="${path}"><span class="link-mark">↗</span><span><b>${title}</b><small>${desc}</small></span><span class="chevron">›</span></button>`).join('')}
    <div class="settings-card"><h3>კავშირის პარამეტრები</h3><p>თუ Laravel სხვა მისამართზე მუშაობს, შეცვალე სერვერის მისამართი.</p><label for="api-base">საიტის მისამართი</label><input id="api-base" value="${escapeHtml(base)}" placeholder="http://10.0.2.2:8000"><button id="save-api" class="secondary-btn">მისამართის შენახვა</button><p id="settings-status" class="form-note"></p></div></section>`
}

function render() {
  if (state.page === 'home') shell(homePage())
  else if (['motorcycles', 'rentals', 'parts'].includes(state.page)) shell(catalogPage(state.page))
  else if (state.page === 'request') shell(requestPage())
  else shell(morePage())

  app.querySelectorAll('[data-page]').forEach(el => el.addEventListener('click', (event) => {
    event.preventDefault()
    navigate(el.dataset.page)
  }))
  app.querySelectorAll('[data-open-url]').forEach(el => el.addEventListener('click', () => {
    const base = getApiBase().replace(/\/api$/, '')
    window.location.href = base + el.dataset.openUrl
  }))
  const retry = app.querySelector('[data-retry]')
  if (retry) retry.addEventListener('click', () => loadData(state.page))
  const form = app.querySelector('#parts-form')
  if (form) form.addEventListener('submit', submitPartRequest)
  const saveApi = app.querySelector('#save-api')
  if (saveApi) saveApi.addEventListener('click', () => {
    const input = app.querySelector('#api-base')
    try {
      const url = new URL(input.value.trim())
      if (!['http:', 'https:'].includes(url.protocol)) throw new Error()
      localStorage.setItem(API_KEY, url.origin)
      app.querySelector('#settings-status').textContent = 'მისამართი შენახულია.'
    } catch {
      app.querySelector('#settings-status').textContent = 'შეიყვანე სწორი http:// ან https:// მისამართი.'
    }
  })
}

async function navigate(page) {
  state.page = page
  state.error = ''
  state.loading = ['motorcycles', 'rentals', 'parts'].includes(page)
  render()
  if (page === 'home') {
    try {
      const result = await api('/api/mobile/motorcycles')
      state.motorcycles = result.data || []
      const target = app.querySelector('#home-motos')
      if (target) target.innerHTML = state.motorcycles.slice(0, 3).map(item => card(item, 'motorcycles')).join('') || '<div class="empty-state">ჯერ მოტოციკლები არ არის გამოქვეყნებული.</div>'
    } catch (e) {
      const target = app.querySelector('#home-motos')
      if (target) target.innerHTML = errorBlock(e.message)
    }
    return
  }
  if (['motorcycles', 'rentals', 'parts'].includes(page)) await loadData(page)
}

async function loadData(page) {
  state.loading = true
  state.error = ''
  render()
  const endpoint = { motorcycles: '/api/mobile/motorcycles', rentals: '/api/mobile/rentals', parts: '/api/mobile/parts' }[page]
  try {
    const result = await api(endpoint)
    state[page] = result.data || []
  } catch (e) {
    state.error = e.message + ' (სერვერი: ' + getApiBase() + ')'
  } finally {
    state.loading = false
    if (state.page === page) render()
  }
}

async function submitPartRequest(event) {
  event.preventDefault()
  const form = event.currentTarget
  const status = app.querySelector('#request-status')
  const submit = form.querySelector('button[type="submit"]')
  submit.disabled = true
  status.textContent = 'იგზავნება…'
  const raw = Object.fromEntries(new FormData(form).entries())
  if (!raw.motorcycle_year) delete raw.motorcycle_year
  else raw.motorcycle_year = Number(raw.motorcycle_year)
  for (const key of Object.keys(raw)) if (raw[key] === '') delete raw[key]
  try {
    const result = await api('/api/part-requests', { method: 'POST', body: JSON.stringify(raw) })
    status.textContent = result.message || 'მოთხოვნა წარმატებით გაიგზავნა!'
    form.reset()
  } catch (e) {
    status.textContent = e.message
  } finally {
    submit.disabled = false
  }
}

render()
navigate('home')
