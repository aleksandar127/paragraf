import User from "./User.js";

const getElement = (el) => {
  return document.querySelector(el);
};

export const handleForm = (e) => {
  e.preventDefault();

  // NOTE Cisti prethodne error poruke !!!
  const errorMessages = document.querySelectorAll(".error-message");
  errorMessages.forEach((element) => (element.textContent = ""));

  const name = getElement("#name").value;
  const birthDate = getElement("#birth-date").value;
  const passport = getElement("#passport").value;
  const phone = getElement("#phone").value;
  const email = getElement("#email").value;
  const dateFrom = getElement("#date-from").value;
  const dateTo = getElement("#date-to").value;
  const policy = getElement("#policy").value;

  const user = new User(
    name,
    birthDate,
    passport,
    phone,
    email,
    dateFrom,
    dateTo,
    policy,
    dodatniOsiguranici
  );

  console.log(user);

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
};
