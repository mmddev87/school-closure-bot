async function checkHoliday() {
  const city = document.getElementById("cityInput").value.trim();
  const resultDiv = document.getElementById("result");

  if (!city) {
    alert("لطفا نام شهر را وارد کنید");
    return;
  }

  resultDiv.innerHTML = '<div class="loading"></div>';
  resultDiv.style.display = "block";

  try {
    const response = await fetch(
      `/api/checkClosure?city=${encodeURIComponent(city)}`
    );

    if (!response.ok) {
      throw new Error(`HTTP Error: ${response.status}`);
    }

    const data = await response.json();

    resultDiv.className = data.result ? "holiday" : "not-holiday";
    resultDiv.innerHTML = data.result
      ? `شهر ${city} امروز تعطیل است`
      : `شهر ${city} امروز تعطیل نیست`;
  } catch (error) {
    resultDiv.className = "holiday";
    resultDiv.innerHTML = "خطا در دریافت اطلاعات";
    throw error;
  }
}

document.getElementById("cityInput").addEventListener("keypress", function (e) {
  if (e.key === "Enter") checkHoliday();
});
