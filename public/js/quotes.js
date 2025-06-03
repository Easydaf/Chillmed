const quotes = [
  "Kamu cukup, bahkan ketika kamu merasa tidak.",
  "Satu langkah kecil tetaplah kemajuan.",
  "Izinkan dirimu beristirahat, bukan menyerah.",
  "Pikiranmu tidak selamanya benar, terutama saat kamu lelah.",
  "Hari yang berat bukan berarti hidup yang buruk.",
  "sebenarnya kau hebat",
  "semua patut disyukuri",
];

let currentQuoteIndex = 0;
const quoteElement = document.getElementById("quote");

function changeQuote() {
  // Fade out dulu
  quoteElement.style.opacity = 0;

  // Setelah 1 detik (transisi fade out selesai), ganti teks dan fade in
  setTimeout(() => {
    currentQuoteIndex = (currentQuoteIndex + 1) % quotes.length;
    quoteElement.textContent = `"${quotes[currentQuoteIndex]}"`;

    quoteElement.style.opacity = 1;
  }, 1000); // 1000ms = 1 detik
}

// Ganti quote setiap 8 detik
setInterval(changeQuote, 2000);