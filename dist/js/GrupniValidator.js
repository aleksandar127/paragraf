export default class GrupniValidator {
  constructor(user) {
    this.user = user;
    this.errors = [];
    this.validacijaIme(this.user.imeGrupa);
    this.validacijaDatumRodjenja(this.user.datumRodjenjaGrupa);
    this.validacijaPassport(this.user.passportGrupa);

    return this.errors;
  }

  validacijaIme(value) {
    if (!value.length) this.errors.push({ imeGrupa: "Unesite Ime !" });
  }

  validacijaDatumRodjenja(value) {
    if (!value.length)
      this.errors.push({ datumRodjenjaGrupa: "Unesite Datum Rodjenja !" });
  }

  validacijaPassport(value) {
    if (!value.length)
      this.errors.push({ passportGrupa: "Unesite Broj Pasosa !" });
  }
}
