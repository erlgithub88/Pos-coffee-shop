<nav class="main-header navbar navbar-expand" style="background-color: #F5E9DA; color: #5C2A1D; justify-content: space-between; padding: 0 15px; align-items: center; display: flex;">
  <ul class="navbar-nav" style="display: flex; align-items: center; margin: 0;">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: #5C2A1D; font-size: 22px;">
        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>

  <div style="display: flex; align-items: center; gap: 25px;">
    <!-- Icon kopi dengan uap besar -->
    <div class="coffee-icon" aria-label="Coffee Icon" title="Coffee time" style="position: relative; width: 70px; height: 85px;">
      <div class="steam steam1"></div>
      <div class="steam steam2"></div>
      <div class="steam steam3"></div>

      <svg viewBox="0 0 64 64" fill="#5C2A1D" xmlns="http://www.w3.org/2000/svg" style="width: 70px; height: 85px;">
        <path d="M48 16H14a6 6 0 0 0 0 12h34a6 6 0 0 0 0-12zM14 30a10 10 0 0 1 0-20h34a10 10 0 0 1 0 20zM50 18a4 4 0 0 1 0 8H14a4 4 0 0 1 0-8z"/>
      </svg>
    </div>

    <!-- Quote kopi -->
    <div id="coffee-quote" style="font-style: italic; font-weight: 700; font-size: 20px; color: #7E4B25; min-width: 280px;">
      “Life begins after coffee.” ☕
    </div>
  </div>

  <div id="clock" style="font-weight: 700; color: #5C2A1D; font-family: monospace; min-width: 130px; text-align: right; display: flex; align-items: center; gap: 6px; font-size: 20px;">
    <i class="fas fa-clock"></i>
    <span id="clock-time"></span>
  </div>

  <ul class="navbar-nav ml-auto" style="align-items: center; margin: 0;">
    <li class="nav-item">
      <form action="{{ route('logout') }}" method="GET">
        @csrf
        <button type="submit" class="nav-link btn btn-link"
          style="color: #5C2A1D; font-size: 20px; font-weight: 700; text-decoration: none;">
          <i class="fas fa-sign-out-alt"></i> Logout
        </button>
      </form>
    </li>
  </ul>
</nav>

<style>
  .steam {
    position: absolute;
    top: 0;
    width: 18px;
    height: 60px;
    border-radius: 10px;
    background: #A9746E;
    opacity: 0.5;
    animation: steamUpBig 3s infinite ease-in-out;
    filter: blur(1.5px);
  }

  .steam1 { left: 10px; animation-delay: 0s; }
  .steam2 { left: 28px; animation-delay: 1s; }
  .steam3 { left: 45px; animation-delay: 2s; }

  @keyframes steamUpBig {
    0%   { transform: translateY(0) scaleX(1); opacity: 0.5; }
    50%  { opacity: 0.2; transform: translateY(-50px) scaleX(1.5); }
    100% { transform: translateY(0) scaleX(1); opacity: 0.5; }
  }
</style>

<script>
  const quotes = [
    "Life begins after coffee. ☕",
    "Coffee is always a good idea.",
    "Espresso yourself!",
    "May your coffee be strong and your Monday be short.",
    "A yawn is a silent scream for coffee."
  ];

  function showQuote() {
    const quoteElem = document.getElementById('coffee-quote');
    const randomIndex = Math.floor(Math.random() * quotes.length);
    quoteElem.textContent = quotes[randomIndex];
  }

  function updateClock() {
    const clockTimeElem = document.getElementById('clock-time');
    const now = new Date();
    clockTimeElem.textContent = now.toLocaleTimeString([], {
      hour: '2-digit',
      minute: '2-digit',
      second: '2-digit'
    });
  }

  showQuote();
  setInterval(showQuote, 15000);
  updateClock();
  setInterval(updateClock, 1000);
</script>
