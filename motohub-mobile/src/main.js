import './style.css'

const app = document.querySelector('#app')

function homePage() {
  app.innerHTML = `
    <header class="topbar">
      <div class="logo">MOTO<span>HUB</span></div>
      <div class="location">GEORGIA · 🇬🇪</div>
    </header>

    <main>
      <section class="hero">
        <div class="eyebrow">YOUR MOTORCYCLE WORLD</div>
        <h1>ყველაფერი<br>შენი <span>მოტოსთვის.</span></h1>
        <p>მოტო ნაწილები, გაქირავება და მოტოციკლების სამყარო — ერთ სივრცეში.</p>
        <button class="primary-btn" id="explore">აღმოაჩინე MotoHub ↗</button>
      </section>

      <section class="section" id="services">
        <div class="section-heading">
          <h2>რას ეძებ?</h2>
          <span>ჩვენი სერვისები</span>
        </div>

        <div class="services">
          <button class="service" id="parts">
            <span class="service-icon">⚙</span>
            <strong>მოტო ნაწილები</strong>
            <small>მოითხოვე საჭირო ნაწილი ↗</small>
          </button>

          <button class="service" data-message="მოტოციკლების გაქირავება">
            <span class="service-icon">🏍</span>
            <strong>გაქირავება</strong>
            <small>შეარჩიე მოტოციკლი ↗</small>
          </button>

          <button class="service" data-message="მოტო განცხადებები">
            <span class="service-icon">◈</span>
            <strong>მოტო განცხადებები</strong>
            <small>იყიდე ან გაყიდე ↗</small>
          </button>

          <button class="service" data-message="ჩემი ანგარიში">
            <span class="service-icon">♙</span>
            <strong>ჩემი ანგარიში</strong>
            <small>შენი პროფილი ↗</small>
          </button>
        </div>
      </section>
    </main>

    <nav class="bottom-nav">
      <a href="#" class="active" id="nav-home"><span>⌂</span>მთავარი</a>
      <a href="#" id="nav-parts"><span>⚙</span>ნაწილები</a>
      <a href="#" data-message="მოტოციკლების გაქირავება"><span>🏍</span>გაქირავება</a>
      <a href="#" data-message="ჩემი ანგარიში"><span>♙</span>ანგარიში</a>
    </nav>
  `

  document.querySelector('#explore').addEventListener('click', () => {
    document.querySelector('#services').scrollIntoView({
      behavior: 'smooth'
    })
  })

  document.querySelector('#parts').addEventListener('click', (event) => {
    event.preventDefault()
    partsPage()
  })

  document.querySelector('#nav-parts').addEventListener('click', (event) => {
    event.preventDefault()
    partsPage()
  })

  document.querySelector('#nav-home').addEventListener('click', (event) => {
    event.preventDefault()
    homePage()
  })

  document.querySelectorAll('[data-message]').forEach((button) => {
    button.addEventListener('click', (event) => {
      event.preventDefault()
      alert(button.dataset.message + ' — მალე დაემატება.')
    })
  })
}

function partsPage() {
  app.innerHTML = `
    <header class="topbar">
      <button class="back-btn" id="back" type="button">← უკან</button>
      <div class="logo">MOTO<span>HUB</span></div>
    </header>

    <main class="section request-page">
      <div class="eyebrow">PARTS REQUEST</div>
      <h1 class="request-title">მოითხოვე <span>ნაწილი.</span></h1>
      <p class="request-intro">
        ვერ იპოვე საჭირო ნაწილი? შეავსე ფორმა და დაგიკავშირდებით.
      </p>

      <form id="parts-form">
        <label for="name">სახელი</label>
        <input id="name" name="name" placeholder="შენი სახელი"
          required maxlength="100" />

        <label for="make">მოტოციკლის მარკა</label>
        <input id="make" name="make" placeholder="მაგ. Yamaha"
          required maxlength="100" />

        <label for="model">მოდელი</label>
        <input id="model" name="model" placeholder="მაგ. MT-07"
          required maxlength="100" />

        <label for="year">გამოშვების წელი</label>
        <input id="year" name="year" type="number"
          min="1950" max="2035" placeholder="მაგ. 2022" required />

        <label for="part">რომელი ნაწილი გჭირდება?</label>
        <input id="part" name="part" placeholder="მაგ. წინა ფარი"
          required maxlength="150" />

        <label for="description">დამატებითი აღწერა</label>
        <textarea id="description" name="description" rows="4"
          maxlength="2000"
          placeholder="ფერი, მდგომარეობა ან სხვა დეტალები..."></textarea>

        <label for="phone">ტელეფონის ნომერი</label>
        <input id="phone" name="phone" type="tel"
          placeholder="5XX XX XX XX" required maxlength="30" />

        <button class="primary-btn" type="submit">
          მოთხოვნის გაგზავნა ↗
        </button>

        <p class="form-note">
          შეავსე ფორმა და გამოგზავნე მოთხოვნა.
        </p>
      </form>
    </main>
  `

  document.querySelector('#back').addEventListener('click', (event) => {
    event.preventDefault()
    homePage()
  })

  document.querySelector('#parts-form').addEventListener('submit', async (event) => {
    event.preventDefault()

    const form = event.currentTarget
    const submitButton = form.querySelector('button[type="submit"]')
    const formNote = form.querySelector('.form-note')

    const data = {
      customer_name: form.querySelector('#name').value.trim(),
      motorcycle_brand: form.querySelector('#make').value.trim(),
      motorcycle_model: form.querySelector('#model').value.trim(),
      motorcycle_year: Number(form.querySelector('#year').value),
      part_name: form.querySelector('#part').value.trim(),
      details: form.querySelector('#description').value.trim(),
      phone: form.querySelector('#phone').value.trim()
    }

    submitButton.disabled = true
    submitButton.textContent = 'იგზავნება...'
    formNote.textContent = 'მოთხოვნა იგზავნება სერვერზე...'

    try {
      const response = await fetch(
        'http://10.0.2.2:8000/api/part-requests',
        {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          },
          body: JSON.stringify(data)
        }
      )

      const result = await response.json()

      if (!response.ok) {
        const errors = result.errors
          ? Object.values(result.errors).flat().join('\n')
          : result.message || 'მოთხოვნის გაგზავნა ვერ მოხერხდა.'

        throw new Error(errors)
      }

      form.reset()
      formNote.textContent =
        'მოთხოვნა წარმატებით გაიგზავნა! ჩვენი გუნდი დაგიკავშირდება.'
      alert('მოთხოვნა წარმატებით გაიგზავნა!')

    } catch (error) {
      console.error('Parts request error:', error)

      formNote.textContent =
        'გაგზავნა ვერ მოხერხდა. შეამოწმე სერვერი და კავშირი.'

      alert('შეცდომა: ' + error.message)

    } finally {
      submitButton.disabled = false
      submitButton.textContent = 'მოთხოვნის გაგზავნა →'
    }
  })
}

homePage()
