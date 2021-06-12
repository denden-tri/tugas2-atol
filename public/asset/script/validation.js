const form = document.getElementById("add-data-form");
const namaGroup = document.getElementById("nama-group");
const debutGroup = document.getElementById("tgl-debut");
const agensiGroup = document.getElementById("agensi");

const submit = document.getElementById("add-btn");

submit.addEventListener("click", () => {
  const namaValue = namaGroup.value.trim();
  const debutValue = debutGroup.value;
  const agensiValue = agensiGroup.value.trim();
  console.log(debutValue);
  if (namaValue === "") {
    //error
    namaGroup.setCustomValidity("Nama group tidak boleh kosong");
  } else {
    namaGroup.setCustomValidity("");
  }
  if (!debutValue) {
    debutGroup.setCustomValidity("Tanggal debut harus ada");
  } else {
    debutGroup.setCustomValidity("");
  }
  if (agensiValue === "") {
    agensiGroup.setCustomValidity("Agensi group harus ada");
  } else {
    agensiGroup.setCustomValidity("");
  }
});
