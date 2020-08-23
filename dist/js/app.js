import User from "./User.js";
import Validator from "./Validator.js";
import GrupniValidator from "./GrupniValidator.js";

const getElement = (el) => {
  return document.querySelector(el);
};

let dodatniOsiguranici = [];
const ul = getElement(".lista-dodatnih-osiguranika");

const handleForm = (e) => {
  e.preventDefault();

  getElement(".broj-dana").textContent = "";

  // NOTE Cisti prethodne error poruke !!!
  const errorMessages = document.querySelectorAll(".error-message");
  errorMessages.forEach((element) => (element.textContent = ""));

  const name = getElement("#name").value;
  const birthDate = getElement("#birth-date").value;
  const passport = getElement("#passport").value;
  const phone = getElement("#phone").value;
  const email = getElement("#email").value;
  const dateFrom = getElement("#date-from");
  const dateTo = getElement("#date-to");
  const policy = getElement("#policy").value;

  const user = new User(
    name,
    birthDate,
    passport,
    phone,
    email,
    dateFrom.value,
    dateTo.value,
    policy,
    dodatniOsiguranici
  );

  // NOTE Prikazuje erore i prekida funkciju !!!
  const errors = new Validator(user);
  if (errors.length) {
    for (let error of errors) {
      const key = Object.entries(error)[0][0];
      const value = Object.entries(error)[0][1];

      if (key) {
        let errorMessage = getElement(`.${key}`);
        errorMessage.textContent = value;
      }
    }
    return;
  }

  axios
    .post("../osiguranja/kreirajpolisu", {
      user
    })
    .then((response) => {
      if (response.data['status'] == 'greska') {
        var text;
        for (let x in response.data.greske) {
          text += response.data.greske[x] + '\n';
        }
        let greske = text.replace("undefined", "");
        alert(greske);
      } else
        alert("Polisa je kreirana");
    })
    .catch((error) => {
      console.log(error);
    });
};

// Prikazi/Sakrij formu !!!
const grupnaPolisa = () => {
  const policy = getElement("#policy").value;
  const dodatni = getElement(".dodatni");
  if (policy === "grupno") {
    dodatni.style.display = "block";
    dodatni.style.transform = "translateX(0)";
    dodatni.style.opacity = "1";
  } else {
    dodatniOsiguranici = [];
    dodatni.style.display = "none";
    ul.innerHTML = "";
  }
};
grupnaPolisa();

//  Prikazuje formu za dodatne osiguranike !!!
const select = getElement("select");
select.addEventListener("change", grupnaPolisa);

//  Dodaje grupne osiguranike !!!
const grupa = getElement(".grupni-submit");
grupa.addEventListener("click", (e) => {
  e.preventDefault();
  //  Cisti prethodne error poruke !!!
  const errorMessages = document.querySelectorAll(".error-poruka");

  errorMessages.forEach((element) => (element.textContent = ""));
  let imeGrupa = getElement("#imeGrupa");
  let datumRodjenjaGrupa = getElement("#datumRodjenjaGrupa");
  let passportGrupa = getElement("#passportGrupa");

  const dodatniUser = {
    imeGrupa: imeGrupa.value,
    datumRodjenjaGrupa: datumRodjenjaGrupa.value,
    passportGrupa: passportGrupa.value,
  };

  // NOTE Validacija za dodatne osiguranike !!!
  const grupaErrors = new GrupniValidator(dodatniUser);
  if (grupaErrors.length) {
    for (let error of grupaErrors) {
      const key = Object.entries(error)[0][0];
      const value = Object.entries(error)[0][1];

      if (key) {
        let errorPoruka = getElement(`.${key}`);
        errorPoruka.textContent = value;
      }
    }
    return;
  }

  dodatniOsiguranici.push(dodatniUser);

  const prikaziDodatnogOsiguranika = document.createElement("li");
  prikaziDodatnogOsiguranika.textContent = dodatniUser.imeGrupa;
  ul.append(prikaziDodatnogOsiguranika);

  imeGrupa.value = "";
  datumRodjenjaGrupa.value = "";
  passportGrupa.value = "";
});

// NOTE Salje na beckend !!!
const slanje = getElement(".submit-btn");
slanje.addEventListener("click", handleForm);

// NOTE Razlika u danima !!!
const kolikoDana = (value1, value2) => {
  if (value1 && value2) {
    getElement(".travelDate").textContent = "";
    const vremenskaRazlika = function (val1, val2) {
      let dt1 = new Date(val1);
      let dt2 = new Date(val2);
      return Math.floor(
        (Date.UTC(dt2.getFullYear(), dt2.getMonth(), dt2.getDate()) -
          Date.UTC(dt1.getFullYear(), dt1.getMonth(), dt1.getDate())) /
        (1000 * 60 * 60 * 24)
      );
    };
    const razlika = vremenskaRazlika(value1, value2);
    if (razlika > 0) {
      const razlikaElement = getElement(".broj-dana");
      razlikaElement.textContent = `${razlika} dana`;
    }
  }
};

//  Racuna razliku u danima
const datum1 = getElement("#date-from");
const datum2 = getElement("#date-to");
datum1.addEventListener("change", () => {
  kolikoDana(datum1.value, datum2.value);
});
datum2.addEventListener("change", () => {
  kolikoDana(datum1.value, datum2.value);
});