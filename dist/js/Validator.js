export default class Validator {
  constructor(user) {
    this.user = user;
    this.errors = [];
    this.validateName(this.user.name);
    this.validateBirthDate(this.user.birthDate);
    this.validatePassport(this.user.passport);
    this.validateEmail(this.user.email);
    this.validateTravelDate(this.user.dateFrom, this.user.dateTo);

    return this.errors;
  }

  validateName(value) {
    if (!value.length) this.errors.push({ name: "Unesite Vase Ime !" });
  }

  validateBirthDate(value) {
    if (!value.length)
      this.errors.push({ birthDate: "Unesite Datum Rodjenja !" });
  }

  validatePassport(value) {
    if (!value.length) this.errors.push({ passport: "Unesite Broj Pasosa !" });
  }

  validateEmail(value) {
    if (!value.length) {
      this.errors.push({ email: "Unesite Email !" });
      return;
    }
    let regex = /\S+@\S+\.\S+/;
    if (!regex.test(value)) {
      this.errors.push({ email: "Unesite Validan Email !" });
    }
  }

  validateTravelDate(value1, value2) {
    if (!value1.length || !value2.length)
      this.errors.push({ travelDate: "Unesite Datum Putovanja !" });
  }
}
