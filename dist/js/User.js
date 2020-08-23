export default class User {
  constructor(
    name,
    birthDate,
    passport,
    phone,
    email,
    dateFrom,
    dateTo,
    policy,
    dodatniOsiguranici
  ) {
    this.name = name;
    this.birthDate = birthDate;
    this.passport = passport;
    this.phone = phone;
    this.email = email;
    this.dateFrom = dateFrom;
    this.dateTo = dateTo;
    this.policy = policy;
    this.dodatniOsiguranici = dodatniOsiguranici;
  }
}