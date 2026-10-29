import './style.css'

const app = document.querySelector('#app')
const DEFAULT_SITE_URL = 'http://10.0.2.2:8000'
const STORAGE_KEY = 'motohub.siteUrl'

function cleanUrl(value) {
  const raw = value.trim().replace(/\/$/, '')
  const parsed = new URL(raw)
  if (!['http:', 'https:'].includes(parsed.protocol)) {
    throw new Error('მისამართი უნდა იწყებოდეს http:// ან https://')
  }
  return parsed.origin
}

function renderConnectPage() {
  const savedUrl = localStorage.getItem(STORAGE_KEY) || DEFAULT_SITE_URL

  app.innerHTML = `
    <header class="topbar">
      <div class="logo">MOTO<span>HUB</span></div>
      <div class="location">MOBILE · GEORGIA</div>
    </header>

    <main>
      <section class="hero">
        <div class="eyebrow">MOTOHUB MOBILE</div>
        <h1>შენი მოტო სამყარო<br><span>ერთ აპლიკაციაში.</span></h1>
        <p>განცხადებები, გაქირავება, ნაწილები, სერვისცენტრები, ტრანსპორტირება და პირადი კაბინეტი — იმავე MotoHub სისტემით.</p>
      </section>

      <section class="section connect-section">
        <div class="section-heading">
          <h2>საიტთან დაკავშირება</h2>
          <span>სატესტო რეჟიმი</span>
        </div>

        <div class="connect-card">
          <label for="site-url">MotoHub საიტის მისამართი</label>
          <input id="site-url" type="url" autocomplete="url"
            value="${savedUrl.replace(/&/g, '&amp;').replace(/"/g, '&quot;')}"
            placeholder="http://10.0.2.2:8000" required />
          <p class="connect-help">
            Android ემულატორზე გამოიყენე <strong>http://10.0.2.2:8000</strong>.
            ტელეფონზე საჭიროა კომპიუტერის ლოკალური IP მისამართი ან ჰოსტინგის HTTPS მისამართი.
          </p>
          <button class="primary-btn" id="connect-site" type="button">გახსენი სრული MotoHub ↗</button>
          <p class="form-note" id="connect-status" role="status">
            აპლიკაცია გამოიყენებს საიტის არსებულ გვერდებსა და ფუნქციებს.
          </p>
        </div>

        <div class="section-heading features-heading">
          <h2>აპლიკაციაში ხელმისაწვდომი</h2>
        </div>
        <div class="services">
          <div class="service"><span class="service-icon">◈</span><strong>მოტო განცხადებები</strong><small>კატალოგი და განცხადების ნახვა</small></div>
          <div class="service"><span class="service-icon">⚙</span><strong>ნაწილები</strong><small>კატალოგი და მოთხოვნის გაგზავნა</small></div>
          <div class="service"><span class="service-icon">🏍</span><strong>გაქირავება</strong><small>სკუტერები და დაჯავშნა</small></div>
          <div class="service"><span class="service-icon">🔧</span><strong>სერვისცენტრები</strong><small>სერვისები და ჩაწერა</small></div>
          <div class="service"><span class="service-icon">↗</span><strong>ტრანსპორტირება</strong><small>მოთხოვნები და შეთავაზებები</small></div>
          <div class="service"><span class="service-icon">♙</span><strong>ანგარიში და შეტყობინებები</strong><small>შესვლა, პროფილი და მიმოწერა</small></div>
        </div>
      </section>
    </main>
  `

  const input = document.querySelector('#site-url')
  const status = document.querySelector('#connect-status')

  document.querySelector('#connect-site').addEventListener('click', () => {
    try {
      const siteUrl = cleanUrl(input.value)
      localStorage.setItem(STORAGE_KEY, siteUrl)
      status.textContent = 'ვუკავშირდები MotoHub-ს…'
      window.location.assign(siteUrl)
    } catch (error) {
      status.textContent = error instanceof TypeError
        ? 'შეიყვანე სწორი მისამართი, მაგალითად http://10.0.2.2:8000'
        : error.message
    }
  })
}

renderConnectPage()
